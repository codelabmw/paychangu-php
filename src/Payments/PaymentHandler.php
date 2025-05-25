<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Payments;

use Codelabmw\Paychangu\Order;
use Codelabmw\Paychangu\Payment;

/**
 * @internal
 */
abstract class PaymentHandler
{
    /**
     * Initiates a payment.
     *
     * @param  Order  $order  The order to initiate.
     * @return Payment The payment entity.
     */
    abstract public function initiate(Order $order): Payment;

    /**
     * Retrieves a payment.
     *
     * @param  string  $reference  The reference of the payment to retrieve.
     * @return Payment The payment entity.
     */
    abstract public function retrieve(string $reference): Payment;

    /**
     * Verifies a payment.
     *
     * @param  Payment|string  $payment  The payment to verify.
     * @return bool True if the payment is verified, false otherwise.
     */
    abstract public function verify(Payment|string $payment): bool;
}
