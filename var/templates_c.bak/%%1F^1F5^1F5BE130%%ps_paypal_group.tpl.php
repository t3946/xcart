<?php /* Smarty version 2.6.12, created on 2011-10-11 07:04:02
         compiled from payments/ps_paypal_group.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'payments/ps_paypal_group.tpl', 38, false),array('modifier', 'strip_tags', 'payments/ps_paypal_group.tpl', 83, false),)), $this); ?>
<?php func_load_lang($this, "payments/ps_paypal_group.tpl","txt_cc_configure_top_text,txt_paypal_solution_title,lbl_paypal_sol_std,txt_paypal_sol_std_note,lbl_paypal_sol_pro,lbl_paypal_guidelines_click,txt_paypal_sol_pro_note,lbl_paypal_sol_express,lbl_paypal_guidelines_click,txt_paypal_sol_express_note,lbl_update,lbl_cc_settings"); ?>
<h3>PayPal</h3>

<?php echo $this->_tpl_vars['lng']['txt_cc_configure_top_text']; ?>


<?php echo '
<script type="text/javascript" language="JavaScript 1.2">
<!--
function view_solution(solution) {
	if (!document.getElementById(\'sol_ipn\') || !document.getElementById(\'sol_pro\'))
		return false;

	if (solution == "ipn") {
		document.getElementById(\'sol_ipn\').style.display = \'\';
		document.getElementById(\'sol_pro\').style.display = \'none\';

	} else {
		document.getElementById(\'sol_ipn\').style.display = \'none\';
		document.getElementById(\'sol_pro\').style.display = \'\';
	}
}
-->
</script>
'; ?>

<p />
<?php ob_start(); ?>

<br />

<?php echo $this->_tpl_vars['lng']['txt_paypal_solution_title']; ?>

<br /><br />

<table cellpadding="5" cellspacing="0" width="100%">

<form action="cc_processing.php" method="post">
<input type="hidden" name="cc_processor" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['cc_processor'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" />

<tr valign="top">
<td width="20"><input id="r_sol_ipn" type="radio" name="paypal_solution" onclick="view_solution('ipn');" value="ipn"<?php if ($this->_tpl_vars['config']['paypal_solution'] == 'ipn'): ?> CHECKED<?php endif; ?> /></td>
<td width="100%"><label for="r_sol_ipn"><b><?php echo $this->_tpl_vars['lng']['lbl_paypal_sol_std']; ?>
</b><br />
<?php echo $this->_tpl_vars['lng']['txt_paypal_sol_std_note']; ?>

</label>
</tr>

<tr valign="top">
<td><input id="r_sol_pro" type="radio" name="paypal_solution" onclick="view_solution('pro');" value="pro"<?php if ($this->_tpl_vars['config']['paypal_solution'] == 'pro'): ?> CHECKED<?php endif; ?> /></td>
<td><label for="r_sol_pro"><b><?php echo $this->_tpl_vars['lng']['lbl_paypal_sol_pro']; ?>
</b> &nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:void(0);" onclick="javascript:window.open('http://www.x-cart.com/xcart_manual/online/paypal_pro_notes.htm','PPEC_HELP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');"><?php echo $this->_tpl_vars['lng']['lbl_paypal_guidelines_click']; ?>
</a><br />
<?php echo $this->_tpl_vars['lng']['txt_paypal_sol_pro_note']; ?>

</label>
</tr>

<tr valign="top">
<td><input id="r_sol_express" type="radio" name="paypal_solution" onclick="view_solution('express');" value="express"<?php if ($this->_tpl_vars['config']['paypal_solution'] == 'express'): ?> CHECKED<?php endif; ?> /></td>
<td><label for="r_sol_express"><b><?php echo $this->_tpl_vars['lng']['lbl_paypal_sol_express']; ?>
</b> &nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:void(0);" onclick="javascript:window.open('http://www.x-cart.com/xcart_manual/online/paypal_pro_notes.htm','PPEC_HELP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');"><?php echo $this->_tpl_vars['lng']['lbl_paypal_guidelines_click']; ?>
</a><br />
<?php echo $this->_tpl_vars['lng']['txt_paypal_sol_express_note']; ?>

</label>
</tr>

<tr>
<td colspan="2"><hr size="1" noshade="noshade" /></td>
</tr>

<tr id="sol_pro" style="display: <?php if ($this->_tpl_vars['config']['paypal_solution'] != 'ipn'): ?>''<?php else: ?>none<?php endif; ?>">
<td>&nbsp;</td>
<td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "payments/ps_paypal_pro.tpl", 'smarty_include_vars' => array('conf_prefix' => "conf_data[pro]",'module_data' => $this->_tpl_vars['conf_data']['pro'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
</tr>

<tr id="sol_ipn" style="display: <?php if ($this->_tpl_vars['config']['paypal_solution'] == 'ipn'): ?>''<?php else: ?>none<?php endif; ?>">
<td>&nbsp;</td>
<td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "payments/ps_paypal.tpl", 'smarty_include_vars' => array('conf_prefix' => "conf_data[ipn]",'module_data' => $this->_tpl_vars['conf_data']['ipn'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
</tr>

<tr>
<td colspan="2" align="center">
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</td>
</tr>

</form>

</table>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_cc_settings'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>