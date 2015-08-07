<?php

$version = explode('?', explode('/', $_SERVER['REQUEST_URI'])[1])[0];
$route = __DIR__ . '/../src/' . $version . '/route.php';

if (is_readable($route)) {
    require_once $route;
} else {
    echo 'Invalid version. Try `/latest`';
}
