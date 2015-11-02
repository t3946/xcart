<?php /* Smarty version 2.6.12, created on 2011-10-11 06:24:06
         compiled from mail/html/order_customer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/html/order_customer.tpl', 2, false),array('modifier', 'substitute', 'mail/html/order_customer.tpl', 5, false),)), $this); ?>
<?php func_load_lang($this, "mail/html/order_customer.tpl","eml_dear,eml_thankyou_for_order,lbl_invoice"); ?><?php echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>


<p /><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['eml_dear'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'customer', ($this->_tpl_vars['order']['firstname'])." ".($this->_tpl_vars['order']['lastname'])) : smarty_modifier_substitute($_tmp, 'customer', ($this->_tpl_vars['order']['firstname'])." ".($this->_tpl_vars['order']['lastname']))); ?>
,

<p /><?php echo $this->_tpl_vars['lng']['eml_thankyou_for_order']; ?>


<p /><b><?php echo $this->_tpl_vars['lng']['lbl_invoice']; ?>
:</b>

<p /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/order_invoice.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/signature.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>