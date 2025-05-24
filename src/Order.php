<?php

namespace Codelabmw\Paychangu;

/**
 * @internal
 */
abstract class Order
{
    /**
     * Returns the order as an array.
     *
     * @return array<string, mixed> The order as an array.
     */
    abstract public function toArray(): array;
}