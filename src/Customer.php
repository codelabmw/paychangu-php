<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu;

final readonly class Customer
{
    /**
     * Creates a new instance of the Customer class.
     *
     * @param  string|null  $firstName  The first name of the customer.
     * @param  string|null  $lastName  The last name of the customer.
     * @param  string|null  $email  The email address of the customer.
     */
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
    ) {
        //
    }

    /**
     * Creates a new instance of the Customer class from an array.
     *
     * @param  array<string, string|null>  $data  The data to create the customer from.
     * @return self The created customer.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            email: $data['email'] ?? null,
        );
    }

    /**
     * Returns the customer as an array.
     *
     * @return array<string, string|null> The customer as an array.
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
