# Standard Payments

The SDK currently supports **Standard Payments**. This section explains how to create and process a payment order.

---

## Creating a Standard Order

```php
use Codelabmw\Paychangu\Payments\Standard\StandardOrder;
use Codelabmw\Paychangu\Enums\Currency;

use Codelabmw\Paychangu\Support\Reference;

$reference = new Reference();

$order = new StandardOrder(
    amount: 10000, // Amount in the smallest currency unit
    currency: Currency::MWK,
    reference: (string) $reference, // Use generated reference
    callbackUrl: 'https://yourapp.com/webhook',
    returnUrl: 'https://yourapp.com/return',
    title: 'Order Title',
    description: 'Order Description',
    customer: null, // Optionally pass a Customer object
    meta: ['custom_key' => 'custom_value'],
);
```

---

## Initiating a Payment

```php
use Codelabmw\Paychangu\Client;
use Codelabmw\Paychangu\Payments\Standard\StandardPayment;

$client = new Client('YOUR_SECRET_KEY');
$paymentHandler = new StandardPayment($client);
$pendingPayment = $paymentHandler->initiate($order);

// $pendingPayment is an instance of PendingPayment
```

---

## Handling the Payment Response

```php
if ($pendingPayment->checkoutUrl) {
    // Redirect user to Paychangu checkout page
    header('Location: ' . $pendingPayment->checkoutUrl);
    exit;
} else {
    // Handle error or completed payment
}
```

---

## Retrieving a Payment

```php
$payment = $paymentHandler->retrieve('TX123456789');
```
