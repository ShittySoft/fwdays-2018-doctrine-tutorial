<?php

namespace Authentication\Query;

use Authentication\Value\EmailAddress;

interface UserExists
{
    public function __invoke(EmailAddress $emailAddress) : bool;
}
