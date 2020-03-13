<?php

namespace Acme\App\Domain\User;

class Password
{
    /** @var string */
    private $hash;

    public static function generate(string $plain): self
    {
        return new static(password_hash($plain, PASSWORD_BCRYPT));
    }

    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    public function verify(string $string): bool
    {
        return password_verify($string, $this->hash);
    }

    public function hash(): string
    {
        return $this->hash;
    }
}
