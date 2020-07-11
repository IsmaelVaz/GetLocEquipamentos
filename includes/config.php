<?php header("Content-type: text/html; charset=utf-8");
    
    $server = $_SERVER['SERVER_NAME'];
    
    switch ($server) {
    case 'localhost':
        $host= '';
        $user= '';
        $pass= '';
        $database= '';
        
        define('URL', 'http://localhost/getlocequipamentos/src/');
        define('URLS', 'https://localhost/getlocequipamentos/src/');
        define('DIR', 'C:/work/www/getlocequipamentos/src/');
        break;
    default:
        break;
}
?>