<?php

declare(strict_types=1);

use Codelabmw\Paychangu\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

it('sends a get request', function (): void {
    // Arrange
    $mock = new MockHandler([
        new Response(200, ['X-Foo' => 'Bar'], json_encode(['foo' => 'bar'])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new GuzzleClient(['handler' => $handlerStack]);
    $client = new Client('secret', httpClient: $httpClient);

    // Act
    $response = $client->get('/greetings');
    $request = $mock->getLastRequest();

    // Assert
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody()->getContents())->toBe(json_encode(['foo' => 'bar']));
    expect($request->getHeaderLine('Authorization'))->toBe('Bearer secret');
    expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($request->getHeaderLine('Accept'))->toBe('application/json');
    expect($request->getMethod())->toBe('GET');
    expect($request->getUri()->getScheme())->toBe('https');
    expect($request->getUri()->getHost())->toBe('api.paychangu.com');
    expect($request->getUri()->getPath())->toBe('/greetings');
});

it('sends a post request', function (): void {
    // Arrange
    $mock = new MockHandler([
        new Response(200, ['X-Foo' => 'Bar'], json_encode(['foo' => 'bar'])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new GuzzleClient(['handler' => $handlerStack]);
    $client = new Client('secret', httpClient: $httpClient);

    // Act
    $response = $client->post('/greetings', ['foo' => 'bar']);
    $request = $mock->getLastRequest();

    // Assert
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody()->getContents())->toBe(json_encode(['foo' => 'bar']));
    expect($request->getHeaderLine('Authorization'))->toBe('Bearer secret');
    expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($request->getHeaderLine('Accept'))->toBe('application/json');
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getScheme())->toBe('https');
    expect($request->getUri()->getHost())->toBe('api.paychangu.com');
    expect($request->getUri()->getPath())->toBe('/greetings');
});

it('gets current secret', function (): void {
    // Arrange
    $client = new Client('secret');

    // Act
    $secret = $client->secret();

    // Assert
    expect($secret)->toBe('secret');
});
