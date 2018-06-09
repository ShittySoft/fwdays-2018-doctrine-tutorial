<?php

namespace Authentication\Entity;

use Authentication\Notification\NotifyUserOfRegistration;
use Authentication\Query\UserExists;
use function filter_var;
use function strlen;
use function password_hash;

class User
{
    /** @var string */
    private $email;

    /** @var string */
    private $passwordHash;

    private function __construct()
    {
    }

    public static function register(
        string $email,
        string $password,
        UserExists $exists,
        NotifyUserOfRegistration $notifyUser
    ) : self {
        if (! filter_var($email, \FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid email address "%s" provided',
                $email
            ));
        }

        if ($exists($email)) {
            throw new \InvalidArgumentException(sprintf(
                'User "%s" is already registered',
                $email
            ));
        }

        if (strlen($password) < 8) {
            throw new \InvalidArgumentException('The provided password is too short');
        }

        $instance = new self();

        $instance->email        = $email;
        $instance->passwordHash = password_hash($password, \PASSWORD_DEFAULT);

        $notifyUser($email);

        return $instance;
    }

    public function logIn(string $password) : bool
    {
        return password_verify($password, $this->passwordHash);
    }
}
