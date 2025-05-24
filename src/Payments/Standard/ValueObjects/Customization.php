<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Payments\Standard\ValueObjects;

final readonly class Customization
{
    /**
     * Creates a new instance of the Customization class.
     *
     * @param  ?string  $title  The title of the customization.
     * @param  ?string  $description  The description of the customization.
     * @param  ?string  $logo  The logo of the customization.
     */
    public function __construct(
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $logo,
    ) {
        //
    }

    /**
     * Creates a new instance of the Customization class from an array.
     *
     * @param  array<string, string|null>  $data  The array to create the Customization from.
     * @return Customization The created Customization.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            logo: $data['logo'] ?? null,
        );
    }
}
