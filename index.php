<?php header("Content-type: text/html; charset=utf-8");
    require_once __DIR__.'/includes/configuracoes.php';
    
    $routes = new \Routes\Routes();
    
    $routes->on('GET', '/path/(\w+)?/to/(\w+)', function($teste, $teste2){
        $smarty = $_SESSION['smarty'];
        $smarty->assign('name',$teste.'-'.$teste2);
        $smarty->display('index.tpl');
    });
    
    $routes->on('GET', '/Cliente', function(){
        $smarty = $_SESSION['smarty'];
        $smarty->assign('name','Lista Cliente');
        $smarty->display('index.tpl');
    });
    
    $routes->on('GET', '/Teste', function(){
        echo 'adadds';
    });
    
    echo $routes->run();
?>