<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class PhoneNumbers
{
    /** @var HttpClient */
    private $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /** @param array{countryIso?: string, areaCode?: string, type?: string} $params */
    public function search(array $params = [], array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/phone-numbers', [], $params, $options);
        return $res['data'];
    }

    public function list(array $options = []): array
    {
        $res = $this->http->request('GET', '/v1/phone-numbers', [], null, $options);
        return $res['data'];
    }

    public function get(string $numberId): array
    {
        $res = $this->http->request('GET', '/v1/phone-numbers/' . $numberId);
        return $res['data'];
    }

    public function release(string $numberId): void
    {
        $this->http->request('DELETE', '/v1/phone-numbers/' . $numberId);
    }

    public function assignToTeam(string $numberId, string $teamId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/phone-numbers/' . $numberId . '/assign-team', [], ['teamId' => $teamId], $options);
        return $res['data'];
    }

    public function unassignFromTeam(string $numberId, string $teamId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/phone-numbers/' . $numberId . '/unassign-team', [], ['teamId' => $teamId], $options);
        return $res['data'];
    }

    /** @param array{from?: string, to?: string} $params */
    public function getUsage(string $numberId, array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/phone-numbers/' . $numberId . '/usage', array_filter($params));
        return $res['data'];
    }

    public function get10DLCStatus(string $numberId): array
    {
        $res = $this->http->request('GET', '/v1/phone-numbers/' . $numberId . '/10dlc-status');
        return $res['data'];
    }
}
