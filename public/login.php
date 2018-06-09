<?php

namespace MyApp;

use Authentication\Value\EmailAddress;
use Authentication\Value\PlainTextPassword;
use Infrastructure\Authentication\Query\DQLBasedUserExists;
use Infrastructure\Authentication\Repository\DoctrineUsers;

(function () {
    ini_set('display_errors', (string) true);
    ini_set('error_reporting', (string) \E_ALL);

    require_once __DIR__ . '/../vendor/autoload.php';

    $entityManager = require __DIR__ . '/../bootstrap.php';

    $email      = EmailAddress::fromString($_POST['emailAddress']);
    $password   = PlainTextPassword::fromString($_POST['password']);
    $repository = new DoctrineUsers($entityManager);
    $userExists = new DQLBasedUserExists($entityManager);

    if (! $userExists($email)) {
        http_response_code(401);

        echo 'NOT OK';

        return;
    }

    if (! $repository->get($email)->logIn($password)) {
        http_response_code(401);

        echo 'NOT OK';

        return;
    }

    session_start();
    $_SESSION['emailAddress'] = $email;

    echo 'OK';
})();
