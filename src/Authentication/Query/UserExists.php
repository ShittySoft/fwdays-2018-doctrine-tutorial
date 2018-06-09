<?php

namespace Authentication\Query;

interface UserExists
{
    public function __invoke(string $emailAddress) : bool;
}
