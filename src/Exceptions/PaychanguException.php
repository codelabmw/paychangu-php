<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Exceptions;

use Exception;

final class PaychanguException extends Exception
{
    /**
     * Create a new PaychanguException instance.
     *
     * @param  string  $message  The error message.
     * @param  int  $statusCode  The HTTP status code.
     */
    public function __construct(string $message, public readonly int $statusCode = 400)
    {
        parent::__construct($message);
    }
}
