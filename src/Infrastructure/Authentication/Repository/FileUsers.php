<?php

namespace Infrastructure\Authentication\Repository;

use Authentication\Entity\User;
use Authentication\Repository\Users;
use Authentication\Value\EmailAddress;

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

    public function get(EmailAddress $emailAddress) : User
    {
        $existingUsers = unserialize(file_get_contents($this->filePath));
        $stringAddress = $emailAddress->toString();

        if (! array_key_exists($stringAddress, $existingUsers)) {
            throw new \OutOfBoundsException(sprintf(
                'User "%s" does not exist',
                $stringAddress
            ));
        }

        return $existingUsers[$stringAddress];
    }

    public function store(User $user) : void
    {
        $existingUsers = unserialize(file_get_contents($this->filePath));

        $emailReflection = new \ReflectionProperty(User::class, 'email');

        $emailReflection->setAccessible(true);

        $existingUsers[$emailReflection->getValue($user)->toString()] = $user;

        file_put_contents($this->filePath, serialize($existingUsers));
    }
}