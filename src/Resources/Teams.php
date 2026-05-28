<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Teams
{
    /** @var HttpClient */
    private $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /** @param array{name: string, description?: string} $body */
    public function create(array $body, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/teams', [], $body, $options);
        return $res['data'];
    }

    public function get(string $teamId): array
    {
        $res = $this->http->request('GET', '/v1/teams/' . $teamId);
        return $res['data'];
    }

    public function list(array $options = []): array
    {
        $res = $this->http->request('GET', '/v1/teams', [], null, $options);
        return $res['data'];
    }

    /** @param array<string, mixed> $body */
    public function update(string $teamId, array $body, array $options = []): array
    {
        $res = $this->http->request('PUT', '/v1/teams/' . $teamId, [], $body, $options);
        return $res['data'];
    }

    public function delete(string $teamId): void
    {
        $this->http->request('DELETE', '/v1/teams/' . $teamId);
    }

    public function addMember(string $teamId, string $userId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/teams/' . $teamId . '/members', [], ['userId' => $userId], $options);
        return $res['data'];
    }

    public function removeMember(string $teamId, string $userId): void
    {
        $this->http->request('DELETE', '/v1/teams/' . $teamId . '/members/' . $userId);
    }

    public function getMembers(string $teamId): array
    {
        $res = $this->http->request('GET', '/v1/teams/' . $teamId . '/members');
        return $res['data'];
    }

    public function assignLead(string $teamId, string $leadId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/teams/' . $teamId . '/assign/lead', [], ['leadId' => $leadId], $options);
        return $res['data'];
    }

    public function assignPhoneNumber(string $teamId, string $numberId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/teams/' . $teamId . '/assign/phone-number', [], ['numberId' => $numberId], $options);
        return $res['data'];
    }

    /** @param array{from?: string, to?: string} $params */
    public function getStats(string $teamId, array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/teams/' . $teamId . '/stats', array_filter($params));
        return $res['data'];
    }

    public function getLeaderboard(): array
    {
        $res = $this->http->request('GET', '/v1/teams/leaderboard');
        return $res['data'];
    }
}
