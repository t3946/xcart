<?php /* Smarty version 2.6.12, created on 2011-10-11 07:04:03
         compiled from payments/ps_paypal.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'payments/ps_paypal.tpl', 6, false),)), $this); ?>
<?php func_load_lang($this, "payments/ps_paypal.tpl","lbl_cc_paypal_acc,lbl_cc_paypal_for,lbl_cc_currency,lbl_cc_order_prefix,lbl_cc_testlive_mode,lbl_cc_testlive_test,lbl_cc_testlive_live,txt_paypal_sandbox_note"); ?>
<table cellspacing="10">
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_paypal_acc']; ?>
:</td>
<td><input type="text" name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param01]" size="24" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['module_data']['param01'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>

<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_paypal_for']; ?>
:</td>
<td><input type="text" name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param02]" size="24" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['module_data']['param02'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>

<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_currency']; ?>
:</td>
<td>
<select name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param03]">
	<option value="USD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'USD'): ?> selected="selected"<?php endif; ?>>U.S. Dollars (USD)</option>
	<option value="CAD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CAD'): ?> selected="selected"<?php endif; ?>>Canadian Dollars (CAD)</option>
	<option value="EUR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'EUR'): ?> selected="selected"<?php endif; ?>>Euros (EUR)</option>
	<option value="GBP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'GBP'): ?> selected="selected"<?php endif; ?>>Pounds Sterling (GBP)</option>
	<option value="JPY"<?php if ($this->_tpl_vars['module_data']['param03'] == 'JPY'): ?> selected="selected"<?php endif; ?>>Yen (JPY)</option>
	<option value="AUD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'AUD'): ?> selected="selected"<?php endif; ?>>Australian Dollars (AUD)</option>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_order_prefix']; ?>
:</td>
<td><input type="text" name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param04]" size="36" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['module_data']['param04'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>

<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_testlive_mode']; ?>
:</td>
<td>
<select name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[testmode]">
<option value="Y"<?php if ($this->_tpl_vars['module_data']['testmode'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_cc_testlive_test']; ?>
</option>
<option value="N"<?php if ($this->_tpl_vars['module_data']['testmode'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_cc_testlive_live']; ?>
</option>
</select>
<br /><font class="SmallText">
<?php echo $this->_tpl_vars['lng']['txt_paypal_sandbox_note']; ?>

</font>
</td>
</tr>

</table>