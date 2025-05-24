<?php

declare(strict_types=1);

use Codelabmw\Paychangu\Customer;

test('from array', function (): void {
    $customer = Customer::fromArray([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
    ]);

    expect($customer->firstName)->toBe('John');
    expect($customer->lastName)->toBe('Doe');
    expect($customer->email)->toBe('john@example.com');
});

test('to array', function (): void {
    $customer = new Customer(
        firstName: 'John',
        lastName: 'Doe',
        email: 'john@example.com',
    );

    expect($customer->toArray())->toBe([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
    ]);
});
