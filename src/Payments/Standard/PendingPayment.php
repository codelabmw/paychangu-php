<?php

namespace Codelabmw\Paychangu\Payments\Standard;

use Codelabmw\Paychangu\Enums\Currency;
use Codelabmw\Paychangu\Payment;

final class PendingPayment extends Payment
{
    public function __construct(
        public readonly string $event,
        public readonly string $checkoutUrl,
        public readonly string $reference,
        public readonly Currency $currency,
        public readonly int $amount,
        public readonly string $mode,
        public readonly string $status,
    ) {
        //
    }

    /**
     * Returns the transaction reference of the payment.
     *
     * @return string The transaction reference of the payment.
     */
    public function reference(): string
    {
        return $this->reference;
    }

    /**
     * Creates a new instance of the PendingPayment class from an array.
     * The format should match the response from the server.
     *
     * @param array $data The data to create the instance from.
     * @return self The created instance.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            event: $data['event'],
            checkoutUrl: $data['checkout_url'],
            reference: $data['data']['tx_ref'],
            currency: Currency::from($data['data']['currency']),
            amount: $data['data']['amount'],
            mode: $data['data']['mode'],
            status: $data['data']['status'],
        );
    }
}
