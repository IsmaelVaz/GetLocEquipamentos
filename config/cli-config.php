<?php
// cli-config.php
require_once "configuracoes.php";

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);