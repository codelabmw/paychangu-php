<?php

declare(strict_types=1);

use Codelabmw\Paychangu\Client;
use Codelabmw\Paychangu\Support\Webhook;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

it('authenticates a request', function (): void {
    // Arrange
    $client = new Client('secret');
    $webhook = new Webhook($client);
    $body = json_encode([
        'event_type' => 'api.charge.payment',
        'currency' => 'MWK',
        'amount' => 1000,
        'charge' => '20',
        'mode' => 'test',
        'type' => 'Direct API Payment',
        'status' => 'success',
        'charge_id' => '5d676fg',
        'reference' => '71308131545',
        'authorization' => [
            'channel' => 'Mobile Bank Transfer',
            'card_details' => null,
            'bank_payment_details' => [
                'payer_bank_uuid' => '82310dd1-ec9b-4fe7-a32c-2f262ef08681',
                'payer_bank' => 'National Bank of Malawi',
                'payer_account_number' => '10010000',
                'payer_account_name' => 'Jonathan Manda',
            ],
            'mobile_money' => null,
            'completed_at' => '2025-01-15T19:53:18.000000Z',
        ],
        'created_at' => '2025-01-15T19:53:18.000000Z',
        'updated_at' => '2025-01-15T19:53:18.000000Z',
    ]);

    $signature = hash_hmac('sha256', $body, 'secret');

    $request = new Request('POST', 'https://example.com/webhook', ['Signature' => $signature], $body);

    // Act
    $verified = $webhook->authenticate($request);

    // Assert
    expect($verified)->toBeTrue();
});

it('gets the current request', function (): void {
    // Arrange
    $body = json_encode([
        'event_type' => 'api.charge.payment',
        'currency' => 'MWK',
        'amount' => 1000,
        'charge' => '20',
        'mode' => 'test',
        'type' => 'Direct API Payment',
        'status' => 'success',
        'charge_id' => '5d676fg',
        'reference' => '71308131545',
        'authorization' => [
            'channel' => 'Mobile Bank Transfer',
            'card_details' => null,
            'bank_payment_details' => [
                'payer_bank_uuid' => '82310dd1-ec9b-4fe7-a32c-2f262ef08681',
                'payer_bank' => 'National Bank of Malawi',
                'payer_account_number' => '10010000',
                'payer_account_name' => 'Jonathan Manda',
            ],
            'mobile_money' => null,
            'completed_at' => '2025-01-15T19:53:18.000000Z',
        ],
        'created_at' => '2025-01-15T19:53:18.000000Z',
        'updated_at' => '2025-01-15T19:53:18.000000Z',
    ]);

    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['REQUEST_URI'] = '/webhook';
    $_SERVER['HTTP_SIGNATURE'] = 'test';
    $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $_SERVER['HTTP_HOST'] = 'localhost';
    $_SERVER['SERVER_PORT'] = 80;

    $request = Webhook::getCurrentRequest();

    // Assert
    expect($request)->toBeInstanceOf(RequestInterface::class);
    expect($request->getMethod())->toBe('POST');
    expect($request->getHeaderLine('Signature'))->toBe('test');
});
