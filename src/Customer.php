<?php

namespace Codelabmw\Paychangu;

class Customer
{
    /**
     * Creates a new instance of the Customer class.
     *
     * @param string|null $firstName The first name of the customer.
     * @param string|null $lastName The last name of the customer.
     * @param string|null $email The email address of the customer.
     */
    public function __construct(
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $email = null,
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

    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            email: $data['email'] ?? null,
        );
    }
}
