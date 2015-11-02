<?php /* Smarty version 2.6.12, created on 2011-10-11 06:07:16
         compiled from customer/main/pages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'eval', 'customer/main/pages.tpl', 6, false),)), $this); ?>
<br>
<?php ob_start();  if ($this->_tpl_vars['page_content'] != ''):  if ($this->_tpl_vars['config']['General']['parse_smarty_tags'] == 'Y'):  echo smarty_function_eval(array('var' => $this->_tpl_vars['page_content']), $this);?>

<?php else:  echo $this->_tpl_vars['page_content']; ?>

<?php endif;  endif;  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['page_data']['title'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>