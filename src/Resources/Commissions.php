<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Commissions
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
        $res = $this->http->request('POST', '/v1/commissions/records', [], $body, $options);
        return $res['data'];
    }

    public function get(string $commissionId): array
    {
        $res = $this->http->request('GET', '/v1/commissions/records/' . $commissionId);
        return $res['data'];
    }

    /** @param array{partnerId?: string, status?: string, from?: string, to?: string, page?: int, limit?: int} $params */
    public function list(array $params = [], array $options = []): array
    {
        $query = array_filter($params, function ($v) {
            return $v !== null && $v !== '';
        });
        $res = $this->http->request('GET', '/v1/commissions/records', $query, null, $options);
        return $res['data'];
    }

    public function approve(string $commissionId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/commissions/records/' . $commissionId . '/approve', [], null, $options);
        return $res['data'];
    }

    public function reject(string $commissionId, ?string $reason = null, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/commissions/records/' . $commissionId . '/reject', [], $reason !== null ? ['reason' => $reason] : null, $options);
        return $res['data'];
    }

    /** @param array{paidAt?: string, paymentMethod?: string, transactionRef?: string} $opts */
    public function markPaid(string $commissionId, array $opts = [], array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/commissions/records/' . $commissionId . '/mark-paid', [], $opts, $options);
        return $res['data'];
    }

    public function getRules(): array
    {
        $res = $this->http->request('GET', '/v1/commissions/rules');
        return $res['data'];
    }

    /** @param array<string, mixed> $body */
    public function createRule(array $body, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/commissions/rules', [], $body, $options);
        return $res['data'];
    }

    /** @param array<string, mixed> $body */
    public function updateRule(string $ruleId, array $body, array $options = []): array
    {
        $res = $this->http->request('PUT', '/v1/commissions/rules/' . $ruleId, [], $body, $options);
        return $res['data'];
    }

    public function deleteRule(string $ruleId): void
    {
        $this->http->request('DELETE', '/v1/commissions/rules/' . $ruleId);
    }

    public function getPayoutSummary(string $partnerId): array
    {
        $res = $this->http->request('GET', '/v1/commissions/partners/' . $partnerId . '/payout-summary');
        return $res['data'];
    }

    public function getWalletBalance(string $partnerId): array
    {
        $res = $this->http->request('GET', '/v1/commissions/partners/' . $partnerId . '/wallet');
        return $res['data'];
    }

    public function requestPayout(string $partnerId, float $amount, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/commissions/partners/' . $partnerId . '/payouts', [], ['amount' => $amount], $options);
        return $res['data'];
    }

    public function getPayoutHistory(string $partnerId): array
    {
        $res = $this->http->request('GET', '/v1/commissions/partners/' . $partnerId . '/payouts');
        return $res['data'];
    }

    /** @param array{from?: string, to?: string, groupBy?: string} $params */
    public function getSummary(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/commissions/summary', array_filter($params));
        return $res['data'];
    }

    /** @param array{from?: string, to?: string, page?: int, limit?: int} $params */
    public function getByPartner(string $partnerId, array $params = []): array
    {
        $query = array_merge(['partnerId' => $partnerId], array_filter($params));
        $res = $this->http->request('GET', '/v1/commissions/records', $query);
        return $res['data'];
    }

    public function getTotalOwed(): array
    {
        $res = $this->http->request('GET', '/v1/commissions/total-owed');
        return $res['data'];
    }

    /** @param array{from?: string, to?: string} $params */
    public function getTotalPaid(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/commissions/total-paid', array_filter($params));
        return $res['data'];
    }
}
