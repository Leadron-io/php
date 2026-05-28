<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Leads
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
        $res = $this->http->request('POST', '/v1/leads', [], $body, $options);
        return $res['data'];
    }

    public function get(string $leadId): array
    {
        $res = $this->http->request('GET', '/v1/leads/' . $leadId);
        return $res['data'];
    }

    /** @param array<string, mixed> $body */
    public function update(string $leadId, array $body, array $options = []): array
    {
        $res = $this->http->request('PUT', '/v1/leads/' . $leadId, [], $body, $options);
        return $res['data'];
    }

    public function delete(string $leadId): void
    {
        $this->http->request('DELETE', '/v1/leads/' . $leadId);
    }

    /**
     * @param array{page?: int, limit?: int, status?: string, assignedTo?: string, source?: string, sort?: string, order?: string, from?: string, to?: string} $params
     * @return array{data: array, pagination?: array, autoPaginate: callable}
     */
    public function list(array $params = [], array $options = []): array
    {
        $query = $this->buildQuery($params);
        $res = $this->http->request('GET', '/v1/leads', $query, null, $options);
        $data = is_array($res['data']) ? $res['data'] : [];
        $pagination = $res['pagination'] ?? null;
        $self = $this;
        $autoPaginate = (function () use ($params, $options, $data, &$pagination, $self) {
            foreach ($data as $item) {
                yield $item;
            }
            $page = $params['page'] ?? 1;
            $limit = $params['limit'] ?? 20;
            while (!empty($pagination['hasNext'])) {
                $page++;
                $nextParams = array_merge($params, ['page' => $page, 'limit' => $limit]);
                $next = $self->list($nextParams, $options);
                foreach ($next['data'] as $item) {
                    yield $item;
                }
                $pagination = $next['pagination'] ?? null;
            }
        })();
        return ['data' => $data, 'pagination' => $pagination, 'autoPaginate' => $autoPaginate];
    }

    public function assign(string $leadId, string $userId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/leads/' . $leadId . '/assign', [], ['userId' => $userId], $options);
        return $res['data'];
    }

    public function updateStatus(string $leadId, string $status, array $options = []): array
    {
        $res = $this->http->request('PUT', '/v1/leads/' . $leadId . '/status', [], ['status' => $status], $options);
        return $res['data'];
    }

    public function addNote(string $leadId, string $content, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/leads/' . $leadId . '/notes', [], ['content' => $content], $options);
        return $res['data'];
    }

    public function getNotes(string $leadId): array
    {
        $res = $this->http->request('GET', '/v1/leads/' . $leadId . '/notes');
        return $res['data'];
    }

    public function getTimeline(string $leadId): array
    {
        $res = $this->http->request('GET', '/v1/leads/' . $leadId . '/timeline');
        return $res['data'];
    }

    /** @param array{dealValue?: float, closedAt?: string, notes?: string} $opts */
    public function markConverted(string $leadId, array $opts = [], array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/leads/' . $leadId . '/convert', [], $opts, $options);
        return $res['data'];
    }

    /** @param array<int, array> $leads */
    public function bulkCreate(array $leads, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/leads/bulk', [], ['leads' => $leads], $options);
        return $res['data'];
    }

    /** @param string[] $leadIds */
    public function bulkAssign(array $leadIds, string $partnerId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/leads/bulk/assign', [], ['leadIds' => $leadIds, 'partnerId' => $partnerId], $options);
        return $res['data'];
    }

    /** @param string[] $leadIds */
    public function bulkUpdateStatus(array $leadIds, string $status, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/leads/bulk/status', [], ['leadIds' => $leadIds, 'status' => $status], $options);
        return $res['data'];
    }

    public function search(string $q, array $options = []): array
    {
        $res = $this->http->request('GET', '/v1/search/leads', ['q' => $q], null, $options);
        return $res['data'];
    }

    /** @param array{status?: string, source?: string, assignedTo?: string, from?: string, to?: string} $params */
    public function filter(array $params = [], array $options = []): array
    {
        $query = $this->buildQuery($params);
        $res = $this->http->request('GET', '/v1/leads', $query, null, $options);
        return $res['data'];
    }

    /** @param array<string, mixed> $params */
    private function buildQuery(array $params): array
    {
        $q = [];
        foreach (['page', 'limit', 'status', 'assignedTo', 'source', 'sort', 'order', 'from', 'to'] as $k) {
            if (array_key_exists($k, $params) && $params[$k] !== null && $params[$k] !== '') {
                $q[$k] = $params[$k];
            }
        }
        return $q;
    }
}
