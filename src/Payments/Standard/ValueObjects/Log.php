<?php

namespace Codelabmw\Paychangu\Payments\Standard\ValueObjects;

final class Log
{
    public function __construct(
        public readonly string $type,
        public readonly string $message,
        public readonly string $createdAt,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'],
            message: $data['message'],
            createdAt: $data['created_at'],
        );
    }
}
