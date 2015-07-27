<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim();

$app->post('/toqti', function ($name) {
    echo "Hello, $name";
});

$app->post('/fromqti', function ($name) {
    echo "Hello, $name";
});

$app->run();
