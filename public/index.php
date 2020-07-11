<?php header("Content-type: text/html; charset=utf-8");
    require_once __DIR__.'/../includes/configuracoes.php';
   
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;


    $app = new \Slim\App;
    $app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
        $name = $args['name'];
        $response->getBody()->write("Hello, $name");

        return $response;
    });
    $app->run();