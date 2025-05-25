<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Payments\Standard;

use Codelabmw\Paychangu\Customer;
use Codelabmw\Paychangu\Enums\Currency;
use Codelabmw\Paychangu\Order;

final class StandardOrder extends Order
{
    /**
     * Creates a new instance of the StandardOrder class.
     *
     * @param  int  $amount  The amount of the order.
     * @param  Currency  $currency  The currency of the order.
     * @param  string  $reference  The reference of the order.
     * @param  string  $callbackUrl  The callback URL of the order.
     * @param  string  $returnUrl  The return URL of the order.
     * @param  string|null  $title  The title of the order.
     * @param  string|null  $description  The description of the order.
     * @param  Customer|null  $customer  The customer of the order.
     * @param  array<string, mixed>|null  $meta  The meta of the order.
     * @param  string|null  $uuid  The UUID of the order.
     */
    public function __construct(
        public readonly int $amount,
        public readonly Currency $currency,
        public readonly string $reference,
        public readonly string $callbackUrl,
        public readonly string $returnUrl,
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?Customer $customer = null,
        public readonly ?array $meta = null,
        public readonly ?string $uuid = null,
    ) {
        //
    }

    /**
     * Returns the order as an array.
     *
     * @return array<string, mixed> The order as an array.
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency->value,
            'reference' => $this->reference,
            'callback_url' => $this->callbackUrl,
            'return_url' => $this->returnUrl,
            'title' => $this->title ?? null,
            'description' => $this->description ?? null,
            'customer' => $this->customer?->toArray() ?? null,
            'meta' => $this->meta ?? null,
            'uuid' => $this->uuid ?? null,
        ];
    }
}
