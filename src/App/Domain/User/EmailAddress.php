<?php

namespace Acme\App\Domain\User;

class EmailAddress
{
    /** @var string */
    private $address;

    public static function isValid(string $address): bool
    {
        return filter_var($address, FILTER_VALIDATE_EMAIL);
    }

    public function __construct(string $address)
    {
        if (!static::isValid($address)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid email address "%s" given', $address
            ));
        }

        $this->address = $address;
    }

    public function address(): string
    {
        return $this->address;
    }

    public function __toString(): string
    {
        return $this->address;
    }
}
