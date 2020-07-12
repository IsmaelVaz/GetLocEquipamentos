<?php

//Erros
$c = new \Slim\Container();

//Override the default Not Found Handler before creating App
$c['notFoundHandler'] = function ($c) use ($smarty) {
    return function ($request, $response) use ($c, $smarty) {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write($smarty->fetch("error/404.tpl"));
    };
};

$c['notAllowedHandler'] = function ($c) use ($smarty) {
    return function ($request, $response, $methods) use ($c, $smarty) {
        $smarty->append('methods', $methods);
        return $response->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-type', 'text/html')
            ->write($smarty->fetch("error/405.tpl"));
    };
};

return $c;