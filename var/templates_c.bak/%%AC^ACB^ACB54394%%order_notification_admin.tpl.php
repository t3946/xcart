<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:27
         compiled from mail/order_notification_admin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/order_notification_admin.tpl', 2, false),array('function', 'math', 'mail/order_notification_admin.tpl', 5, false),array('modifier', 'cat', 'mail/order_notification_admin.tpl', 5, false),array('modifier', 'substitute', 'mail/order_notification_admin.tpl', 8, false),)), $this); ?>
<?php func_load_lang($this, "mail/order_notification_admin.tpl","eml_order_notification"); ?><?php echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<?php $this->assign('where', 'A');  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/mail_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->assign('max_truncate', $this->_tpl_vars['config']['Email']['max_truncate']);  echo smarty_function_math(array('assign' => 'max_space','equation' => "x+5",'x' => $this->_tpl_vars['max_truncate']), $this); $this->assign('max_space', ((is_array($_tmp=((is_array($_tmp="%-")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['max_space'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 's') : smarty_modifier_cat($_tmp, 's'))); ?>

<?php $this->assign('orderid', ((is_array($_tmp=$this->_tpl_vars['order']['order_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['order']['orderid']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['order']['orderid'])));  echo ((is_array($_tmp=$this->_tpl_vars['lng']['eml_order_notification'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'orderid', $this->_tpl_vars['orderid']) : smarty_modifier_substitute($_tmp, 'orderid', $this->_tpl_vars['orderid'])); ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/order_invoice.tpl", 'smarty_include_vars' => array('to_admin' => 'Y','show_shipping_groups' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/signature.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>