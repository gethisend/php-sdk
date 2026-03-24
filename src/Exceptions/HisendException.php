<?php

namespace Hisend\Exceptions;

use Exception;
use Throwable;

class HisendException extends Exception
{
    protected ?int $statusCode;

    public function __construct(string $message, ?int $statusCode = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $statusCode ?? 0, $previous);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}
