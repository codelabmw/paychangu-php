# Generating References

The SDK provides a robust utility for generating unique, readable references for payments, orders, or any resource that needs a unique identifier.

---

## Usage

```php
use Codelabmw\Paychangu\Support\Reference;

// Generate a reference with default settings (prefix 'REF', length 18)
$reference = new Reference();
echo (string) $reference; // e.g., REF-ABC123-4567-89AB

// Customize prefix, length, and formatting
$customRef = new Reference(prefix: 'ORDER', length: 12, prefixSeparator: '_', pretty: false);
echo (string) $customRef; // e.g., ORDER_ABCD1234EFGH
```

---

**Tip:** Use this when creating new orders or payments to ensure unique, traceable references.
