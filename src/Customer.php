<?php

namespace Codelabmw\Paychangu;

class Customer
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName = null,
        public readonly string $email = null,
    ) {
        //
    }

    /**
     * Returns the customer as an array.
     *
     * @return array<string, mixed> The customer as an array.
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
        ];
    }
}
