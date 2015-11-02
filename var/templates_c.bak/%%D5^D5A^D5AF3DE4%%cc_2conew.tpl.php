<?php /* Smarty version 2.6.12, created on 2011-10-11 07:04:22
         compiled from payments/cc_2conew.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substitute', 'payments/cc_2conew.tpl', 7, false),array('modifier', 'escape', 'payments/cc_2conew.tpl', 10, false),array('modifier', 'strip_tags', 'payments/cc_2conew.tpl', 36, false),)), $this); ?>
<?php func_load_lang($this, "payments/cc_2conew.tpl","txt_cc_configure_top_text,txt_cc_2conew_desc,txt_cc_2conew_note,lbl_cc_2checkoutcom_account,lbl_cc_2checkoutcom_secret,lbl_cc_testlive_mode,lbl_cc_testlive_test,lbl_cc_testlive_live,lbl_cc_order_prefix,lbl_update,lbl_cc_settings"); ?><h3>2checkout.Com</h3>
<?php echo $this->_tpl_vars['lng']['txt_cc_configure_top_text']; ?>

<p />
<?php echo $this->_tpl_vars['lng']['txt_cc_2conew_desc']; ?>

<p />
<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_cc_2conew_note'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'http_location', $this->_tpl_vars['http_location']) : smarty_modifier_substitute($_tmp, 'http_location', $this->_tpl_vars['http_location'])); ?>

<p />
<?php ob_start(); ?>
<form action="cc_processing.php?cc_processor=<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['cc_processor'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" method="post">
<center>
<table cellspacing="10">
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_2checkoutcom_account']; ?>
:</td>
<td><input type="text" name="param01" size="32" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['module_data']['param01'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_2checkoutcom_secret']; ?>
:</td>
<td><input type="password" name="param03" size="32" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['module_data']['param03'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_testlive_mode']; ?>
:</td>
<td>
<select name="testmode">
<option value="Y"<?php if ($this->_tpl_vars['module_data']['testmode'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_cc_testlive_test']; ?>

<option value="N"<?php if ($this->_tpl_vars['module_data']['testmode'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_cc_testlive_live']; ?>

</select>
</td>
</tr>
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_order_prefix']; ?>
:</td>
<td><input type="text" name="param02" size="32" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['module_data']['param02'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>
</table>
<p />
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</form>
</center>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_cc_settings'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>