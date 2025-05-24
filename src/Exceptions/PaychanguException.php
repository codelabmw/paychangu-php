<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Exceptions;

use Exception;

class PaychanguException extends Exception
{
    /**
     * Create a new PaychanguException instance.
     *
     * @param string $message The error message.
     * @param int $statusCode The HTTP status code.
     */
    public function __construct(string $message, private int $statusCode = 0)
    {
        parent::__construct($message);
    }

    /**
     * Get the status code.
     *
     * @return int The status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
