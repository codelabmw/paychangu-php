<?php

use Codelabmw\Paychangu\Enums\Currency;
use Codelabmw\Paychangu\Order;
use Codelabmw\Paychangu\Payments\Standard\StandardOrder;

test('standard order', function () {
    // Arrange
    $order = new StandardOrder(
        amount: 1000,
        currency: Currency::MWK,
        reference: '1234567890',
        callbackUrl: 'https://example.com/callback',
        returnUrl: 'https://example.com/return',
        title: 'Test Order',
        description: 'Test Order Description',
        customer: null,
        meta: null,
        uuid: null,
    );

    // Assert
    expect($order)->toBeInstanceOf(Order::class);
    expect($order->amount)->toBe(1000);
    expect($order->currency)->toBe(Currency::MWK);
    expect($order->reference)->toBe('1234567890');
    expect($order->callbackUrl)->toBe('https://example.com/callback');
    expect($order->returnUrl)->toBe('https://example.com/return');
    expect($order->title)->toBe('Test Order');
    expect($order->description)->toBe('Test Order Description');
    expect($order->customer)->toBe(null);
    expect($order->meta)->toBe(null);
    expect($order->uuid)->toBe(null);
});