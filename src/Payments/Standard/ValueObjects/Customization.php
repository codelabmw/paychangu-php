<?php

namespace Codelabmw\Paychangu\Payments\Standard\ValueObjects;

final class Customization
{
    public function __construct(
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $logo,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            logo: $data['logo'] ?? null,
        );
    }
}
