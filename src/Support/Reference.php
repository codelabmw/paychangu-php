<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Support;

use Codelabmw\Testament\Testament;
use InvalidArgumentException;
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
     * @param  int  $length  The length of the reference after the prefix.
     * @param  string  $prefixSeparator  The separator between the prefix and the reference.
     * @param  bool  $pretty  Adds a separator between reference characters.
     */
    public function __construct(
        string $prefix = 'REF',
        int $length = 18,
        string $prefixSeparator = '-',
        bool $pretty = true,
    ) {
        if ($length < 6) {
            throw new InvalidArgumentException('Reference length must be at least 6');
        }

        $alpha = Testament::alpha($length);

        if ($pretty) {
            $first = mb_substr($alpha, 0, 6);
            $rest = mb_substr($alpha, 6);
            $chunks = [$first];

            if ($rest !== '') {
                $chunks = array_merge($chunks, mb_str_split($rest, 4));
            }

            $alpha = implode('-', $chunks);
        }

        $this->ref = $prefix.$prefixSeparator.$alpha;
    }

    /**
     * Returns the string representation of the reference.
     *
     * @return string The string representation of the reference.
     */
    public function __toString(): string
    {
        return $this->ref;
    }
}
