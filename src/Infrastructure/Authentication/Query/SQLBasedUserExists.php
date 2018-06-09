<?php

namespace Infrastructure\Authentication\Query;

use Authentication\Query\UserExists;
use Authentication\Value\EmailAddress;
use Doctrine\DBAL\Connection;

final class DQLBasedUserExists implements UserExists
{
    /**  @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(EmailAddress $emailAddress) : bool
    {
        return (bool) $this
            ->connection
            ->fetchColumn(
                <<<'SQL'
SELECT COUNT(*) FROM users e WHERE e.email = :email
SQL
                ,
                ['email' => $emailAddress],
                0,
                ['email' => EmailAddress::class]
            );
    }
}
