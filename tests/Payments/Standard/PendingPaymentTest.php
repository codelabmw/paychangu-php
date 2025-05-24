<?php

use Codelabmw\Paychangu\Enums\Currency;
use Codelabmw\Paychangu\Payments\Standard\PendingPayment;

// Payment object from server
//
// {
//     "message": "Hosted payment session generated successfully.",
//     "status": "success",
//     "data": {
//       "event": "checkout.session:created",
//       "checkout_url": "https://test-checkout.paychangu.com/7887951180",
//       "data": {
//         "tx_ref": "98993331-d4f4-4840-899f-7b46cacbb9f4",
//         "currency": "MWK",
//         "amount": 1000,
//         "mode": "sandbox",
//         "status": "pending"
//       }
//     }
//   }

test('pending payment', function () {
    // Arrange
    $payment = new PendingPayment(
        event: 'checkout.session:created',
        checkoutUrl: 'https://test-checkout.paychangu.com/7887951180',
        reference: '98993331-d4f4-4840-899f-7b46cacbb9f4',
        currency: Currency::MWK,
        amount: 1000,
        mode: 'sandbox',
        status: 'pending',
    );

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

test('from array', function () {
    // Arrange
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