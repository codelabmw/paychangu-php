<?php

namespace Codelabmw\Paychangu\Exceptions;

use Exception;

class InvalidOrderException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid order instance given');
    }
}
