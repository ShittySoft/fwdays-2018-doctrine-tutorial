<?php

namespace Authentication\Value;

final class EmailAddress
{
    /** @var string */
    private $emailAddress;

    private function __construct()
    {
    }

    public static function fromString(string $emailAddress) : self
    {
        $instance = new self();

        if (! filter_var($emailAddress, \FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf(
                'The given email address "%s" is invalid',
                $emailAddress
            ));
        }

        $instance->emailAddress = $emailAddress;

        return $instance;
    }

    public function toString() : string
    {
        return $this->emailAddress;
    }

    public function __toString() : string
    {
        return $this->emailAddress;
    }
}
