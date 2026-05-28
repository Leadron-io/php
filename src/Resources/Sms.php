<?php

namespace Leadron\Resources;

use Leadron\Http\Client as HttpClient;

class Sms
{
    /** @var HttpClient */
    private $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /** @param array{to: string, message?: string, body?: string, from?: string} $body */
    public function send(array $body, array $options = []): array
    {
        if (isset($body['message']) && !isset($body['body'])) {
            $body['body'] = $body['message'];
        }
        $res = $this->http->request('POST', '/v1/communications/send/sms', [], $body, $options);
        return $res['data'];
    }

    public function getInbox(?string $phoneNumberId = null): array
    {
        $query = $phoneNumberId !== null ? ['phoneNumberId' => $phoneNumberId] : [];
        $res = $this->http->request('GET', '/v1/communications/inbox', $query);
        return $res['data'];
    }

    public function getOutbox(?string $phoneNumberId = null): array
    {
        $query = $phoneNumberId !== null ? ['phoneNumberId' => $phoneNumberId] : [];
        $res = $this->http->request('GET', '/v1/communications/outbox', $query);
        return $res['data'];
    }

    public function getConversation(string $leadId): array
    {
        $res = $this->http->request('GET', '/v1/communications/conversations/' . $leadId);
        return $res['data'];
    }

    /** @param array{from?: string, to?: string} $params */
    public function getUsage(array $params = []): array
    {
        $res = $this->http->request('GET', '/v1/communications/usage', array_filter($params));
        return $res['data'];
    }
}
