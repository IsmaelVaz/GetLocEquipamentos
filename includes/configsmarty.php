<?php
    $smarty = new Smarty();

    $smarty->setTemplateDir(DIR.'/resources/templates');
    $smarty->setCompileDir(DIR.'/resources/templates_c/');
    $smarty->setConfigDir(DIR.'/resources/configs/');
    $smarty->setCacheDir(DIR.'/resources/cache/');
    $smarty->setLeftDelimiter('{{');
    $smarty->setRightDelimiter('}}');
?>