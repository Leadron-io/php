<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Analytics
{
    /** @var HttpClient */
    private $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /** @param array{from?: string, to?: string} $params */
    public function getOverview(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/analytics/overview', array_filter($params));
        return $res['data'];
    }

    /** @param array{from?: string, to?: string, groupBy?: string} $params */
    public function getLeadMetrics(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/analytics/leads', array_filter($params));
        return $res['data'];
    }

    /** @param array{from?: string, to?: string} $params */
    public function getCommissionMetrics(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/analytics/commissions', array_filter($params));
        return $res['data'];
    }

    /** @param array{from?: string, to?: string} $params */
    public function getPartnerMetrics(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/analytics/partners', array_filter($params));
        return $res['data'];
    }

    /** @param array{from?: string, to?: string} $params */
    public function getConversionRate(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/analytics/conversion-rate', array_filter($params));
        return $res['data'];
    }

    /** @param array{from?: string, to?: string} $params */
    public function getSmsMetrics(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/analytics/sms', array_filter($params));
        return $res['data'];
    }
}
