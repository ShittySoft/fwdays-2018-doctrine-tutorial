<?php

namespace Infrastructure\Authentication\Query;

use Authentication\Entity\User;
use Authentication\Query\UserExists;
use Authentication\Value\EmailAddress;
use Doctrine\ORM\EntityManager;

final class DQLBasedUserExists implements UserExists
{
    /** @var EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(EmailAddress $emailAddress) : bool
    {
        return (bool) $this
            ->entityManager
            ->createQuery(
                'SELECT COUNT(e) FROM ' . User::class . ' e '
                . 'WHERE e.email = :email'
            )
            ->setParameter('email', $emailAddress, EmailAddress::class)
            ->getSingleScalarResult();
    }
}
