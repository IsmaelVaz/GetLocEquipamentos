<?php
/* Smarty version 3.1.34-dev-7, created on 2020-07-08 02:52:33
  from 'C:\work\www\getlocequipamentos\resources\templates\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5f0518d1b06088_16985821',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '768c6a6202f3af532bb825a1761779a787f01366' => 
    array (
      0 => 'C:\\work\\www\\getlocequipamentos\\resources\\templates\\index.tpl',
      1 => 1594169551,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:box/header.tpl' => 1,
    'file:box/menu.tpl' => 1,
    'file:box/footer.tpl' => 1,
  ),
),false)) {
function content_5f0518d1b06088_16985821 (Smarty_Internal_Template $_smarty_tpl) {
ob_start();
$_prefixVariable1 = ob_get_clean();
echo $_prefixVariable1;
ob_start();
$_smarty_tpl->_subTemplateRender("file:box/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
$_prefixVariable2 = ob_get_clean();
echo $_prefixVariable2;
ob_start();
$_smarty_tpl->_subTemplateRender("file:box/menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
$_prefixVariable3 = ob_get_clean();
echo $_prefixVariable3;?>
pipipopo<br><?php ob_start();
echo $_smarty_tpl->tpl_vars['name']->value;
$_prefixVariable4 = ob_get_clean();
echo $_prefixVariable4;
ob_start();
$_smarty_tpl->_subTemplateRender("file:box/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
$_prefixVariable5 = ob_get_clean();
echo $_prefixVariable5;
ob_start();
$_prefixVariable6 = ob_get_clean();
echo $_prefixVariable6;
}
}
