<?php
    spl_autoload_register(function ($class_name) {
        $url = __DIR__ . '\\' . $class_name . '.php';
        if (!file_exists($url)) {
            throw new Exception("Arquivo não encontrado");
        }
        require_once $url;
    });