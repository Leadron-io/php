<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Documents
{
    /** @var HttpClient */
    private $http;

    /** @var DocumentsTemplates|null */
    private $templates;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function templates(): DocumentsTemplates
    {
        if ($this->templates === null) {
            $this->templates = new DocumentsTemplates($this->http);
        }
        return $this->templates;
    }

    /** @param array{templateId: string, recipientEmail: string, recipientName: string, customFields?: array} $body */
    public function send(array $body, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/documents/send', [], $body, $options);
        return $res['data'];
    }

    public function sendToPartner(string $templateId, string $partnerId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/documents/send-to-partner', [], ['templateId' => $templateId, 'partnerId' => $partnerId], $options);
        return $res['data'];
    }

    public function get(string $documentId): array
    {
        $res = $this->http->request('GET', '/v1/documents/' . $documentId);
        return $res['data'];
    }

    /** @param array{status?: string, partnerId?: string} $params */
    public function list(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/documents', array_filter($params));
        return $res['data'];
    }

    public function getStatus(string $documentId): array
    {
        $res = $this->http->request('GET', '/v1/documents/' . $documentId . '/status');
        return $res['data'];
    }

    public function download(string $documentId): string
    {
        $res = $this->http->request('GET', '/v1/documents/' . $documentId . '/download');
        return is_string($res['data']) ? $res['data'] : json_encode($res['data']);
    }

    public function getAuditTrail(string $documentId): array
    {
        $res = $this->http->request('GET', '/v1/documents/' . $documentId . '/audit-trail');
        return $res['data'];
    }

    public function void(string $documentId, ?string $reason = null, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/documents/' . $documentId . '/void', [], $reason !== null ? ['reason' => $reason] : null, $options);
        return $res['data'];
    }

    public function resend(string $documentId, array $options = []): array
    {
        $res = $this->http->request('POST', '/v1/documents/' . $documentId . '/resend', [], null, $options);
        return $res['data'];
    }
}

class DocumentsTemplates
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
        $res = $this->http->request('POST', '/v1/documents/templates', [], $body, $options);
        return $res['data'];
    }

    public function list(): array
    {
        $res = $this->http->request('GET', '/v1/documents/templates');
        return $res['data'];
    }

    public function get(string $templateId): array
    {
        $res = $this->http->request('GET', '/v1/documents/templates/' . $templateId);
        return $res['data'];
    }

    /** @param array<string, mixed> $body */
    public function update(string $templateId, array $body, array $options = []): array
    {
        $res = $this->http->request('PUT', '/v1/documents/templates/' . $templateId, [], $body, $options);
        return $res['data'];
    }

    public function delete(string $templateId): void
    {
        $this->http->request('DELETE', '/v1/documents/templates/' . $templateId);
    }
}
