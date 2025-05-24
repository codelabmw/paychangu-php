<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Payments\Standard\ValueObjects;

final readonly class Authorization
{
    /**
     * Creates a new instance of the Authorization class.
     *
     * @param  ?string  $channel  The channel of the authorization.
     * @param  ?string  $cardNumber  The card number of the authorization.
     * @param  ?string  $expiry  The expiry date of the authorization.
     * @param  ?string  $brand  The brand of the authorization.
     * @param  ?string  $provider  The provider of the authorization.
     * @param  ?string  $mobileNumber  The mobile number of the authorization.
     * @param  ?string  $completedAt  The completed at date of the authorization.
     */
    public function __construct(
        public readonly ?string $channel,
        public readonly ?string $cardNumber,
        public readonly ?string $expiry,
        public readonly ?string $brand,
        public readonly ?string $provider,
        public readonly ?string $mobileNumber,
        public readonly ?string $completedAt,
    ) {
        //
    }

    /**
     * Creates a new instance of the Authorization class from an array.
     *
     * @param  array<string, string|null>  $data  The array to create the Authorization from.
     * @return Authorization The created Authorization.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            channel: $data['channel'] ?? null,
            cardNumber: $data['card_number'] ?? null,
            expiry: $data['expiry'] ?? null,
            brand: $data['brand'] ?? null,
            provider: $data['provider'] ?? null,
            mobileNumber: $data['mobile_number'] ?? null,
            completedAt: $data['completed_at'] ?? null,
        );
    }
}
