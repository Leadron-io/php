<?php

namespace Leadron\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Leadron\Exceptions\LeadronAuthException;
use Leadron\Exceptions\LeadronException;
use Leadron\Exceptions\LeadronRateLimitException;
use Leadron\Exceptions\LeadronValidationException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private const DEFAULT_BASE_URL = 'https://api.leadron.io';
    private const DEFAULT_RETRIES = 3;
    private const RETRY_DELAY_MS = 1000;

    /** @var string */
    private $apiKey;

    /** @var string */
    private $baseUrl;

    /** @var int */
    private $maxRetries;

    /** @var GuzzleClient */
    private $guzzle;

    /** @var int|null */
    private $lastRateLimitRemaining;

    public function __construct(string $apiKey, ?string $baseUrl = null, int $maxRetries = self::DEFAULT_RETRIES)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl ?? self::DEFAULT_BASE_URL, '/');
        $this->maxRetries = $maxRetries;
        $this->guzzle = new GuzzleClient([
            'base_uri' => $this->baseUrl . '/',
            'timeout' => 30,
            'http_errors' => false,
        ]);
    }

    /**
     * @param array<string, string|int|null> $query
     * @param array<string, string> $options Optional 'request_id', 'idempotency_key'
     * @return array{data: mixed, pagination?: array, rate_limit_remaining?: int}
     */
    public function request(string $method, string $path, array $query = [], $body = null, array $options = []): array
    {
        $path = ltrim($path, '/');
        $uri = $path . ($query ? '?' . http_build_query(array_filter($query, function ($v) {
            return $v !== null && $v !== '';
        })) : '');

        $headers = [
            'X-API-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        if (!empty($options['request_id'])) {
            $headers['X-Request-Id'] = $options['request_id'];
        }
        if (!empty($options['idempotency_key'])) {
            $headers['Idempotency-Key'] = $options['idempotency_key'];
        }

        $bodyString = $body !== null ? json_encode($body) : null;
        $request = new Request($method, $uri, $headers, $bodyString);

        $lastError = null;
        for ($attempt = 0; $attempt <= $this->maxRetries; $attempt++) {
            try {
                $response = $this->guzzle->send($request);
                $this->lastRateLimitRemaining = $this->parseRateLimitRemaining($response);

                if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                    $responseBody = (string) $response->getBody();
                    $decoded = $responseBody ? json_decode($responseBody, true) : [];
                    $data = $decoded['data'] ?? $decoded;
                    $pagination = $decoded['pagination'] ?? null;
                    return [
                        'data' => $data,
                        'pagination' => $pagination,
                        'rate_limit_remaining' => $this->lastRateLimitRemaining,
                    ];
                }

                $message = $this->extractMessage($response);
                $statusCode = $response->getStatusCode();
                $responseBody = (string) $response->getBody();
                $decodedBody = $responseBody ? json_decode($responseBody, true) : null;

                if ($statusCode === 401) {
                    throw new LeadronAuthException($message, $statusCode, $decodedBody);
                }
                if ($statusCode === 422) {
                    throw new LeadronValidationException($message, $statusCode, $decodedBody);
                }
                if ($statusCode === 429) {
                    $retryAfter = (int) ($response->getHeader('Retry-After')[0] ?? self::RETRY_DELAY_MS / 1000);
                    if ($attempt < $this->maxRetries) {
                        usleep($retryAfter * 1000000);
                        continue;
                    }
                    throw new LeadronRateLimitException($message, $retryAfter, $statusCode, $decodedBody);
                }
                if ($statusCode >= 500 && $statusCode < 600 && $attempt < $this->maxRetries) {
                    $lastError = new LeadronException($message, $statusCode, $decodedBody);
                    usleep(self::RETRY_DELAY_MS * 1000 * ($attempt + 1));
                    continue;
                }

                throw new LeadronException($message, $statusCode, $decodedBody);
            } catch (LeadronAuthException|LeadronValidationException|LeadronRateLimitException $e) {
                throw $e;
            } catch (RequestException $e) {
                $lastError = new LeadronException($e->getMessage(), 0, null, $e);
                if ($attempt < $this->maxRetries) {
                    usleep(self::RETRY_DELAY_MS * 1000 * ($attempt + 1));
                    continue;
                }
                throw $lastError;
            }
        }

        throw $lastError ?? new LeadronException('Request failed');
    }

    public function getRateLimitStatus(): ?int
    {
        return $this->lastRateLimitRemaining;
    }

    private function parseRateLimitRemaining(ResponseInterface $response): ?int
    {
        $header = $response->getHeader('X-RateLimit-Remaining');
        if (empty($header)) {
            return null;
        }
        $val = (int) $header[0];
        return $val >= 0 ? $val : null;
    }

    private function extractMessage(ResponseInterface $response): string
    {
        $body = (string) $response->getBody();
        if ($body) {
            $decoded = json_decode($body, true);
            if (isset($decoded['message']) && is_string($decoded['message'])) {
                return $decoded['message'];
            }
        }
        return $response->getReasonPhrase() ?: 'HTTP ' . $response->getStatusCode();
    }
}
