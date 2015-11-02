<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:29
         compiled from modules/Subscriptions/subscription_plans.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'modules/Subscriptions/subscription_plans.tpl', 55, false),array('modifier', 'escape', 'modules/Subscriptions/subscription_plans.tpl', 55, false),)), $this); ?>
<?php func_load_lang($this, "modules/Subscriptions/subscription_plans.tpl","txt_subscription_for_product,txt_note,txt_edit_product_group,lbl_pay_period,lbl_period_fee,lbl_cost_of_one_day,lbl_days_same_period,lbl_days,lbl_apply,lbl_delete,lbl_pay_dates,lbl_subscription"); ?><?php if ($this->_tpl_vars['active_modules']['Subscriptions'] != ""): ?>

<br />

<?php echo $this->_tpl_vars['lng']['txt_subscription_for_product']; ?>


<br /><br />

<?php ob_start(); ?>

<form action="product_modify.php" method="post" name="subscription_form">
<input type="hidden" name="mode" value="subscription_modify" />
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />

<table cellspacing="0" cellpadding="3" width="100%">

<?php if ($this->_tpl_vars['geid'] != ''): ?>
<tr>
    <td width="15" class="TableSubHead">&nbsp;</td>
    <td class="TableSubHead" colspan="4"><b>* <?php echo $this->_tpl_vars['lng']['txt_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_edit_product_group']; ?>
</td>
</tr>
<?php endif; ?>

<tr class="TableHead">
<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
<td width="30%" nowrap="nowrap" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_pay_period']; ?>
</td>
<td width="25%" nowrap="nowrap" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_period_fee']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
)</td>
<td width="25%" nowrap="nowrap" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_cost_of_one_day']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
)</td>
<td width="25%" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_days_same_period']; ?>
</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input id="fields_subscription" type="checkbox" value="Y" name="fields[subscription]" /></td><?php endif; ?>
	<td valign="top" class="DataTable">
<select name="subscription[pay_period_type]" onchange="<?php echo 'if (this.selectedIndex == 4) { this.form.pay_period.disabled = false; this.form.pay_period.focus(); }else{ this.form.pay_period.disabled = true; }'; ?>
" style="width: 100%;">
<?php $_from = $this->_tpl_vars['subscription_periods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<option value="<?php echo $this->_tpl_vars['key']; ?>
"<?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['subscription']['pay_period_type']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['key']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select></td>
	<td valign="top" class="DataTable"><input type="text" name="subscription[price_period]" value="<?php echo $this->_tpl_vars['subscription']['price_period']; ?>
" size="15" style="width:100%" /></td>
	<td valign="top" class="DataTable"><input type="text" name="subscription[oneday_price]" value="<?php echo $this->_tpl_vars['subscription']['oneday_price']; ?>
" size="15" style="width:100%" /></td>
	<td valign="top"><input type="text" name="subscription[days_as_period]" value="<?php echo $this->_tpl_vars['subscription']['days_as_period']; ?>
" size="15" style="width:100%" /></td>
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td nowrap="nowrap"><input type="text" name="pay_period" size="12" value="<?php echo $this->_tpl_vars['pay_period']; ?>
"<?php if ($this->_tpl_vars['subscription']['pay_period_type'] != 'By Period'): ?> disabled="disabled"<?php endif; ?> style="width:80%" />&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_days']; ?>
</td>
	<td colspan="3">&nbsp;</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="4"><br />
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php if ($this->_tpl_vars['subscription']['pay_period_type']): ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: this.form.mode.value='subscription_delete'; this.form.submit();" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_pay_dates'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript:window.open('calendar.php?productid=<?php echo $this->_tpl_vars['product']['productid'];  echo $this->_tpl_vars['redirect_geid']; ?>
','calendar','width=600,height=500,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');" />
<?php endif; ?>
</td>
</tr>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_subscription'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>