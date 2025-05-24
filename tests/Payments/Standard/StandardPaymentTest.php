<?php

use Codelabmw\Paychangu\Client;
use Codelabmw\Paychangu\Enums\Currency;
use Codelabmw\Paychangu\Exceptions\PaychanguException;
use Codelabmw\Paychangu\Payments\Standard\PendingPayment;
use Codelabmw\Paychangu\Payments\Standard\StandardOrder;
use Codelabmw\Paychangu\Payments\Standard\StandardPayment;
use Codelabmw\Paychangu\Exceptions\InvalidOrderException;
use Codelabmw\Paychangu\Payments\PaymentHandler;
use Codelabmw\Paychangu\Order;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

beforeEach()->only();

it('extends base payment', function () {
    // Arrange
    $client = new Client('secret');
    $payment = new StandardPayment($client);

    // Assert
    expect($payment)->toBeInstanceOf(PaymentHandler::class);
});

it('throws exception if wrong order instance is given', function () {
    // Arrange
    $client = new Client('secret');
    $payment = new StandardPayment($client);
    $order = new class extends Order {

        public function toArray(): array
        {
            return [];
        }
    };

    // Act
    $payment->initiate($order);
})->throws(InvalidOrderException::class);

it('initiates a payment', function () {
    // Arrange
    $mock = new MockHandler([
        new Response(status: 200, body: json_encode(
            [
                "message" => "Hosted payment session generated successfully.",
                "status" => "success",
                "data" => [
                    "event" => "checkout.session:created",
                    "checkout_url" => "https://test-checkout.paychangu.com/7887951180",
                    "data" => [
                        "tx_ref" => "1234567890",
                        "currency" => "MWK",
                        "amount" => 1000,
                        "mode" => "sandbox",
                        "status" => "pending"
                    ]
                ]
            ]
        )),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new GuzzleClient(['handler' => $handlerStack]);

    $client = new Client('secret', httpClient: $httpClient);
    $payment = new StandardPayment($client);

    $order = new StandardOrder(
        amount: 1000,
        currency: Currency::MWK,
        reference: '1234567890',
        callbackUrl: 'https://example.com/callback',
        returnUrl: 'https://example.com/return',
    );

    // Act
    $payment = $payment->initiate($order);

    // Assert
    expect($payment)->toBeInstanceOf(PendingPayment::class);
    expect($payment)->toHaveProperty('event', 'checkout.session:created');
    expect($payment)->toHaveProperty('reference', '1234567890');
    expect($payment)->toHaveProperty('status', 'pending');
    expect($payment)->toHaveProperty('checkoutUrl', 'https://test-checkout.paychangu.com/7887951180');
    expect($payment)->toHaveProperty('mode', 'sandbox');
    expect($payment)->toHaveProperty('currency', Currency::MWK);
    expect($payment)->toHaveProperty('amount', 1000);
});

it('can throw paychangu exception when initiate fails', function () {
    // Arrange
    $mock = new MockHandler([
        new Response(status: 400, body: json_encode(
            [
                "message" => "Invalid order.",
                "status" => "error",
                "data" => [
                    "event" => "checkout.session:created",
                    "checkout_url" => "https://test-checkout.paychangu.com/7887951180",
                    "data" => [
                        "tx_ref" => "1234567890",
                        "currency" => "MWK",
                        "amount" => 1000,
                        "mode" => "sandbox",
                        "status" => "pending"
                    ]
                ]
            ]
        )),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new GuzzleClient(['handler' => $handlerStack]);

    $client = new Client('secret', httpClient: $httpClient);
    $payment = new StandardPayment($client);
    $order = new StandardOrder(
        amount: 1000,
        currency: Currency::MWK,
        reference: '1234567890',
        callbackUrl: 'https://example.com/callback',
        returnUrl: 'https://example.com/return',
    );

    // Act
    $payment->initiate($order);
})->throws(PaychanguException::class);

it('gets a payment', function () {
    // Arrange
    $mock = new MockHandler([
        new Response(status: 200, body: json_encode(
            [
                "status" => "success",
                "message" => "Payment details retrieved successfully.",
                "data" => [
                    "event_type" => "checkout.payment",
                    "tx_ref" => "PA54231315",
                    "mode" => "live",
                    "type" => "API Payment (Checkout)",
                    "status" => "success",
                    "number_of_attempts" => 1,
                    "reference" => "26262633201",
                    "currency" => "MWK",
                    "amount" => 1000,
                    "charges" => 40,
                    "customization" => [
                        "title" => "iPhone 10",
                        "description" => "Online order",
                        "logo" => null
                    ],
                    "meta" => null,
                    "authorization" => [
                        "channel" => "Card",
                        "card_number" => "230377******0408",
                        "expiry" => "2035-12",
                        "brand" => "MASTERCARD",
                        "provider" => null,
                        "mobile_number" => null,
                        "completed_at" => "2024-08-08T23:21:22.000000Z"
                    ],
                    "customer" => [
                        "email" => "yourmail@example.com",
                        "first_name" => "Mac",
                        "last_name" => "Phiri"
                    ],
                    "logs" => [
                        [
                            "type" => "log",
                            "message" => "Attempted to pay with card",
                            "created_at" => "2024-08-08T23:20:59.000000Z"
                        ],
                        [
                            "type" => "log",
                            "message" => "Processing and verification of card payment completed successfully.",
                            "created_at" => "2024-08-08T23:21:22.000000Z"
                        ]
                    ],
                    "created_at" => "2024-08-08T23:20:21.000000Z",
                    "updated_at" => "2024-08-08T23:20:21.000000Z"
                ]
            ]
        )),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new GuzzleClient(['handler' => $handlerStack]);

    $client = new Client('secret', httpClient: $httpClient);
    $payment = new StandardPayment($client);

    $payment = $payment->retrieve('PA54231315');

    // Assert
    expect($payment)->toBeInstanceOf(PendingPayment::class);
});

it('can throw paychangu exception when retrieve fails', function () {
    // Arrange
    $mock = new MockHandler([
        new Response(status: 400, body: json_encode(
            [
                "status" => "error",
                "message" => "Invalid transaction reference.",
            ]
        )),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new GuzzleClient(['handler' => $handlerStack]);

    $client = new Client('secret', httpClient: $httpClient);
    $payment = new StandardPayment($client);

    // Act
    $payment->retrieve('PA54231315');
})->throws(PaychanguException::class);

it('verifies a payment')->todo();