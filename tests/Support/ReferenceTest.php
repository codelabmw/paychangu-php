<?php

declare(strict_types=1);

use Codelabmw\Paychangu\Support\Reference;

test('reference', function (): void {
    // Act
    $reference = (string) new Reference(prefix: 'TEST', length: 10);

    // Assert
    expect($reference)->toHaveLength(10);
    expect($reference)->toMatch('/^test-[a-z]{5}$/');
});
