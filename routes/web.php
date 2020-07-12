<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$container = require_once DIR.'routes/erro.php';
//Create Slim
$app = new \Slim\App($container);

// Rotas do sistema
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hello, index");
    return $response;
});
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->run();