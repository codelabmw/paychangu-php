<?php

declare(strict_types=1);

use Codelabmw\Paychangu\Enums\Currency;
use Codelabmw\Paychangu\Payments\Standard\PendingPayment;
use Codelabmw\Paychangu\Payments\Standard\ValueObjects\Authorization;
use Codelabmw\Paychangu\Payments\Standard\ValueObjects\Customization;
use Codelabmw\Paychangu\Payments\Standard\ValueObjects\Log;

test('creates a pending payment instance', function (): void {
    // Arrange & Act
    $payment = new PendingPayment(
        event: 'checkout.session:created',
        reference: '98993331-d4f4-4840-899f-7b46cacbb9f4',
        currency: Currency::MWK,
        amount: 1000,
        mode: 'sandbox',
        status: 'pending',
        checkoutUrl: 'https://test-checkout.paychangu.com/7887951180',
    );

    // Assert
    expect($payment)->toBeInstanceOf(PendingPayment::class);
    expect($payment->event)->toBe('checkout.session:created');
    expect($payment->reference())->toBe('98993331-d4f4-4840-899f-7b46cacbb9f4');
    expect($payment->reference)->toBe('98993331-d4f4-4840-899f-7b46cacbb9f4');
    expect($payment->currency)->toBe(Currency::MWK);
    expect($payment->amount)->toBe(1000);
    expect($payment->mode)->toBe('sandbox');
    expect($payment->status)->toBe('pending');
    expect($payment->checkoutUrl)->toBe('https://test-checkout.paychangu.com/7887951180');

    // Test optional properties are null
    expect($payment->eventType)->toBeNull();
    expect($payment->type)->toBeNull();
    expect($payment->customer)->toBeNull();
});

test('creates a pending payment from array with checkout session data', function (): void {
    // Arrange & Act
    $payment = PendingPayment::fromArray([
        'event' => 'checkout.session:created',
        'checkout_url' => 'https://test-checkout.paychangu.com/7887951180',
        'data' => [
            'tx_ref' => '98993331-d4f4-4840-899f-7b46cacbb9f4',
            'currency' => 'MWK',
            'amount' => 1000,
            'mode' => 'sandbox',
            'status' => 'pending',
        ],
    ]);

    // Assert
    expect($payment)->toBeInstanceOf(PendingPayment::class);
    expect($payment->event)->toBe('checkout.session:created');
    expect($payment->checkoutUrl)->toBe('https://test-checkout.paychangu.com/7887951180');
    expect($payment->reference)->toBe('98993331-d4f4-4840-899f-7b46cacbb9f4');
    expect($payment->currency)->toBe(Currency::MWK);
    expect($payment->amount)->toBe(1000);
    expect($payment->mode)->toBe('sandbox');
    expect($payment->status)->toBe('pending');
});

test('creates a completed payment from array with payment data', function (): void {
    // Arrange
    $paymentData = [
        'event_type' => 'checkout.payment',
        'tx_ref' => 'PA54231315',
        'mode' => 'live',
        'type' => 'API Payment (Checkout)',
        'status' => 'success',
        'number_of_attempts' => 1,
        'reference' => '26262633201',
        'currency' => 'MWK',
        'amount' => 1000,
        'charges' => 40,
        'customization' => [
            'title' => 'iPhone 10',
            'description' => 'Online order',
            'logo' => null,
        ],
        'meta' => ['order_id' => '12345'],
        'authorization' => [
            'channel' => 'Card',
            'card_number' => '230377******0408',
            'expiry' => '2035-12',
            'brand' => 'MASTERCARD',
            'provider' => null,
            'mobile_number' => null,
            'completed_at' => '2024-08-08T23:21:22.000000Z',
        ],
        'customer' => [
            'email' => 'yourmail@example.com',
            'first_name' => 'Mac',
            'last_name' => 'Phiri',
        ],
        'logs' => [
            [
                'type' => 'log',
                'message' => 'Attempted to pay with card',
                'created_at' => '2024-08-08T23:20:59.000000Z',
            ],
            [
                'type' => 'log',
                'message' => 'Processing and verification of card payment completed successfully.',
                'created_at' => '2024-08-08T23:21:22.000000Z',
            ],
        ],
        'created_at' => '2024-08-08T23:20:21.000000Z',
        'updated_at' => '2024-08-08T23:20:21.000000Z',
    ];

    // Act
    $payment = PendingPayment::fromArray($paymentData);

    // Assert
    expect($payment)->toBeInstanceOf(PendingPayment::class);

    // Basic fields
    expect($payment->event)->toBe('checkout.payment');
    expect($payment->eventType)->toBe('checkout.payment');
    expect($payment->reference)->toBe('PA54231315');
    expect($payment->currency)->toBe(Currency::MWK);
    expect($payment->amount)->toBe(1000);
    expect($payment->mode)->toBe('live');
    expect($payment->status)->toBe('success');

    // Additional fields
    expect($payment->type)->toBe('API Payment (Checkout)');
    expect($payment->numberOfAttempts)->toBe(1);
    expect($payment->paymentReference)->toBe('26262633201');
    expect($payment->charges)->toBe(40);

    // Customer
    expect($payment->customer)->not->toBeNull();
    expect($payment->customer->firstName)->toBe('Mac');
    expect($payment->customer->lastName)->toBe('Phiri');
    expect($payment->customer->email)->toBe('yourmail@example.com');

    // Authorization
    expect($payment->authorization)->toBeInstanceOf(Authorization::class);
    expect($payment->authorization->channel)->toBe('Card');
    expect($payment->authorization->cardNumber)->toBe('230377******0408');
    expect($payment->authorization->expiry)->toBe('2035-12');

    // Customization
    expect($payment->customization)->toBeInstanceOf(Customization::class);
    expect($payment->customization->title)->toBe('iPhone 10');

    // Logs
    expect($payment->logs)->toHaveCount(2);
    expect($payment->logs[0])->toBeInstanceOf(Log::class);
    expect($payment->logs[0]->message)->toContain('Attempted to pay');

    // Timestamps
    expect($payment->createdAt)->toBe('2024-08-08T23:20:21.000000Z');
    expect($payment->updatedAt)->toBe('2024-08-08T23:20:21.000000Z');
});

test('handles unknown format with fallback', function (): void {
    // Arrange & Act
    $payment = PendingPayment::fromArray([
        'tx_ref' => 'fallback-ref',
        'currency' => 'MWK',
        'amount' => 500,
        'mode' => 'test',
        'status' => 'unknown',
    ]);

    // Assert
    expect($payment)->toBeInstanceOf(PendingPayment::class);
    expect($payment->reference)->toBe('fallback-ref');
    expect($payment->currency)->toBe(Currency::MWK);
    expect($payment->amount)->toBe(500);
    expect($payment->mode)->toBe('test');
    expect($payment->status)->toBe('unknown');
});
