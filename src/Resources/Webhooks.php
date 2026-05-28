<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Webhooks
{
    /** @var HttpClient */
    private $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /** @param array{url: string, events: string[], secret?: string} $body */
    public function create(array $body, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/integrations/webhooks', [], $body, $options);
        return $res['data'];
    }

    public function list(array $options = []): array
    {
        $res = $this->http->request('GET', '/v1/integrations/webhooks', [], null, $options);
        return $res['data'];
    }

    public function get(string $webhookId): array
    {
        $res = $this->http->request('GET', '/v1/integrations/webhooks/' . $webhookId);
        return $res['data'];
    }

    /** @param array{url?: string, events?: string[], secret?: string} $body */
    public function update(string $webhookId, array $body, array $options = []): array
    {
        $res = $this->http->request('PUT', '/v1/integrations/webhooks/' . $webhookId, [], $body, $options);
        return $res['data'];
    }

    public function delete(string $webhookId): void
    {
        $this->http->request('DELETE', '/v1/integrations/webhooks/' . $webhookId);
    }

    public function test(string $webhookId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/integrations/webhooks/' . $webhookId . '/test', [], null, $options);
        return $res['data'];
    }

    public function getLogs(string $webhookId): array
    {
        $res = $this->http->request('GET', '/v1/integrations/webhooks/' . $webhookId . '/logs');
        return $res['data'];
    }

    public function retry(string $webhookId, string $logId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/integrations/webhooks/' . $webhookId . '/retry', [], ['logId' => $logId], $options);
        return $res['data'];
    }

    /** Verify signature and parse JSON. Returns decoded event array or throws. */
    public function constructEvent(string $rawBody, string $signature, string $secret): array
    {
        $auth = new Auth($this->http);
        if (!$auth->verifyWebhookSignature($rawBody, $signature, $secret)) {
            throw new \UnexpectedValueException('Webhook signature verification failed');
        }
        $decoded = json_decode($rawBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \UnexpectedValueException('Invalid JSON in webhook payload');
        }
        return $decoded;
    }
}
