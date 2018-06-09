<?php

namespace Authentication\Notification;

use Authentication\Value\EmailAddress;

interface NotifyUserOfRegistration
{
    public function __invoke(EmailAddress $email) : void;
}
