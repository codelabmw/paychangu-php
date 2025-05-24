<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Payments\Standard\ValueObjects;

final readonly class Log
{
    /**
     * Creates a new instance of the Log class.
     *
     * @param  string  $type  The type of the log.
     * @param  string  $message  The message of the log.
     * @param  string  $createdAt  The created at date of the log.
     */
    public function __construct(
        public readonly string $type,
        public readonly string $message,
        public readonly string $createdAt,
    ) {
        //
    }

    /**
     * Creates a new instance of the Log class from an array.
     *
     * @param  array<string, string>  $data  The array to create the Log from.
     * @return Log The created Log.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'],
            message: $data['message'],
            createdAt: $data['created_at'],
        );
    }
}
