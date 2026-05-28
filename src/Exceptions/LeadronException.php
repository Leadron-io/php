<?php

namespace Leadron\Exceptions;

use Exception;
use Throwable;

class LeadronException extends Exception
{
    /** @var int|null */
    protected $statusCode;

    /** @var mixed */
    protected $responseBody;

    public function __construct(string $message = '', ?int $statusCode = null, $responseBody = null, Throwable $previous = null)
    {
        parent::__construct($message, $statusCode ?? 0, $previous);
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }
}
