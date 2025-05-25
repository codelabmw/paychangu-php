<?php

declare(strict_types=1);

use Codelabmw\Paychangu\Support\Reference;

it('generates a pretty reference with correct chunking', function () {
    $ref = new Reference('INV', 14, '-', true);
    $string = (string)$ref;
    expect($string)->toMatch('/^INV-[A-Z]{6}-[A-Z]{4}-[A-Z]{4}$/');
});

it('generates a pretty reference with custom prefix and separator', function () {
    $ref = new Reference('PAY', 18, ':', true);
    $string = (string)$ref;
    expect($string)->toMatch('/^PAY:[A-Z]{6}(-[A-Z]{4}){3}$/');
});

it('generates a non-pretty reference', function () {
    $ref = new Reference('REF', 10, '-', false);
    $string = (string)$ref;
    expect($string)->toMatch('/^REF-[A-Z]{10}$/');
});

it('throws if length is less than 6', function () {
    new Reference('ERR', 5);
})->throws(InvalidArgumentException::class);
