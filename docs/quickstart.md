# Quick Start

This guide will help you get up and running with the Paychangu PHP SDK in minutes.

---

## 1. Create a Paychangu Client

```php
use Codelabmw\Paychangu\Client;

$client = new Client('YOUR_SECRET_KEY');
```

---

## 2. Initiate a Standard Payment

```php
use Codelabmw\Paychangu\Payments\Standard\StandardOrder;
use Codelabmw\Paychangu\Payments\Standard\StandardPayment;
use Codelabmw\Paychangu\Enums\Currency;

use Codelabmw\Paychangu\Support\Reference;

$reference = new Reference();

$order = new StandardOrder(
    amount: 10_000,
    currency: Currency::MWK,
    reference: (string) $reference,
    callbackUrl: 'https://yourapp.com/webhook',
    returnUrl: 'https://yourapp.com/return',
);

$payment = (new StandardPayment($client))->initiate($order);

// Redirect user to checkout URL
header('Location: ' . $payment->checkoutUrl);
exit;
```

> More information on [Reference](reference.md).

---

## 3. Retrieve a Payment

You can retrieve a payment by its reference at any time:

```php
$paymentHandler = new StandardPayment($client);
$retrievedPayment = $paymentHandler->retrieve((string) $reference);
```

---

## 4. Verify a Payment

To check if a payment was successful, use the `verify` method, it accepts either a string or a `Payment` object returned by `initiate` and or `retrieve` methods:

```php
// Verify by transaction reference
$isVerified = $paymentHandler->verify($reference); // Returns true if successful

// Verify by payment object
$isVerified = $paymentHandler->verify($payment); // Returns true if successful
```

> **NOTE** The `verify` method calls `retrieve` method to get the payment object in both cases.

---

## 5. Validate Webhook Requests

See [Webhooks](webhooks.md) for secure webhook handling.
