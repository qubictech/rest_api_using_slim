<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';
require '../includes/db_connect.php';

$app = new \Slim\App;

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name<br>");    

    $db = new DbConnect;

    if ($db->connect() != null) {
        echo "Connection Successful";
    }

    return $response;
});

$app->run();
