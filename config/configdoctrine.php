<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// Configuração padrão do Doctrine ORM
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
//$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
// XML de configuração do Modelo de dados
$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/XMLEntityDoctrine"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// conifgurações do banco de dados

$connectionParams = array(
    'dbname' => $database,
    'user' => $user,
    'password' =>$pass,
    'host' => $host,
    'driver' => 'pdo_mysql',
);
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);


// Gerenciador de Entidades
$entityManager = EntityManager::create($conn, $config);