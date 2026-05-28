<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Auth
{
    /** @var HttpClient */
    private $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /** @return array{valid: bool} */
    public function validate(): array
    {
        $res = $this->http->request('GET', '/v1/api-keys/validate');
        return ['valid' => $res['data']['valid'] ?? true];
    }

    /** @return array{scopes: string[]} */
    public function getScopes(): array
    {
        $res = $this->http->request('GET', '/v1/api-keys/scopes');
        return ['scopes' => $res['data']['scopes'] ?? []];
    }

    /** Verify webhook signature (HMAC-SHA256). Payload is raw body string. */
    public function verifyWebhookSignature(string $payload, string $signature, string $secret): bool
    {
        $expected = hash_hmac('sha256', $payload, $secret, false);
        return hash_equals($expected, $signature) || hash_equals('sha256=' . $expected, $signature);
    }
}
