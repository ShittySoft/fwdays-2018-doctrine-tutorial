<?php

namespace Infrastructure\Authentication\Query;

use Authentication\Query\UserExists;
use Authentication\Repository\Users;

final class UserExistsThroughUglyHacks implements UserExists
{
    /**
     * @var Users
     */
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function __invoke(string $emailAddress) : bool
    {
        try {
            $this->users->get($emailAddress);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
