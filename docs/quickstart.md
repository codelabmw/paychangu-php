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

$order = new StandardOrder(
    amount: 10000, // Amount in the smallest currency unit (e.g., tambala for MWK)
    currency: Currency::MWK,
    reference: 'TX123456789',
    callbackUrl: 'https://yourapp.com/webhook',
    returnUrl: 'https://yourapp.com/return',
);

$payment = (new StandardPayment($client))->initiate($order);

// Redirect user to checkout URL
header('Location: ' . $payment->checkoutUrl);
exit;
```

---

## 3. Validate Webhook Requests

See [Webhooks](webhooks.md) for secure webhook handling.
