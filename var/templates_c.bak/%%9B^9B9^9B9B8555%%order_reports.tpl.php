<?php /* Smarty version 2.6.12, created on 2011-10-11 07:27:30
         compiled from main/order_reports.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'main/order_reports.tpl', 14, false),array('modifier', 'date_format', 'main/order_reports.tpl', 14, false),array('modifier', 'strip_tags', 'main/order_reports.tpl', 102, false),array('modifier', 'escape', 'main/order_reports.tpl', 102, false),array('function', 'html_select_date', 'main/order_reports.tpl', 69, false),)), $this); ?>
<?php func_load_lang($this, "main/order_reports.tpl","lbl_order_reports,lbl_date_period,lbl_all_dates,lbl_this_month,lbl_this_week,lbl_today,lbl_specify_period_below,lbl_order_date_from,lbl_order_date_through,lbl_manufacturers,lbl_include_orders_profit_margin,lbl_generate_html_report,lbl_generate_csv_report,lbl_order_reports"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_order_reports'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "reset.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
<!--
var searchform_def = [
	['posted_data[date_period]', '<?php echo $this->_tpl_vars['search_prefilled']['date_period']; ?>
'],
	['StartDay', '<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['search_prefilled']['start_date'])) ? $this->_run_mod_handler('default', true, $_tmp, time()) : smarty_modifier_default($_tmp, time())))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d") : smarty_modifier_date_format($_tmp, "%d")); ?>
'],
	['StartMonth', '<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['search_prefilled']['start_date'])) ? $this->_run_mod_handler('default', true, $_tmp, time()) : smarty_modifier_default($_tmp, time())))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m") : smarty_modifier_date_format($_tmp, "%m")); ?>
'],
	['StartYear', '<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['search_prefilled']['start_date'])) ? $this->_run_mod_handler('default', true, $_tmp, time()) : smarty_modifier_default($_tmp, time())))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
'],
	['EndDay', '<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['search_prefilled']['end_date'])) ? $this->_run_mod_handler('default', true, $_tmp, time()) : smarty_modifier_default($_tmp, time())))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d") : smarty_modifier_date_format($_tmp, "%d")); ?>
'],
	['EndMonth', '<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['search_prefilled']['end_date'])) ? $this->_run_mod_handler('default', true, $_tmp, time()) : smarty_modifier_default($_tmp, time())))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m") : smarty_modifier_date_format($_tmp, "%m")); ?>
'],
	['EndYear', '<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['search_prefilled']['end_date'])) ? $this->_run_mod_handler('default', true, $_tmp, time()) : smarty_modifier_default($_tmp, time())))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
']
];
<?php echo '
function managedate(status) {
	var fields = [\'StartDay\',\'StartMonth\',\'StartYear\',\'EndDay\',\'EndMonth\',\'EndYear\'];
	for (i in fields)
		if (document.searchform.elements[fields[i]])
			document.searchform.elements[fields[i]].disabled = status;
}
'; ?>

-->
</script>

<?php ob_start(); ?>

<form name="searchform" action="order_reports.php" method="post">
<input type="hidden" name="mode" value="" />

<table cellpadding="1" cellspacing="5" width="100%">

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_date_period']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td>
<table cellpadding="0" cellspacing="0">
<tr>
	<td width="5"><input type="radio" id="date_period_null" name="posted_data[date_period]" value=""<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['date_period'] == ""): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate(true)" /></td>
	<td class="OptionLabel"><label for="date_period_null"><?php echo $this->_tpl_vars['lng']['lbl_all_dates']; ?>
</label></td>

	<td width="5"><input type="radio" id="date_period_M" name="posted_data[date_period]" value="M"<?php if ($this->_tpl_vars['search_prefilled']['date_period'] == 'M'): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate(true)" /></td>
	<td class="OptionLabel"><label for="date_period_M"><?php echo $this->_tpl_vars['lng']['lbl_this_month']; ?>
</label></td>

	<td width="5"><input type="radio" id="date_period_W" name="posted_data[date_period]" value="W"<?php if ($this->_tpl_vars['search_prefilled']['date_period'] == 'W'): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate(true)" /></td>
	<td class="OptionLabel"><label for="date_period_W"><?php echo $this->_tpl_vars['lng']['lbl_this_week']; ?>
</label></td>

	<td width="5"><input type="radio" id="date_period_D" name="posted_data[date_period]" value="D"<?php if ($this->_tpl_vars['search_prefilled']['date_period'] == 'D'): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate(true)" /></td>
	<td class="OptionLabel"><label for="date_period_D"><?php echo $this->_tpl_vars['lng']['lbl_today']; ?>
</label></td>
</tr>
<tr>
	<td width="5"><input type="radio" id="date_period_C" name="posted_data[date_period]" value="C"<?php if ($this->_tpl_vars['search_prefilled']['date_period'] == 'C'): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate(false)" /></td>
	<td colspan="7" class="OptionLabel"><label for="date_period_C"><?php echo $this->_tpl_vars['lng']['lbl_specify_period_below']; ?>
</label></td>
</tr>
</table>
</td>
</tr>

<tr> 
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_order_date_from']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td> 
	<?php echo smarty_function_html_select_date(array('prefix' => 'Start','time' => $this->_tpl_vars['search_prefilled']['start_date'],'start_year' => $this->_tpl_vars['config']['Company']['start_year'],'end_year' => $this->_tpl_vars['config']['Company']['end_year']), $this);?>

	</td>
</tr>

<tr> 
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_order_date_through']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td> 
	<?php echo smarty_function_html_select_date(array('prefix' => 'End','time' => $this->_tpl_vars['search_prefilled']['end_date'],'start_year' => $this->_tpl_vars['config']['Company']['start_year'],'end_year' => $this->_tpl_vars['config']['Company']['end_year'],'display_days' => true), $this);?>

	</td>
</tr>

<tr> 
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_manufacturers']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td>
  <select name="posted_data[manufacturers][]" multiple="multiple" size="10">
  <?php $_from = $this->_tpl_vars['manufacturers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['mid'] => $this->_tpl_vars['mnf']):
?>
    <option value="<?php echo $this->_tpl_vars['mid']; ?>
"<?php if ($this->_tpl_vars['mnf']['selected']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['mnf']['manufacturer']; ?>
</option>
  <?php endforeach; endif; unset($_from); ?>
  </select>
	</td>
</tr>
<tr> 
	<td class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_include_orders_profit_margin']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td>
  <input type="checkbox" name="posted_data[include_margin_100]" value="Y"<?php if ($this->_tpl_vars['search_prefilled']['include_margin_100'] == 'Y' || ! $this->_tpl_vars['search_prefilled']): ?> checked="checked"<?php endif; ?> />
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="3" class="SubmitBox">
	<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_generate_html_report'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.searchform.mode.value=''; document.searchform.target='_blank'; document.searchform.submit();" />
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_generate_csv_report'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.searchform.mode.value='csv'; document.searchform.target=''; document.searchform.submit();" />

<?php if ($this->_tpl_vars['search_prefilled']['date_period'] != 'C'): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
managedate(true);
-->
</script>
<?php endif; ?>
	</td>
</tr>

</table>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_order_reports'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>