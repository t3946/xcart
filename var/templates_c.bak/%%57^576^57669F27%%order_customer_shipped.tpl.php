<?php /* Smarty version 2.6.12, created on 2011-10-11 07:35:50
         compiled from mail/order_customer_shipped.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/order_customer_shipped.tpl', 3, false),array('function', 'math', 'mail/order_customer_shipped.tpl', 5, false),array('modifier', 'cat', 'mail/order_customer_shipped.tpl', 5, false),array('modifier', 'substitute', 'mail/order_customer_shipped.tpl', 7, false),)), $this); ?>
<?php func_load_lang($this, "mail/order_customer_shipped.tpl","eml_dear"); ?><?php if ($this->_tpl_vars['customer'] != ''):  $this->assign('_userinfo', $this->_tpl_vars['customer']);  else:  $this->assign('_userinfo', $this->_tpl_vars['userinfo']);  endif;  echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<?php $this->assign('max_truncate', $this->_tpl_vars['config']['Email']['max_truncate']);  echo smarty_function_math(array('assign' => 'max_space','equation' => "x+5",'x' => $this->_tpl_vars['max_truncate']), $this); $this->assign('max_space', ((is_array($_tmp=((is_array($_tmp="%-")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['max_space'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 's') : smarty_modifier_cat($_tmp, 's'))); ?>

<?php if ($this->_tpl_vars['retrieve'] != 'Y'):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['eml_dear'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'customer', ($this->_tpl_vars['_userinfo']['firstname'])." ".($this->_tpl_vars['_userinfo']['lastname'])) : smarty_modifier_substitute($_tmp, 'customer', ($this->_tpl_vars['_userinfo']['firstname'])." ".($this->_tpl_vars['_userinfo']['lastname']))); ?>
,<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/order_invoice.tpl", 'smarty_include_vars' => array('show_shipping_groups' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['retrieve'] != 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/signature.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>