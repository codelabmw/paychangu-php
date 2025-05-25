<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Support;

use Codelabmw\Testament\Testament;
use Stringable;

final readonly class Reference implements Stringable
{
    /**
     * The prefix of the reference.
     */
    public readonly string $ref;

    /**
     * Creates a new reference instance.
     *
     * @param  string  $prefix  The prefix of the reference.
     * @param  int  $length  The total length of the reference including the prefix.
     */
    public function __construct(
        string $prefix = 'REF',
        int $length = 16,
    ) {
        $this->ref = $prefix.'-'.Testament::alpha($length - (mb_strlen($prefix) + 1));
    }

    /**
     * Returns the string representation of the reference.
     *
     * @return string The string representation of the reference.
     */
    public function __toString(): string
    {
        return mb_strtolower($this->ref);
    }
}
