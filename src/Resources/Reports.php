<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Reports
{
    /** @var HttpClient */
    private $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /** @param array{from?: string, to?: string, groupBy?: string, format?: string} $params */
    public function leads(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/reports/leads', array_filter($params));
        return $res['data'];
    }

    /** @param array{from?: string, to?: string} $params */
    public function commissions(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/reports/commissions', array_filter($params));
        return $res['data'];
    }

    /** @param array{from?: string, to?: string} $params */
    public function partners(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/reports/partners', array_filter($params));
        return $res['data'];
    }

    /** @param array<string, mixed> $reportConfig */
    public function export(array $reportConfig, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/reports/export', [], $reportConfig, $options);
        return $res['data'];
    }
}
