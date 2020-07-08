<?php header("Content-type: text/html; charset=utf-8");
    
    $server = $_SERVER['SERVER_NAME'];
    
    switch ($server) {
    case 'localhost':
        $host= '';
        $user= '';
        $pass= '';
        $database= '';
        
        define('URL', 'http://localhost/getlocequipamentos/');
        define('URLS', 'https://localhost/getlocequipamentos/');
        define('DIR', 'C:/work/www/getlocequipamentos/');
        break;
    default:
        break;
}
?>