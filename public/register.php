<?php

namespace MyApp;

use Authentication\Entity\User;
use Infrastructure\Authentication\Notification\NotifyUserOfRegistrationInTerminal;
use Infrastructure\Authentication\Query\UserExistsThroughUglyHacks;
use Infrastructure\Authentication\Repository\FileUsers;

(function () {
    ini_set('display_errors', (string) true);
    ini_set('error_reporting', (string) \E_ALL);

    require_once __DIR__ . '/../vendor/autoload.php';

    $email      = $_POST['emailAddress'];
    $password   = $_POST['password'];
    $repository = new FileUsers(__DIR__ . '/../data/users.dat');
    $userExists = new UserExistsThroughUglyHacks($repository);
    $notify     = new NotifyUserOfRegistrationInTerminal();

    $repository->store(User::register($email, $password, $userExists, $notify));

    echo 'OK';
})();