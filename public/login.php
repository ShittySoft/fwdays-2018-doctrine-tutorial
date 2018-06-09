<?php

namespace MyApp;

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

// discuss: should the fetching by password happen at database level?
//          Should it happen inside the entity?
//          Or in a service?
