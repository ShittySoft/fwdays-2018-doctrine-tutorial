<?php

namespace Infrastructure\Authentication\Repository;

use Authentication\Entity\User;
use Authentication\Repository\Users;

class FileUsers implements Users
{
    /** @var string */
    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;

        if (! file_exists($this->filePath)) {
            file_put_contents($this->filePath, serialize([]));
        }
    }

    public function get(string $emailAddress) : User
    {
        $existingUsers = unserialize(file_get_contents($this->filePath));

        if (! array_key_exists($emailAddress, $existingUsers)) {
            throw new \OutOfBoundsException(sprintf(
                'User "%s" does not exist',
                $emailAddress
            ));
        }

        return $existingUsers[$emailAddress];
    }

    public function store(User $user) : void
    {
        $existingUsers = unserialize(file_get_contents($this->filePath));

        $emailReflection = new \ReflectionProperty(User::class, 'email');

        $emailReflection->setAccessible(true);

        $existingUsers[$emailReflection->getValue($user)] = $user;

        file_put_contents($this->filePath, serialize($existingUsers));
    }
}