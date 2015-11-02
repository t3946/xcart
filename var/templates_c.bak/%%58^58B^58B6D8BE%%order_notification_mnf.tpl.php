<?php /* Smarty version 2.6.12, created on 2011-10-11 07:23:41
         compiled from mail/order_notification_mnf.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/order_notification_mnf.tpl', 2, false),array('function', 'math', 'mail/order_notification_mnf.tpl', 4, false),array('modifier', 'cat', 'mail/order_notification_mnf.tpl', 4, false),array('modifier', 'strip_tags', 'mail/order_notification_mnf.tpl', 5, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<?php $this->assign('max_truncate', $this->_tpl_vars['config']['Email']['max_truncate']);  echo smarty_function_math(array('assign' => 'max_space','equation' => "x+5",'x' => $this->_tpl_vars['max_truncate']), $this); $this->assign('max_space', ((is_array($_tmp=((is_array($_tmp="%-")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['max_space'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 's') : smarty_modifier_cat($_tmp, 's')));  echo ((is_array($_tmp=$this->_tpl_vars['message_body'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)); ?>


-------------------------------------------------

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/order_invoice_mnf.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/signature.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>