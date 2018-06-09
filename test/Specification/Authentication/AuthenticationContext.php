<?php

declare(strict_types=1);

namespace Test\Specification\Authentication;

use Authentication\Entity\User;
use Authentication\Notification\NotifyUserOfRegistration;
use Authentication\Query\UserExists;
use Authentication\Value\EmailAddress;
use Authentication\Value\PlainTextPassword;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

final class AuthenticationContext implements Context
{
    /** @var UserExists */
    private $existingUsers;

    /** @var PlainTextPassword */
    private $password;

    /** @var User */
    private $user;

    /**
     * @Given /^there are no registered users$/
     */
    public function thereAreNoRegisteredUsers()
    {
        $this->existingUsers = new class implements UserExists
        {
            public function __invoke(EmailAddress $emailAddress) : bool
            {
                return false;
            }
        };
    }

    /**
     * @When /^a user registers with the website$/
     */
    public function aUserRegistersWithTheWebsite()
    {
        $this->password = PlainTextPassword::fromString('bob\'s password');
        $this->user     = User::register(
            EmailAddress::fromString('bob@bob.bob'),
            $this->password,
            $this->existingUsers,
            new class implements NotifyUserOfRegistration
            {
                public function __invoke(EmailAddress $email) : void
                {
                    // nothing. Go away. Nothing to see here.
                }
            }
        );
    }

    /**
     * @Then /^the user can log into the website$/
     */
    public function theUserCanLogIntoTheWebsite()
    {
        if (! $this->user->logIn($this->password)) {
            throw new \UnexpectedValueException('Could not log in.');
        }
    }

    /**
     * @Given /^"([^"]*)" is a registered user$/
     */
    public function isARegisteredUser(string $emailAddress)
    {
        $this->existingUsers = new class ($emailAddress) implements UserExists
        {
            /**
             * @var string
             */
            private $emailAddress;

            public function __construct(string $emailAddress)
            {
                $this->emailAddress = $emailAddress;
            }

            public function __invoke(EmailAddress $emailAddress) : bool
            {
                return $emailAddress->toString() === $this->emailAddress;
            }
        };
    }

    /**
     * @Then /^I cannot register a user "([^"]*)"$/
     */
    public function iCannotRegisterAUser(string $emailAddress)
    {
        try {
            $this->user     = User::register(
                EmailAddress::fromString($emailAddress),
                PlainTextPassword::fromString('aaaaaaaaaaaaaaa'),
                $this->existingUsers,
                new class implements NotifyUserOfRegistration
                {
                    public function __invoke(EmailAddress $email) : void
                    {
                        // nothing. Go away. Nothing to see here.
                    }
                }
            );
        } catch (\Exception $e) {
            return;
        }

        throw new \Exception('No exception was raised');
    }
}
