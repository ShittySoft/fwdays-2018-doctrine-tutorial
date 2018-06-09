<?php

namespace Infrastructure\Authentication\Notification;

use Authentication\Notification\NotifyUserOfRegistration;
use Authentication\Value\EmailAddress;

final class NotifyUserOfRegistrationInTerminal implements NotifyUserOfRegistration
{
    public function __invoke(EmailAddress $email) : void
    {
        error_log(sprintf(
            'User "%s" was registered',
            $email->toString()
        ));
    }
}