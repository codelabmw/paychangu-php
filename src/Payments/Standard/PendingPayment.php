<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Payments\Standard;

use Codelabmw\Paychangu\Customer;
use Codelabmw\Paychangu\Enums\Currency;
use Codelabmw\Paychangu\Payment;
use Codelabmw\Paychangu\Payments\Standard\ValueObjects\Authorization;
use Codelabmw\Paychangu\Payments\Standard\ValueObjects\Customization;
use Codelabmw\Paychangu\Payments\Standard\ValueObjects\Log;

final class PendingPayment extends Payment
{
    /**
     * @param  string  $event  The event type (e.g., 'checkout.session:created' or 'checkout.payment')
     * @param  string  $reference  The transaction reference
     * @param  Currency  $currency  The currency of the payment
     * @param  int  $amount  The amount in the smallest currency unit (e.g., tambala for MWK)
     * @param  string  $mode  The mode of the payment (e.g., 'sandbox' or 'live')
     * @param  string  $status  The status of the payment (e.g., 'pending' or 'success')
     * @param  string|null  $checkoutUrl  The URL for the checkout page (for pending payments)
     * @param  string|null  $eventType  The type of the event (for completed payments)
     * @param  string|null  $type  The type of payment (for completed payments)
     * @param  int|null  $numberOfAttempts  Number of payment attempts (for completed payments)
     * @param  string|null  $paymentReference  The payment reference (for completed payments)
     * @param  int|null  $charges  The charges applied to the payment (for completed payments)
     * @param  array<array<string, string|null>>|null  $meta  Additional metadata (for completed payments)
     * @param  Authorization|null  $authorization  Authorization details (for completed payments)
     * @param  Customer|null  $customer  Customer details (for completed payments)
     * @param  Customization|null  $customization  Customization details (for completed payments)
     * @param  array<Log>|null  $logs  Array of Log objects (for completed payments)
     * @param  string|null  $createdAt  When the payment was created (for completed payments)
     * @param  string|null  $updatedAt  When the payment was last updated (for completed payments)
     */
    public function __construct(
        public readonly string $event,
        public readonly string $reference,
        public readonly Currency $currency,
        public readonly int $amount,
        public readonly string $mode,
        public readonly string $status,
        public readonly ?string $checkoutUrl = null,
        public readonly ?string $eventType = null,
        public readonly ?string $type = null,
        public readonly ?int $numberOfAttempts = null,
        public readonly ?string $paymentReference = null,
        public readonly ?int $charges = null,
        public readonly ?array $meta = null,
        public readonly ?Authorization $authorization = null,
        public readonly ?Customer $customer = null,
        public readonly ?Customization $customization = null,
        public readonly ?array $logs = null,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
    ) {
        //
    }

    /**
     * Creates a new instance of the PendingPayment class from an array.
     * The format should match the response from the server.
     *
     * @param  array<string, mixed>  $data  The data to create the instance from.
     * @return self The created instance.
     */
    public static function fromArray(array $data): self
    {
        // Handle Object A format (checkout.session:created)
        if (isset($data['event']) && str_contains((string) $data['event'], 'checkout.session:created')) {
            return new self(
                event: $data['event'],
                checkoutUrl: $data['checkout_url'] ?? null,
                reference: $data['data']['tx_ref'] ?? '',
                currency: Currency::from($data['data']['currency'] ?? 'MWK'),
                amount: (int) ($data['data']['amount'] ?? 0),
                mode: $data['data']['mode'] ?? 'sandbox',
                status: $data['data']['status'] ?? 'pending',
            );
        }

        // Handle Object B format (completed payment)
        if (isset($data['event_type']) && $data['event_type'] === 'checkout.payment') {
            return new self(
                event: 'checkout.payment',
                reference: $data['tx_ref'] ?? '',
                currency: Currency::from($data['currency'] ?? 'MWK'),
                amount: (int) ($data['amount'] ?? 0),
                mode: $data['mode'] ?? 'live',
                status: $data['status'] ?? 'pending',
                eventType: $data['event_type'] ?? null,
                type: $data['type'] ?? null,
                numberOfAttempts: isset($data['number_of_attempts']) ? (int) $data['number_of_attempts'] : null,
                paymentReference: $data['reference'] ?? null,
                charges: isset($data['charges']) ? (int) $data['charges'] : null,
                meta: $data['meta'] ?? null,
                authorization: empty($data['authorization']) ? null : Authorization::fromArray($data['authorization']),
                customer: empty($data['customer']) ? null : new Customer(
                    firstName: $data['customer']['first_name'] ?? '',
                    lastName: $data['customer']['last_name'] ?? null,
                    email: $data['customer']['email'] ?? null
                ),
                customization: empty($data['customization']) ? null : Customization::fromArray($data['customization']),
                logs: empty($data['logs']) ? null : array_map(fn ($log): Log => Log::fromArray($log), $data['logs']),
                createdAt: $data['created_at'] ?? null,
                updatedAt: $data['updated_at'] ?? null,
            );
        }

        // Fallback for unknown format
        return new self(
            event: $data['event'] ?? 'unknown',
            reference: $data['tx_ref'] ?? ($data['data']['tx_ref'] ?? ''),
            currency: Currency::from($data['currency'] ?? ($data['data']['currency'] ?? 'MWK')),
            amount: (int) ($data['amount'] ?? ($data['data']['amount'] ?? 0)),
            mode: $data['mode'] ?? ($data['data']['mode'] ?? 'sandbox'),
            status: $data['status'] ?? ($data['data']['status'] ?? 'unknown'),
            checkoutUrl: $data['checkout_url'] ?? null,
        );
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
}
