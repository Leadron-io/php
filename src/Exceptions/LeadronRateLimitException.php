<?php

namespace Leadron\Exceptions;

class LeadronRateLimitException extends LeadronException
{
    /** @var int */
    protected $retryAfter;

    public function __construct(string $message = '', int $retryAfter = 60, ?int $statusCode = 429, $responseBody = null)
    {
        parent::__construct($message, $statusCode, $responseBody);
        $this->retryAfter = $retryAfter;
    }

    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }
}
