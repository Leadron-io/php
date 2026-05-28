<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Sequences
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
        $res = $this->http->request('POST', '/v1/sequences', [], $body, $options);
        return $res['data'];
    }

    public function get(string $sequenceId): array
    {
        $res = $this->http->request('GET', '/v1/sequences/' . $sequenceId);
        return $res['data'];
    }

    public function list(array $options = []): array
    {
        $res = $this->http->request('GET', '/v1/sequences', [], null, $options);
        return $res['data'];
    }

    /** @param array<string, mixed> $body */
    public function update(string $sequenceId, array $body, array $options = []): array
    {
        $res = $this->http->request('PUT', '/v1/sequences/' . $sequenceId, [], $body, $options);
        return $res['data'];
    }

    public function delete(string $sequenceId): void
    {
        $this->http->request('DELETE', '/v1/sequences/' . $sequenceId);
    }

    public function activate(string $sequenceId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/sequences/' . $sequenceId . '/activate', [], null, $options);
        return $res['data'];
    }

    public function pause(string $sequenceId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/sequences/' . $sequenceId . '/pause', [], null, $options);
        return $res['data'];
    }

    public function enrollLead(string $sequenceId, string $leadId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/sequences/' . $sequenceId . '/enroll', [], ['leadId' => $leadId], $options);
        return $res['data'];
    }

    /** @param string[] $leadIds */
    public function enrollBulk(string $sequenceId, array $leadIds, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/sequences/' . $sequenceId . '/enroll/bulk', [], ['leadIds' => $leadIds], $options);
        return $res['data'];
    }

    public function unenrollLead(string $sequenceId, string $leadId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/sequences/' . $sequenceId . '/unenroll/' . $leadId, [], null, $options);
        return $res['data'];
    }

    public function getEnrolledLeads(string $sequenceId): array
    {
        $res = $this->http->request('GET', '/v1/sequences/' . $sequenceId . '/enrolled');
        return $res['data'];
    }

    /** @param array<string, mixed> $step */
    public function addStep(string $sequenceId, array $step, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/sequences/' . $sequenceId . '/steps', [], $step, $options);
        return $res['data'];
    }

    /** @param array<string, mixed> $body */
    public function updateStep(string $sequenceId, string $stepId, array $body, array $options = []): array
    {
        $res = $this->http->request('PUT', '/v1/sequences/' . $sequenceId . '/steps/' . $stepId, [], $body, $options);
        return $res['data'];
    }

    public function deleteStep(string $sequenceId, string $stepId): void
    {
        $this->http->request('DELETE', '/v1/sequences/' . $sequenceId . '/steps/' . $stepId);
    }

    /** @param string[] $stepOrder */
    public function reorderSteps(string $sequenceId, array $stepOrder, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/sequences/' . $sequenceId . '/steps/reorder', [], ['stepOrder' => $stepOrder], $options);
        return $res['data'];
    }
}
