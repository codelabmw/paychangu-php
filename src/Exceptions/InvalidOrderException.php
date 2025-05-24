<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Exceptions;

use Exception;

final class InvalidOrderException extends Exception
{
    /**
     * Creates a new instance of the InvalidOrderException class.
     *
     * @param  string  $message  The error message.
     */
    public function __construct(string $message = 'Invalid order instance given')
    {
        parent::__construct($message);
    }
}
