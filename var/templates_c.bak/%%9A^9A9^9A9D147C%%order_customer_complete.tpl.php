<?php /* Smarty version 2.6.12, created on 2011-10-11 07:23:41
         compiled from mail/html/order_customer_complete.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/html/order_customer_complete.tpl', 2, false),array('modifier', 'substitute', 'mail/html/order_customer_complete.tpl', 5, false),array('modifier', 'date_format', 'mail/html/order_customer_complete.tpl', 21, false),)), $this); ?>
<?php func_load_lang($this, "mail/html/order_customer_complete.tpl","eml_dear,eml_order_complete,lbl_order_id,lbl_order_date,lbl_tracking_number"); ?><?php echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>


<p /><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['eml_dear'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'customer', ($this->_tpl_vars['customer']['firstname'])." ".($this->_tpl_vars['customer']['lastname'])) : smarty_modifier_substitute($_tmp, 'customer', ($this->_tpl_vars['customer']['firstname'])." ".($this->_tpl_vars['customer']['lastname']))); ?>
,

<p /><?php echo $this->_tpl_vars['lng']['eml_order_complete']; ?>



<hr size="1" noshade="noshade" />
<p />
<table cellpadding="2" cellspacing="1" width="100%">
<tr>
<td width="20%"><b><?php echo $this->_tpl_vars['lng']['lbl_order_id']; ?>
:</b></td>
<td width="10">&nbsp;</td>
<td width="80%"><tt><b><?php echo $this->_tpl_vars['order']['order_prefix'];  echo $this->_tpl_vars['order']['orderid']; ?>
</b></tt></td>
</tr>
<tr>
<td><b><?php echo $this->_tpl_vars['lng']['lbl_order_date']; ?>
:</b></td>
<td width="10">&nbsp;</td>
<td><tt><b><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format'])); ?>
</b></tt></td>
</tr>
<?php if ($this->_tpl_vars['order']['tracking']): ?>
<tr>
<td><b><?php echo $this->_tpl_vars['lng']['lbl_tracking_number']; ?>
:</b></td>
<td width="10">&nbsp;</td>
<td><tt><?php echo $this->_tpl_vars['order']['tracking']; ?>
</tt></td>
</tr>
<?php endif; ?>
<tr>
	<td colspan="3"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/order_data.tpl", 'smarty_include_vars' => array()));
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
