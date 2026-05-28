<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Marketers
{
    /** @var HttpClient */
    private $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /** @param array<string, mixed> $body */
    public function create(array $body, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/marketers', [], $body, $options);
        return $res['data'];
    }

    public function get(string $partnerId): array
    {
        $res = $this->http->request('GET', '/v1/marketers/' . $partnerId);
        return $res['data'];
    }

    /** @param array<string, mixed> $body */
    public function update(string $partnerId, array $body, array $options = []): array
    {
        $res = $this->http->request('PUT', '/v1/marketers/' . $partnerId, [], $body, $options);
        return $res['data'];
    }

    /** @param array{page?: int, limit?: int, status?: string, tier?: string} $params */
    public function list(array $params = [], array $options = []): array
    {
        $query = array_filter($params, function ($v) {
            return $v !== null && $v !== '';
        });
        $res = $this->http->request('GET', '/v1/marketers', $query, null, $options);
        return $res['data'];
    }

    public function deactivate(string $partnerId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/marketers/' . $partnerId . '/deactivate', [], null, $options);
        return $res['data'];
    }

    public function getReferralTree(string $partnerId): array
    {
        $res = $this->http->request('GET', '/v1/marketers/' . $partnerId . '/hierarchy');
        return $res['data'];
    }

    public function getUpline(string $partnerId): array
    {
        $res = $this->http->request('GET', '/v1/marketers/' . $partnerId . '/hierarchy');
        return $res['data'];
    }

    public function getReferralLink(string $partnerId): array
    {
        $res = $this->http->request('GET', '/v1/marketers/' . $partnerId . '/referral-link');
        return $res['data'];
    }

    /** @param array{from?: string, to?: string} $opts */
    public function getStats(string $partnerId, array $opts = []): array
    {
        $res = $this->http->request('GET', '/v1/marketers/' . $partnerId . '/metrics', array_filter($opts));
        return $res['data'];
    }

    /** @param array{period?: string, limit?: int} $opts */
    public function getLeaderboard(array $opts = []): array
    {
        $res = $this->http->request('GET', '/v1/marketers/leaderboard', array_filter($opts));
        return $res['data'];
    }

    /** @param array{limit?: int} $opts */
    public function getTopPerformers(array $opts = []): array
    {
        $res = $this->http->request('GET', '/v1/marketers/leaderboard', array_filter($opts));
        return $res['data'];
    }

    /** @param array{email: string, name?: string, referredBy?: string} $body */
    public function invite(array $body, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/marketers/invite', [], $body, $options);
        return $res['data'];
    }

    public function resendInvite(string $partnerId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/marketers/' . $partnerId . '/resend-invite', [], null, $options);
        return $res['data'];
    }

    public function getOnboardingStatus(string $partnerId): array
    {
        $res = $this->http->request('GET', '/v1/marketers/' . $partnerId . '/onboarding');
        return $res['data'];
    }

    public function sendAgreement(string $partnerId, string $templateId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/marketers/' . $partnerId . '/agreements/send', [], ['templateId' => $templateId], $options);
        return $res['data'];
    }

    public function getSignedDocuments(string $partnerId): array
    {
        $res = $this->http->request('GET', '/v1/marketers/' . $partnerId . '/documents/signed');
        return $res['data'];
    }

    public function getAgreementStatus(string $partnerId): array
    {
        $res = $this->http->request('GET', '/v1/marketers/' . $partnerId . '/agreement-status');
        return $res['data'];
    }
}
