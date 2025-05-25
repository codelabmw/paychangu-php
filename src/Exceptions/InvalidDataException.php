<?php

namespace Codelabmw\Paychangu\Exceptions;

class InvalidDataException extends \Exception
{
    /**
     * Creates a new instance of the InvalidDataException class.
     *
     * @param string $message The error message.
     */
    public function __construct(string $message = 'Invalid data provided')
    {
        parent::__construct($message);
    }
}