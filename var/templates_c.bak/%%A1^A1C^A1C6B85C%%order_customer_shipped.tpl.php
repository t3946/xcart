<?php /* Smarty version 2.6.12, created on 2011-10-11 07:35:50
         compiled from mail/html/order_customer_shipped.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/html/order_customer_shipped.tpl', 3, false),array('modifier', 'substitute', 'mail/html/order_customer_shipped.tpl', 6, false),)), $this); ?>
<?php func_load_lang($this, "mail/html/order_customer_shipped.tpl","eml_dear,txt_thank_you_for_purchase"); ?><?php if ($this->_tpl_vars['customer'] != ''):  $this->assign('_userinfo', $this->_tpl_vars['customer']);  else:  $this->assign('_userinfo', $this->_tpl_vars['userinfo']);  endif;  echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>


<?php if ($this->_tpl_vars['retrieve'] != 'Y'): ?><p /><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['eml_dear'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'customer', ($this->_tpl_vars['_userinfo']['firstname'])." ".($this->_tpl_vars['_userinfo']['lastname'])) : smarty_modifier_substitute($_tmp, 'customer', ($this->_tpl_vars['_userinfo']['firstname'])." ".($this->_tpl_vars['_userinfo']['lastname']))); ?>
,<?php endif; ?>

<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/order_invoice.tpl", 'smarty_include_vars' => array('show_shipping_groups' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['retrieve'] == 'Y'): ?>
<p />
<?php endif; ?>

<?php if ($this->_tpl_vars['retrieve'] != 'Y'): ?>
	<?php if (! $this->_tpl_vars['order']['empty_shipping_groups']): ?>
	<table cellspacing="0" cellpadding="0" width="<?php if ($this->_tpl_vars['is_nomail'] == 'Y'): ?>100%<?php else: ?>600<?php endif; ?>" bgcolor="#ffffff">
	<tr>
		<td align="center"><br /><br /><font style="FONT-SIZE:12px"><?php echo $this->_tpl_vars['lng']['txt_thank_you_for_purchase']; ?>
</font></td>
	</tr>
	</table>
	<?php endif; ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/signature.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
