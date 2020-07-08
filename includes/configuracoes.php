<?php header("Content-type: text/html; charset=utf-8");
    require_once __DIR__.'/config.php';
    require_once DIR.'/vendor/smarty/libs/Smarty.class.php';
    require_once DIR.'/includes/configsmarty.php';
    require_once DIR.'/vendor/autoload.php';
    
    $router = new \Router\Router();
    
    $router->on('GET', 'path/to/action', showIndex());

    echo $router->run($router->method(), $router->uri());
    
    
    function showIndex(){
        $smarty->assign('name','Ned');
        $smarty->display('index.tpl');
    }
?>