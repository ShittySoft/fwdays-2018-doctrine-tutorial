<?php

namespace Authentication\Notification;

interface NotifyUserOfRegistration
{
    public function __invoke(string $email) : void;
}
