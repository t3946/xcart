<?php /* Smarty version 2.6.12, created on 2011-10-11 06:30:26
         compiled from main/orders.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'main/orders.tpl', 23, false),array('modifier', 'date_format', 'main/orders.tpl', 23, false),array('modifier', 'formatprice', 'main/orders.tpl', 29, false),array('modifier', 'escape', 'main/orders.tpl', 40, false),array('modifier', 'strip_tags', 'main/orders.tpl', 185, false),array('modifier', 'trademark', 'main/orders.tpl', 252, false),array('modifier', 'substitute', 'main/orders.tpl', 496, false),array('function', 'html_select_date', 'main/orders.tpl', 122, false),)), $this); ?>
<?php func_load_lang($this, "main/orders.tpl","lbl_orders_management,txt_adm_search_orders_result_header,txt_search_orders_header,txt_search_orders_header,txt_search_orders_header,txt_search_orders_text,lbl_date_period,lbl_all_dates,lbl_this_month,lbl_this_week,lbl_today,lbl_specify_period_below,lbl_order_date_from,lbl_order_date_through,lbl_order_status,lbl_order_id,lbl_manufacturers,lbl_search_and_export,lbl_search,lbl_advanced_search_options,lbl_advanced_search_options,txt_adv_search_orders_text,lbl_order_total,lbl_payment_method,lbl_delivery,lbl_provider,lbl_order_features,lbl_entirely_or_partially_payed_by_gc,lbl_global_discount_applied,lbl_discount_coupon_applied,lbl_free_shipping,lbl_tax_exempt,lbl_gc_purchased,lbl_orders_with_notes_assigned,lbl_hold_ctrl_key,lbl_search_by_ordered_products,lbl_search_for_pattern,lbl_search_in,lbl_product_title,lbl_options,lbl_sku,lbl_productid,lbl_price,lbl_search_by_customer,lbl_customer,lbl_search_in,lbl_username,lbl_first_name,lbl_last_name,lbl_search_by_address,lbl_ignore_address,lbl_billing,lbl_shipping,lbl_both,lbl_city,lbl_state,lbl_country,lbl_please_select_one,lbl_zip_code,lbl_phone,lbl_fax,lbl_email,lbl_search,lbl_reset,lbl_search_orders,txt_N_results_found,txt_displaying_X_Y_results,txt_N_results_found,txt_delete_export_all_orders_note_admin,txt_delete_export_all_orders_note_provider,lbl_export_file_format,lbl_standart,lbl_40x_compatible,lbl_with_tab_delimiter,lbl_40x_compatible,lbl_with_semicolon_delimiter,lbl_40x_compatible,lbl_with_comma_delimiter,lbl_export_all,lbl_delete_all_orders,txt_delete_orders_warning,lbl_export_delete_orders,lbl_export_orders"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_orders_management'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['orders'] != ""):  if ($this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] )):  echo $this->_tpl_vars['lng']['txt_adm_search_orders_result_header']; ?>

<?php elseif ($this->_tpl_vars['usertype'] == 'P'):  echo $this->_tpl_vars['lng']['txt_search_orders_header']; ?>

<?php elseif ($this->_tpl_vars['usertype'] == 'C'):  echo $this->_tpl_vars['lng']['txt_search_orders_header']; ?>

<?php endif;  else:  echo $this->_tpl_vars['lng']['txt_search_orders_header']; ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['mode'] != 'search' || $this->_tpl_vars['orders'] == ""): ?>

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
'],
	['posted_data[total_min]', '<?php if ($this->_tpl_vars['search_prefilled'] == ""):  echo $this->_tpl_vars['zero'];  else:  echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['total_min'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp));  endif; ?>'],
	['posted_data[total_max]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['total_max'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
'],
	['posted_data[by_title]', <?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_title']): ?>true<?php else: ?>false<?php endif; ?>],
	['posted_data[by_options]', <?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_options']): ?>true<?php else: ?>false<?php endif; ?>],
	['posted_data[price_min]', '<?php if ($this->_tpl_vars['search_prefilled'] == ""):  echo $this->_tpl_vars['zero'];  else:  echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['price_min'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp));  endif; ?>'],
	['posted_data[price_max]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['price_max'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
'],
	['posted_data[address_type]', '<?php echo $this->_tpl_vars['search_prefilled']['address_type']; ?>
'],
	['posted_data[is_export]', ''],
	['posted_data[orderid1]', '<?php echo $this->_tpl_vars['search_prefilled']['orderid1']; ?>
'],
	['posted_data[orderid2]', '<?php echo $this->_tpl_vars['search_prefilled']['orderid2']; ?>
'],
	['posted_data[payment_method]', '<?php echo $this->_tpl_vars['search_prefilled']['payment_method']; ?>
'],
	['posted_data[product_substring]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['product_substring'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'],
	['posted_data[features][]', '<?php $_from = $this->_tpl_vars['search_prefilled']['features']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['fk'] => $this->_tpl_vars['fv']):
 echo $this->_tpl_vars['fk']; ?>
,<?php endforeach; endif; unset($_from); ?>'],
	['posted_data[provider]', '<?php echo $this->_tpl_vars['search_prefilled']['provider']; ?>
'],
	['posted_data[shipping_method]', '<?php echo $this->_tpl_vars['search_prefilled']['shipping_method']; ?>
'],
	['posted_data[productcode]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['productcode'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'],
	['posted_data[productid]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['productid'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'],
	['posted_data[customer]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['customer'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'],
	['posted_data[by_username]', <?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_username']): ?>true<?php else: ?>false<?php endif; ?>],
	['posted_data[by_firstname]', <?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_firstname']): ?>true<?php else: ?>false<?php endif; ?>],
	['posted_data[by_lastname]', <?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_lastname']): ?>true<?php else: ?>false<?php endif; ?>],
	['posted_data[city]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['city'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'],
	['posted_data[state]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['state'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'],
	['posted_data[country]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['country'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'],
	['posted_data[zipcode]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['zipcode'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'],
	['posted_data[phone]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['phone'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'],
	['posted_data[email]', '<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'],
	['posted_data[status]', '<?php echo $this->_tpl_vars['search_prefilled']['status']; ?>
']
];
<?php echo '
function managedate(type, status) {
	if (type != \'date\')
		var fields = [\'posted_data[city]\',\'posted_data[state]\',\'posted_data[country]\',\'posted_data[zipcode]\'];
	else
		var fields = [\'StartDay\',\'StartMonth\',\'StartYear\',\'EndDay\',\'EndMonth\',\'EndYear\'];
	
	for (i in fields)
		if (document.searchform.elements[fields[i]])
			document.searchform.elements[fields[i]].disabled = status;
}
'; ?>

-->
</script>

<?php ob_start(); ?>

<form name="searchform" action="orders.php" method="post">
<input type="hidden" name="mode" value="" />

<table cellpadding="0" cellspacing="0" width="100%">

<tr>
	<td>

<table cellpadding="1" cellspacing="5" width="100%">

<tr>
	<td colspan="3">
<?php echo $this->_tpl_vars['lng']['txt_search_orders_text']; ?>

<br /><br />
	</td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap" width="25%"><?php echo $this->_tpl_vars['lng']['lbl_date_period']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td>
<table cellpadding="0" cellspacing="0">
<tr>
	<td width="5"><input type="radio" id="date_period_null" name="posted_data[date_period]" value=""<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['date_period'] == ""): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate('date',true)" /></td>
	<td class="OptionLabel"><label for="date_period_null"><?php echo $this->_tpl_vars['lng']['lbl_all_dates']; ?>
</label></td>

	<td width="5"><input type="radio" id="date_period_M" name="posted_data[date_period]" value="M"<?php if ($this->_tpl_vars['search_prefilled']['date_period'] == 'M'): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate('date',true)" /></td>
	<td class="OptionLabel"><label for="date_period_M"><?php echo $this->_tpl_vars['lng']['lbl_this_month']; ?>
</label></td>

	<td width="5"><input type="radio" id="date_period_W" name="posted_data[date_period]" value="W"<?php if ($this->_tpl_vars['search_prefilled']['date_period'] == 'W'): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate('date',true)" /></td>
	<td class="OptionLabel"><label for="date_period_W"><?php echo $this->_tpl_vars['lng']['lbl_this_week']; ?>
</label></td>

	<td width="5"><input type="radio" id="date_period_D" name="posted_data[date_period]" value="D"<?php if ($this->_tpl_vars['search_prefilled']['date_period'] == 'D'): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate('date',true)" /></td>
	<td class="OptionLabel"><label for="date_period_D"><?php echo $this->_tpl_vars['lng']['lbl_today']; ?>
</label></td>
</tr>
<tr>
	<td width="5"><input type="radio" id="date_period_C" name="posted_data[date_period]" value="C"<?php if ($this->_tpl_vars['search_prefilled']['date_period'] == 'C'): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate('date',false)" /></td>
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
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_order_status']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/order_status.tpl", 'smarty_include_vars' => array('status' => $this->_tpl_vars['search_prefilled']['status'],'mode' => 'select','name' => "posted_data[status]",'extended' => 'Y','extra' => "style='width:70%'")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_order_id']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td>
<input type="text" name="posted_data[orderid1]" size="10" maxlength="15" value="<?php echo $this->_tpl_vars['search_prefilled']['orderid1']; ?>
" />
-
<input type="text" name="posted_data[orderid2]" size="10" maxlength="15"value="<?php echo $this->_tpl_vars['search_prefilled']['orderid2']; ?>
" />
	</td>
</tr>

<?php if ($this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['manufacturers']): ?>
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
<?php endif; ?>

<?php if (( $this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['current_membership_flag'] != 'FS' ) || $this->_tpl_vars['usertype'] == 'P'): ?>
<tr>
	<td colspan="2"></td>
	<td>
	<hr />
<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type="checkbox" id="posted_data_is_export" name="posted_data[is_export]" value="Y" /></td>
	<td>&nbsp;</td>
	<td class="FormButton" nowrap="nowrap"><label for="posted_data_is_export"><?php echo $this->_tpl_vars['lng']['lbl_search_and_export']; ?>
</label></td>
</tr>
</table>
	</td>
</tr>
<?php endif; ?>

<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="3" class="SubmitBox">
	<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_search'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.searchform.mode.value=''; document.searchform.submit();" />

<?php if ($this->_tpl_vars['search_prefilled']['date_period'] != 'C'): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
managedate('date',true);
-->
</script>
<?php endif; ?>
	</td>
</tr>

</table>

<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/visiblebox_link.tpl", 'smarty_include_vars' => array('mark' => '1','title' => $this->_tpl_vars['lng']['lbl_advanced_search_options'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />

<table cellpadding="1" cellspacing="5" width="100%"<?php if ($this->_tpl_vars['js_enabled'] == 'Y'): ?> style="display: none;"<?php endif; ?> id="box1">

<tr>
	<td colspan="3"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_advanced_search_options'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<td colspan="3"><?php echo $this->_tpl_vars['lng']['txt_adv_search_orders_text']; ?>
<br /><br /></td>
</tr>

<?php if ($this->_tpl_vars['usertype'] != 'C'): ?>
<tr>
	<td class="FormButton" nowrap="nowrap" width="25%"><?php echo $this->_tpl_vars['lng']['lbl_order_total']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
):</td>
	<td width="10">&nbsp;</td>
	<td>

<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type="text" size="10" maxlength="15" name="posted_data[total_min]" value="<?php if ($this->_tpl_vars['search_prefilled'] == ""):  echo $this->_tpl_vars['zero'];  else:  echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['total_min'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp));  endif; ?>" /></td>
	<td>&nbsp;-&nbsp;</td>
	<td><input type="text" size="10" maxlength="15" name="posted_data[total_max]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['total_max'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
" /></td>
</tr>
</table>

	</td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_payment_method']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td>
	<select name="posted_data[payment_method]" style="width:70%">
		<option value=""></option>
<?php unset($this->_sections['pm']);
$this->_sections['pm']['name'] = 'pm';
$this->_sections['pm']['loop'] = is_array($_loop=$this->_tpl_vars['payment_methods']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['pm']['show'] = true;
$this->_sections['pm']['max'] = $this->_sections['pm']['loop'];
$this->_sections['pm']['step'] = 1;
$this->_sections['pm']['start'] = $this->_sections['pm']['step'] > 0 ? 0 : $this->_sections['pm']['loop']-1;
if ($this->_sections['pm']['show']) {
    $this->_sections['pm']['total'] = $this->_sections['pm']['loop'];
    if ($this->_sections['pm']['total'] == 0)
        $this->_sections['pm']['show'] = false;
} else
    $this->_sections['pm']['total'] = 0;
if ($this->_sections['pm']['show']):

            for ($this->_sections['pm']['index'] = $this->_sections['pm']['start'], $this->_sections['pm']['iteration'] = 1;
                 $this->_sections['pm']['iteration'] <= $this->_sections['pm']['total'];
                 $this->_sections['pm']['index'] += $this->_sections['pm']['step'], $this->_sections['pm']['iteration']++):
$this->_sections['pm']['rownum'] = $this->_sections['pm']['iteration'];
$this->_sections['pm']['index_prev'] = $this->_sections['pm']['index'] - $this->_sections['pm']['step'];
$this->_sections['pm']['index_next'] = $this->_sections['pm']['index'] + $this->_sections['pm']['step'];
$this->_sections['pm']['first']      = ($this->_sections['pm']['iteration'] == 1);
$this->_sections['pm']['last']       = ($this->_sections['pm']['iteration'] == $this->_sections['pm']['total']);
?>
		<option value="<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['pm']['index']]['payment_method']; ?>
"<?php if ($this->_tpl_vars['search_prefilled']['payment_method'] == $this->_tpl_vars['payment_methods'][$this->_sections['pm']['index']]['payment_method']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['payment_methods'][$this->_sections['pm']['index']]['payment_method']; ?>
</option>
<?php endfor; endif; ?>
	</select>
	</td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_delivery']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td>
	<select name="posted_data[shipping_method]" style="width:70%">
		<option value=""></option>
<?php unset($this->_sections['sm']);
$this->_sections['sm']['name'] = 'sm';
$this->_sections['sm']['loop'] = is_array($_loop=$this->_tpl_vars['shipping_methods']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['sm']['show'] = true;
$this->_sections['sm']['max'] = $this->_sections['sm']['loop'];
$this->_sections['sm']['step'] = 1;
$this->_sections['sm']['start'] = $this->_sections['sm']['step'] > 0 ? 0 : $this->_sections['sm']['loop']-1;
if ($this->_sections['sm']['show']) {
    $this->_sections['sm']['total'] = $this->_sections['sm']['loop'];
    if ($this->_sections['sm']['total'] == 0)
        $this->_sections['sm']['show'] = false;
} else
    $this->_sections['sm']['total'] = 0;
if ($this->_sections['sm']['show']):

            for ($this->_sections['sm']['index'] = $this->_sections['sm']['start'], $this->_sections['sm']['iteration'] = 1;
                 $this->_sections['sm']['iteration'] <= $this->_sections['sm']['total'];
                 $this->_sections['sm']['index'] += $this->_sections['sm']['step'], $this->_sections['sm']['iteration']++):
$this->_sections['sm']['rownum'] = $this->_sections['sm']['iteration'];
$this->_sections['sm']['index_prev'] = $this->_sections['sm']['index'] - $this->_sections['sm']['step'];
$this->_sections['sm']['index_next'] = $this->_sections['sm']['index'] + $this->_sections['sm']['step'];
$this->_sections['sm']['first']      = ($this->_sections['sm']['iteration'] == 1);
$this->_sections['sm']['last']       = ($this->_sections['sm']['iteration'] == $this->_sections['sm']['total']);
?>
		<option value="<?php echo $this->_tpl_vars['shipping_methods'][$this->_sections['sm']['index']]['shippingid']; ?>
"<?php if ($this->_tpl_vars['search_prefilled']['shipping_method'] == $this->_tpl_vars['shipping_methods'][$this->_sections['sm']['index']]['shippingid']): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['shipping_methods'][$this->_sections['sm']['index']]['shipping'])) ? $this->_run_mod_handler('trademark', true, $_tmp) : smarty_modifier_trademark($_tmp)); ?>
</option>
<?php endfor; endif; ?>
	</select>
	</td>
</tr>

<?php endif; ?>

<?php if ($this->_tpl_vars['usertype'] != 'C'):  if ($this->_tpl_vars['usertype'] == 'A'): ?>
<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_provider']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td>
	<input type="text" name="posted_data[provider]" size="30" value="<?php echo $this->_tpl_vars['search_prefilled']['provider']; ?>
" style="width:70%" />
	</td>
</tr>
<?php endif; ?>

<tr> 
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_order_features']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td>
<?php $this->assign('features', $this->_tpl_vars['search_prefilled']['features']); ?>
	<select name="posted_data[features][]" multiple="multiple" size="7" style="width:70%">
		<option value="gc_applied"<?php if ($this->_tpl_vars['features']['gc_applied']): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_entirely_or_partially_payed_by_gc'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</option>
		<option value="discount_applied"<?php if ($this->_tpl_vars['features']['discount_applied']): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_global_discount_applied'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</option>
		<option value="coupon_applied"<?php if ($this->_tpl_vars['features']['coupon_applied']): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_discount_coupon_applied'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</option>
		<option value="free_ship"<?php if ($this->_tpl_vars['features']['free_ship']): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_free_shipping'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</option>
		<option value="free_tax"<?php if ($this->_tpl_vars['features']['free_tax']): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_tax_exempt'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</option>
		<option value="gc_ordered"<?php if ($this->_tpl_vars['features']['gc_ordered']): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_gc_purchased'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</option>
		<option value="notes"<?php if ($this->_tpl_vars['features']['notes']): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_orders_with_notes_assigned'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</option>
	</select><br />
<div style="width: 70%"><?php echo $this->_tpl_vars['lng']['lbl_hold_ctrl_key']; ?>
</div>
	</td>
</tr>

<?php endif; ?>

<?php if ($this->_tpl_vars['usertype'] != 'C'): ?>

<tr>
	<td colspan="3"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_search_by_ordered_products'],'class' => 'grey')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_search_for_pattern']; ?>
:</td>
	<td width="10"><font class="CustomerMessage"></font></td>
	<td>
	<input type="text" name="posted_data[product_substring]" size="30" value="<?php echo $this->_tpl_vars['search_prefilled']['product_substring']; ?>
" style="width:70%" />
	</td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_search_in']; ?>
:</td>
	<td width="10"><font class="CustomerMessage"></font></td>
	<td>

<table cellpadding="0" cellspacing="0">
<tr>
	<td width="5"><input type="checkbox" id="posted_data_by_title" name="posted_data[by_title]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_title']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_by_title"><?php echo $this->_tpl_vars['lng']['lbl_product_title']; ?>
</label>&nbsp;&nbsp;</td>

	<td width="5"><input type="checkbox" id="posted_data_by_options" name="posted_data[by_options]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_options']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_by_options"><?php echo $this->_tpl_vars['lng']['lbl_options']; ?>
</label></td>
</tr>
</table>

	</td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
:</td>
	<td width="10"><font class="CustomerMessage"></font></td>
	<td>
	<input type="text" maxlength="64" id="posted_sku" name="posted_data[productcode]" value="<?php echo $this->_tpl_vars['search_prefilled']['productcode']; ?>
" style="width:70%" onblur="javascript: var posted_sku = $('#posted_sku').val(); reset_form('searchform', searchform_def); $('#posted_sku').val(posted_sku);" />
	</td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_productid']; ?>
#:</td>
	<td width="10"><font class="CustomerMessage"></font></td>
	<td>
	<input type="text" maxlength="64" name="posted_data[productid]" value="<?php echo $this->_tpl_vars['search_prefilled']['productid']; ?>
" style="width:70%" />
	</td>
</tr>

<tr> 
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_price']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
):</td>
	<td width="10">&nbsp;</td>
	<td>
<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type="text" size="10" maxlength="15" name="posted_data[price_min]" value="<?php if ($this->_tpl_vars['search_prefilled'] == ""):  echo $this->_tpl_vars['zero'];  else:  echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['price_min'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp));  endif; ?>" /></td>
	<td>&nbsp;-&nbsp;</td>
	<td><input type="text" size="10" maxlength="15" name="posted_data[price_max]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['price_max'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
" /></td>
</tr>
</table>
	</td>
</tr>

<?php endif; ?>

<?php if ($this->_tpl_vars['usertype'] != 'C'): ?>

<tr>
	<td colspan="3"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_search_by_customer'],'class' => 'grey')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr> 
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_customer']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td><input type="text" name="posted_data[customer]" size="30" value="<?php echo $this->_tpl_vars['search_prefilled']['customer']; ?>
" style="width:70%" /></td>
</tr>

<tr>
	<td class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_search_in']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td>
<table cellspacing="0" cellpadding="0">
<tr>
    <td width="5"><input type="checkbox" id="posted_data_by_username" name="posted_data[by_username]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_username']): ?> checked="checked"<?php endif; ?> /></td>
    <td nowrap="nowrap"><label for="posted_data_by_username"><?php echo $this->_tpl_vars['lng']['lbl_username']; ?>
</label>&nbsp;&nbsp;</td>

	<td width="5"><input type="checkbox" id="posted_data_by_firstname" name="posted_data[by_firstname]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_firstname']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_by_firstname"><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
</label>&nbsp;&nbsp;</td>

	<td width="5"><input type="checkbox" id="posted_data_by_lastname" name="posted_data[by_lastname]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_lastname']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_by_lastname"><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
</label></td>
</tr>
</table>
	</td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_search_by_address']; ?>
:</td>
	<td width="10"><font class="CustomerMessage"></font></td>
	<td>
<table cellpadding="0" cellspacing="0">
<tr>
	<td width="5"><input type="radio" id="address_type_null" name="posted_data[address_type]" value=""<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['address_type'] == ""): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate('address',true)" /></td>
	<td class="OptionLabel"><label for="address_type_null"><?php echo $this->_tpl_vars['lng']['lbl_ignore_address']; ?>
</label></td>

	<td width="5"><input type="radio" id="address_type_B" name="posted_data[address_type]" value="B"<?php if ($this->_tpl_vars['search_prefilled']['address_type'] == 'B'): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate('address',false)" /></td>
	<td class="OptionLabel"><label for="address_type_B"><?php echo $this->_tpl_vars['lng']['lbl_billing']; ?>
</label></td>

	<td width="5"><input type="radio" id="address_type_S" name="posted_data[address_type]" value="S"<?php if ($this->_tpl_vars['search_prefilled']['address_type'] == 'S'): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate('address',false)" /></td>
	<td class="OptionLabel"><label for="address_type_S"><?php echo $this->_tpl_vars['lng']['lbl_shipping']; ?>
</label></td>

	<td width="5"><input type="radio" id="address_type_both" name="posted_data[address_type]" value="Both"<?php if ($this->_tpl_vars['search_prefilled']['address_type'] == 'Both'): ?> checked="checked"<?php endif; ?> onclick="javascript:managedate('address',false)" /></td>
	<td class="OptionLabel"><label for="address_type_both"><?php echo $this->_tpl_vars['lng']['lbl_both']; ?>
</label></td>
</tr>
</table>
	</td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_city']; ?>
:</td>
	<td width="10"><font class="CustomerMessage"></font></td>
	<td><input type="text" maxlength="64" name="posted_data[city]" value="<?php echo $this->_tpl_vars['search_prefilled']['city']; ?>
" style="width:70%" /></td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>
:</td>
	<td width="10"><font class="CustomerMessage"></font></td>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/states.tpl", 'smarty_include_vars' => array('states' => $this->_tpl_vars['states'],'name' => "posted_data[state]",'default' => $this->_tpl_vars['search_prefilled']['state'],'required' => 'N','style' => "style='width:70%'")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>
:</td>
	<td width="10"><font class="CustomerMessage"></font></td>
	<td>
	<select name="posted_data[country]" style="width:70%">
		<option value="">[<?php echo $this->_tpl_vars['lng']['lbl_please_select_one']; ?>
]</option>
<?php unset($this->_sections['country_idx']);
$this->_sections['country_idx']['name'] = 'country_idx';
$this->_sections['country_idx']['loop'] = is_array($_loop=$this->_tpl_vars['countries']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['country_idx']['show'] = true;
$this->_sections['country_idx']['max'] = $this->_sections['country_idx']['loop'];
$this->_sections['country_idx']['step'] = 1;
$this->_sections['country_idx']['start'] = $this->_sections['country_idx']['step'] > 0 ? 0 : $this->_sections['country_idx']['loop']-1;
if ($this->_sections['country_idx']['show']) {
    $this->_sections['country_idx']['total'] = $this->_sections['country_idx']['loop'];
    if ($this->_sections['country_idx']['total'] == 0)
        $this->_sections['country_idx']['show'] = false;
} else
    $this->_sections['country_idx']['total'] = 0;
if ($this->_sections['country_idx']['show']):

            for ($this->_sections['country_idx']['index'] = $this->_sections['country_idx']['start'], $this->_sections['country_idx']['iteration'] = 1;
                 $this->_sections['country_idx']['iteration'] <= $this->_sections['country_idx']['total'];
                 $this->_sections['country_idx']['index'] += $this->_sections['country_idx']['step'], $this->_sections['country_idx']['iteration']++):
$this->_sections['country_idx']['rownum'] = $this->_sections['country_idx']['iteration'];
$this->_sections['country_idx']['index_prev'] = $this->_sections['country_idx']['index'] - $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['index_next'] = $this->_sections['country_idx']['index'] + $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['first']      = ($this->_sections['country_idx']['iteration'] == 1);
$this->_sections['country_idx']['last']       = ($this->_sections['country_idx']['iteration'] == $this->_sections['country_idx']['total']);
?>
		<option value="<?php echo $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']; ?>
"<?php if ($this->_tpl_vars['search_prefilled']['country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country']; ?>
</option>
<?php endfor; endif; ?>
	</select>
	</td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_zip_code']; ?>
:</td>
	<td width="10"><font class="CustomerMessage"></font></td>
	<td>
<input type="text" maxlength="32" name="posted_data[zipcode]" value="<?php echo $this->_tpl_vars['search_prefilled']['zipcode']; ?>
" style="width:70%" />
<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['address_type'] == ""): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
managedate('address',true);
-->
</script>
<?php endif; ?>
	</td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_phone']; ?>
/<?php echo $this->_tpl_vars['lng']['lbl_fax']; ?>
:</td>
	<td width="10"><font class="CustomerMessage"></font></td>
	<td><input type="text" maxlength="32" name="posted_data[phone]" value="<?php echo $this->_tpl_vars['search_prefilled']['phone']; ?>
" style="width:70%" /></td>
</tr>

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_email']; ?>
:</td>
	<td width="10">&nbsp;</td>
	<td><input type="text" maxlength="128" name="posted_data[email]" value="<?php echo $this->_tpl_vars['search_prefilled']['email']; ?>
" style="width:70%" /></td>
</tr>

<?php endif; ?>

<tr>
	<td colspan="2">&nbsp;</td>
	<td>
	<br /><br />
	<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_search'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(this, '');" />
	&nbsp;&nbsp;&nbsp;
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_reset'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: reset_form('searchform', searchform_def);" />
	</td>
</tr>

</table>

	</td>
</tr>

</table>
</form>

<?php if ($this->_tpl_vars['search_prefilled']['need_advanced_options']): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
visibleBox('1');
-->
</script>
<?php endif; ?>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_search_orders'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['mode'] == 'search'):  if ($this->_tpl_vars['total_items'] >= '1'):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_N_results_found'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['total_items']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['total_items'])); ?>
<br />
<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_displaying_X_Y_results'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'first_item', $this->_tpl_vars['first_item'], 'last_item', $this->_tpl_vars['last_item']) : smarty_modifier_substitute($_tmp, 'first_item', $this->_tpl_vars['first_item'], 'last_item', $this->_tpl_vars['last_item'])); ?>

<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_N_results_found'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', 0) : smarty_modifier_substitute($_tmp, 'items', 0)); ?>

<?php endif;  endif; ?>


<?php if ($this->_tpl_vars['orders'] != ""): ?>

<?php if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['active_modules']['Simple_Mode'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/orders_list_admin.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/orders_list.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php endif; ?>

<br />

<?php if ($this->_tpl_vars['usertype'] != 'C' && $this->_tpl_vars['mode'] != 'search' && $this->_tpl_vars['current_membership_flag'] != 'FS'): ?>

<?php ob_start(); ?>


<?php if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['active_modules']['Simple_Mode'] != ""):  echo $this->_tpl_vars['lng']['txt_delete_export_all_orders_note_admin']; ?>

<?php else:  echo $this->_tpl_vars['lng']['txt_delete_export_all_orders_note_provider']; ?>

<?php endif; ?>
<form name="ordersform" action="orders.php" method="post">
<input type="hidden" name="mode" value="" />

<table cellpadding="1" cellspacing="5">

<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_export_file_format']; ?>
:</td>
	<td>&nbsp;</td>
	<td>
	<select name="export_fmt">
		<option value="std"><?php echo $this->_tpl_vars['lng']['lbl_standart']; ?>
</option>
		<option value="csv_tab"><?php echo $this->_tpl_vars['lng']['lbl_40x_compatible']; ?>
: CSV <?php echo $this->_tpl_vars['lng']['lbl_with_tab_delimiter']; ?>
</option>
		<option value="csv_semi"><?php echo $this->_tpl_vars['lng']['lbl_40x_compatible']; ?>
: CSV <?php echo $this->_tpl_vars['lng']['lbl_with_semicolon_delimiter']; ?>
</option>
		<option value="csv_comma"><?php echo $this->_tpl_vars['lng']['lbl_40x_compatible']; ?>
: CSV <?php echo $this->_tpl_vars['lng']['lbl_with_comma_delimiter']; ?>
</option>
<?php if ($this->_tpl_vars['active_modules']['QuickBooks'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/QuickBooks/orders.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
	</select>
	</td>
	<td><input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_export_all'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(this, 'export_all');" /></td>
</tr>

<tr> 
	<td colspan="4" class="SubmitBox">
<?php if ($this->_tpl_vars['usertype'] == 'A'): ?>
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_all_orders'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (confirm('<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_delete_orders_warning'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
')) submitForm(this, 'delete_all');" />
<?php endif; ?>
	</td>
</tr>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['active_modules']['Simple_Mode'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_export_delete_orders'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_export_orders'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>


<?php if ($this->_tpl_vars['active_modules']['Order_Tracking']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/orders_tracking.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php endif; ?>
