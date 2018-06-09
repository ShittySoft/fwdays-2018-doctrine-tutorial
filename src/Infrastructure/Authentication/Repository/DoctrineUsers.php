<?php

namespace Infrastructure\Authentication\Repository;

use Authentication\Entity\User;
use Authentication\Repository\Users;
use Authentication\Value\EmailAddress;
use Doctrine\Common\Persistence\ObjectManager;

class DoctrineUsers implements Users
{
    /** @var ObjectManager */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function get(EmailAddress $emailAddress) : User
    {
        $user = $this->objectManager->find(User::class, $emailAddress);

        if (! $user instanceof User) {
            throw new \OutOfBoundsException(sprintf(
                'User "%s" does not exist',
                $emailAddress->toString()
            ));
        }

        return $user;
    }

    public function store(User $user) : void
    {
        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }
}