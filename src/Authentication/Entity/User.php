<?php

namespace Authentication\Entity;

use Authentication\Notification\NotifyUserOfRegistration;
use Authentication\Query\UserExists;
use Authentication\Value\EmailAddress;
use Authentication\Value\PasswordHash;
use Authentication\Value\PlainTextPassword;
use function filter_var;
use function strlen;
use function password_hash;

class User
{
    /** @var EmailAddress */
    private $email;

    /** @var PasswordHash */
    private $passwordHash;

    private function __construct()
    {
    }

    public static function register(
        EmailAddress $email,
        PlainTextPassword $password,
        UserExists $exists,
        NotifyUserOfRegistration $notifyUser
    ) : self {
        if ($exists($email)) {
            throw new \InvalidArgumentException(sprintf(
                'User "%s" is already registered',
                $email
            ));
        }

        $instance = new self();

        $instance->email        = $email;
        $instance->passwordHash = $password->toHash();

        $notifyUser($email);

        return $instance;
    }

    public function logIn(PlainTextPassword $password) : bool
    {
        return $password->verifyAgainstHash($this->passwordHash);
    }
}
