<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Account
{
    /** @var HttpClient */
    private $http;

    /** @var AccountApiKeys|null */
    private $apiKeys;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function get(): array
    {
        $res = $this->http->request('GET', '/v1/account');
        return $res['data'];
    }

    /** @param array<string, mixed> $body */
    public function update(array $body, array $options = []): array
    {
        $res = $this->http->request('PATCH', '/v1/account', [], $body, $options);
        return $res['data'];
    }

    public function getBranding(): array
    {
        $res = $this->http->request('GET', '/v1/account/branding');
        return $res['data'];
    }

    /** @param array{logo?: string, primaryColor?: string, companyName?: string} $body */
    public function updateBranding(array $body, array $options = []): array
    {
        $res = $this->http->request('PUT', '/v1/account/branding', [], $body, $options);
        return $res['data'];
    }

    public function apiKeys(): AccountApiKeys
    {
        if ($this->apiKeys === null) {
            $this->apiKeys = new AccountApiKeys($this->http);
        }
        return $this->apiKeys;
    }

    /** @param array{from?: string, to?: string} $params */
    public function getUsage(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/account/usage', array_filter($params));
        return $res['data'];
    }

    public function getPlan(): array
    {
        $res = $this->http->request('GET', '/v1/account/plan');
        return $res['data'];
    }

    public function getLimits(): array
    {
        $res = $this->http->request('GET', '/v1/account/limits');
        return $res['data'];
    }
}

class AccountApiKeys
{
    /** @var HttpClient */
    private $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function list(): array
    {
        $res = $this->http->request('GET', '/v1/api-keys');
        return $res['data'];
    }

    /** @param array{name?: string, scopes?: string[]} $body */
    public function create(array $body = [], array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/api-keys', [], $body, $options);
        return $res['data'];
    }

    public function revoke(string $keyId): void
    {
        $this->http->request('DELETE', '/v1/api-keys/' . $keyId);
    }
}
