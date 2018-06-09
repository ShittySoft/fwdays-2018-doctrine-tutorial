<?php

namespace Infrastructure\Authentication\Notification;

use Authentication\Notification\NotifyUserOfRegistration;

final class NotifyUserOfRegistrationInTerminal implements NotifyUserOfRegistration
{
    public function __invoke(string $email) : void
    {
        error_log(sprintf(
            'User "%s" was registered',
            $email
        ));
    }
}