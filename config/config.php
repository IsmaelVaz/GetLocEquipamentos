<?php header("Content-type: text/html; charset=utf-8");
    
    $server = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
    
    switch ($server) {
    case 'localhost':
        $host= "localhost";
        $port="3308";
        $user= "root";
        $pass= "root";
        $database= "getlocequip";
        $type = "mysql";
        
        $isDevMode = true;
        define('URL', 'http://localhost/getlocequipamentos/');
        define('URLS', 'https://localhost/getlocequipamentos/');
        define('DIR', 'C:/work/www/getlocequipamentos/');
        break;
    default:
        break;
}
?>