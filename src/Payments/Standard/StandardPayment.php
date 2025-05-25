<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Payments\Standard;

use Codelabmw\Paychangu\Client;
use Codelabmw\Paychangu\Exceptions\InvalidOrderException;
use Codelabmw\Paychangu\Exceptions\PaychanguException;
use Codelabmw\Paychangu\Order;
use Codelabmw\Paychangu\Payment;
use Codelabmw\Paychangu\Payments\PaymentHandler;

final class StandardPayment extends PaymentHandler
{
    /**
     * Creates a new instance of the StandardPayment class.
     *
     * @param  Client  $client  The client instance.
     */
    public function __construct(private readonly Client $client)
    {
        //
    }

    /**
     * Initiates a payment.
     *
     * @param  Order  $order  The order to initiate.
     * @return PendingPayment The payment entity.
     *
     * @throws InvalidOrderException If the order is not an instance of StandardOrder.
     */
    public function initiate(Order $order): PendingPayment
    {
        if (! $order instanceof StandardOrder) {
            throw new InvalidOrderException(
                'Invalid order instance given. Expected StandardOrder, got '.$order::class
            );
        }

        $response = $this->client->post('/payment', $order->toArray());
        $data = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() === 400) {
            // @phpstan-ignore-next-line
            throw new PaychanguException(json_encode($data['message']));
        }

        // @phpstan-ignore-next-line
        return PendingPayment::fromArray($data['data']);
    }

    /**
     * Retrieves a payment.
     *
     * @param  string  $reference  The reference of the payment to retrieve.
     * @return Payment The payment entity.
     *
     * @throws PaychanguException If the payment is not found.
     *
     * @phpstan-ignore-next-line
     */
    public function retrieve(string $reference): PendingPayment
    {
        $response = $this->client->get('/verify-payment/'.$reference);
        $data = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() === 400) {
            // @phpstan-ignore-next-line
            throw new PaychanguException(json_encode($data['message']));
        }

        // @phpstan-ignore-next-line
        return PendingPayment::fromArray($data['data']);
    }

    /**
     * Verifies a payment.
     *
     * @param  Payment|string  $payment  The payment to verify.
     * @return bool True if the payment is verified, false otherwise.
     *
     * @throws PaychanguException If the payment is not found.
     */
    public function verify(Payment|string $payment): bool
    {
        $reference = $payment instanceof Payment ? $payment->reference() : $payment;

        /** @var PendingPayment $payment */
        $payment = $this->retrieve($reference);

        return $payment->status === 'success';
    }
}
