<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Exceptions;

use Exception;

final class InvalidDataException extends Exception
{
    /**
     * Creates a new instance of the InvalidDataException class.
     *
     * @param  string  $message  The error message.
     */
    public function __construct(string $message = 'Invalid data provided')
    {
        parent::__construct($message);
    }
}
