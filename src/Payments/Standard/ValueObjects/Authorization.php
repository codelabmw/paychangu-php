<?php

namespace Codelabmw\Paychangu\Payments\Standard\ValueObjects;

final class Authorization
{
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
