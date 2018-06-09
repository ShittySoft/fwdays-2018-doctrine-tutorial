<?php

namespace Authentication\Value;

final class PlainTextPassword
{
    /** @var string */
    private $password;

    private function __construct()
    {
    }

    public static function fromString(string $password) : self
    {
        $instance = new self();

        if (strlen($password) < 8) {
            throw new \InvalidArgumentException(sprintf(
                'The given password is too short',
                $password
            ));
        }

        $instance->password = $password;

        return $instance;
    }

    public function toHash() : PasswordHash
    {
        return PasswordHash::fromString(password_hash($this->password, \PASSWORD_DEFAULT));
    }

    public function verifyAgainstHash(PasswordHash $passwordHash) : bool
    {
        return password_verify($this->password, $passwordHash->toString());
    }
}
