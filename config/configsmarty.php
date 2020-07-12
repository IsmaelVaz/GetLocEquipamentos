<?php
    $smarty = new Smarty();

    $smarty->setTemplateDir(DIR.'/resources/view/');
    $smarty->setCompileDir(DIR.'/resources/view_c/');
    $smarty->setConfigDir(DIR.'/resources/configs/');
    $smarty->setCacheDir(DIR.'/resources/cache/');
    $smarty->setLeftDelimiter('{{');
    $smarty->setRightDelimiter('}}');
    
    $_SESSION['smarty'] = $smarty;
?>