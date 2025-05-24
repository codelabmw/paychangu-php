<?php

namespace Codelabmw\Paychangu\Payments\Standard;

use Codelabmw\Paychangu\Client;
use Codelabmw\Paychangu\Exceptions\InvalidOrderException;
use Codelabmw\Paychangu\Exceptions\PaychanguException;
use Codelabmw\Paychangu\Payments\PaymentHandler;
use Codelabmw\Paychangu\Payment;
use Codelabmw\Paychangu\Order;

class StandardPayment extends PaymentHandler
{
    /**
     * Creates a new instance of the StandardPayment class.
     *
     * @param Client $client The client instance.
     */
    public function __construct(private readonly Client $client)
    {
        //
    }

    /**
     * Initiates a payment.
     *
     * @param StandardOrder $order The order to initiate.
     * @return Payment The payment entity.
     *
     * @throws InvalidOrderException If the order is not an instance of StandardOrder.
     */
    public function initiate(Order $order): PendingPayment
    {
        if (!$order instanceof StandardOrder) {
            throw new InvalidOrderException;
        }

        $response = $this->client->post('/payment', $order->toArray());
        $data = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() !== 200 || $data['status'] !== 'success') {
            throw new PaychanguException($data['message'], $response->getStatusCode());
        }

        return PendingPayment::fromArray($data['data']);
    }

    /**
     * Retrieves a payment.
     *
     * @param string $reference The reference of the payment to retrieve.
     * @return Payment The payment entity.
     */
    public function retrieve(string $reference): PendingPayment
    {
        $response = $this->client->get('/verify-paymen/' . $reference);
        $data = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() !== 200 || $data['status'] !== 'success') {
            throw new PaychanguException($data['message'], $response->getStatusCode());
        }

        return PendingPayment::fromArray($data);
    }

    /**
     * Verifies a payment.
     *
     * @param Payment|string $payment The payment to verify.
     * @return bool True if the payment is verified, false otherwise.
     */
    public function verify(Payment|string $payment): bool
    {
        //
    }
}