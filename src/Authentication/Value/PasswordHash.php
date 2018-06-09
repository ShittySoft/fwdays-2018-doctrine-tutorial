<?php

namespace Authentication\Value;

final class PasswordHash
{
    /** @var string */
    private $hash;

    private function __construct()
    {
    }

    public static function fromString(string $hash) : self
    {
        if (0 !== strpos($hash, '$')) {
            throw new \InvalidArgumentException(sprintf(
                'Password hash "%s" is invalid',
                $hash
            ));
        }

        $instance = new self();

        $instance->hash = $hash;

        return $instance;
    }

    public function toString() : string
    {
        return $this->hash;
    }
}
