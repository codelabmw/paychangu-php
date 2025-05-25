# Webhooks

Paychangu uses webhooks to notify your application about payment events. This SDK provides secure validation for incoming webhook requests.

---

## Validating a Webhook Request

```php
use Codelabmw\Paychangu\Support\Webhook;
use Codelabmw\Paychangu\Client;

$client = new Client('YOUR_SECRET_KEY');
$request = Webhook::getCurrentRequest(); // Or any PSR-7 RequestInterface instance
$webhook = new Webhook($client);

if ($webhook->authenticate($request)) {
    // Valid Paychangu webhook
    $payload = json_decode($request->getBody()->getContents(), true);
    // Handle the event
} else {
    http_response_code(401);
    exit('Invalid signature');
}
```

---

## Tips
- Always verify the webhook signature before processing.
- Respond with HTTP 2xx status to acknowledge receipt.
- See Paychangu [API docs](https://developer.paychangu.com/reference/introduction-1) for full event types.
