<?php

namespace Routes;
class Routes {
    private $routes = [];
    
    public static function method() {
        return isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';
    }
    
    public static function uri() {
        $PHPSELF = $_SERVER['PHP_SELF'].'/'; 
        $self = isset($PHPSELF) ? str_replace('/index.php', '', $PHPSELF) : '';
        $uri = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
        
        if ($self !== $uri) {
            $peaces = explode('/', $self);
            array_pop($peaces);
            $start = implode('/', $peaces);
            $search = '/' . preg_quote($start, '/') . '/';
            $uri = preg_replace($search, '', $uri, 1);
            $uri = empty($uri) ? '/' : $uri;
        }
        return $uri;
    }
    
    public function on($method, $path, $callback) { 
        $method = strtolower($method);
        if (!isset($this->routes[$method])) {
            $this->routes[$method] = [];
        }

        $uri = substr($path, 0, 1) !== '/' ? '/' . $path : $path;
        $pattern = str_replace('/', '\/', $uri);
        $route = '/^' . $pattern . '$/';

        $this->routes[$method][$route] = $callback;

        return $this;
    } 
    
    function run() {
        $method = Self::method();
        $uri = Self::uri();
        $method = strtolower($method);
        if (!isset($this->routes[$method])) {
            return null;
        }

        foreach ($this->routes[$method] as $route => $callback) {

            if (preg_match($route, $uri, $parameters)) {
                
                array_shift($parameters);
                parr($parameters);
                return call_user_func_array($callback, $parameters);
            }
        }
        return null;
    }
}