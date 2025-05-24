<?php

namespace Codelabmw\Paychangu;

/**
 * @internal
 */
abstract class Payment
{
    /**
     * Returns the transaction reference of the payment.
     *
     * @return string The transaction reference of the payment.
     */
    abstract public function reference(): string;
}