<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:27
         compiled from mail/html/init_order_customer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/html/init_order_customer.tpl', 2, false),array('modifier', 'substitute', 'mail/html/init_order_customer.tpl', 4, false),array('modifier', 'date_format', 'mail/html/init_order_customer.tpl', 20, false),)), $this); ?>
<?php func_load_lang($this, "mail/html/init_order_customer.tpl","eml_dear,eml_init_order_customer,lbl_order_details_label,lbl_order_id,lbl_order_date,lbl_order_status"); ?><?php echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/mail_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p /><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['eml_dear'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'customer', ($this->_tpl_vars['order']['firstname'])." ".($this->_tpl_vars['order']['lastname'])) : smarty_modifier_substitute($_tmp, 'customer', ($this->_tpl_vars['order']['firstname'])." ".($this->_tpl_vars['order']['lastname']))); ?>
,

<p /><?php echo $this->_tpl_vars['lng']['eml_init_order_customer']; ?>


<p /><?php echo $this->_tpl_vars['lng']['lbl_order_details_label']; ?>
:

<p />
<table cellpadding="2" cellspacing="1">
<tr>
<td width="20%"><b><?php echo $this->_tpl_vars['lng']['lbl_order_id']; ?>
:</b></td>
<td width="10">&nbsp;</td>
<td>#<?php echo $this->_tpl_vars['order']['orderid']; ?>
</td>
</tr>
<tr>
<td><b><?php echo $this->_tpl_vars['lng']['lbl_order_date']; ?>
:</b></td>
<td>&nbsp;</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format'])); ?>
</td>
</tr>
<tr>
<td><b><?php echo $this->_tpl_vars['lng']['lbl_order_status']; ?>
:</b></td>
<td>&nbsp;</td>
<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/order_status.tpl", 'smarty_include_vars' => array('mode' => 'static','status' => $this->_tpl_vars['order']['status'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
</table>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/signature.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>