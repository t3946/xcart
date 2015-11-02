<?php /* Smarty version 2.6.12, created on 2011-10-11 06:30:34
         compiled from main/order_info_admin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'main/order_info_admin.tpl', 40, false),array('modifier', 'price_format', 'main/order_info_admin.tpl', 49, false),array('modifier', 'trademark', 'main/order_info_admin.tpl', 59, false),array('modifier', 'substitute', 'main/order_info_admin.tpl', 84, false),array('modifier', 'escape', 'main/order_info_admin.tpl', 208, false),array('modifier', 'default', 'main/order_info_admin.tpl', 396, false),array('modifier', 'amp', 'main/order_info_admin.tpl', 406, false),)), $this); ?>
<?php func_load_lang($this, "main/order_info_admin.tpl","lbl_order_info,lbl_product,lbl_sku,lbl_price,lbl_qty,lbl_net,lbl_gst,lbl_pst,lbl_gross,lbl_remove,lbl_items,lbl_status,lbl_shipper,lbl_tracking_number,lbl_total_product_cost,lbl_discount,lbl_coupon_saving,lbl_total_shipping_cost,lbl_grand_total,lbl_add_to_order,lbl_apply_changes,lbl_apply_changes_send_email,lbl_customer_info,lbl_contact_information,lbl_company,lbl_tax_number,lbl_title,lbl_first_name,lbl_last_name,lbl_phone,lbl_fax,lbl_email,lbl_url,lbl_po_number,lbl_company_name,lbl_name_of_purchaser,lbl_position,lbl_billing_address,lbl_shipping_address,lbl_first_name,lbl_last_name,lbl_address,lbl_address_2,lbl_city,lbl_county,lbl_state,lbl_country,lbl_zip_code,lbl_first_name,lbl_last_name,lbl_address,lbl_address_2,lbl_city,lbl_county,lbl_state,lbl_country,lbl_zip_code,lbl_additional_information,lbl_apply_changes,lbl_apply_changes_send_email"); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_order_info'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "change_states_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<form action="order.php" method="post" name="ordereditform">
<input type="hidden" name="mode" value="order_edit_apply" />
<input type="hidden" name="orderid" value="<?php echo $this->_tpl_vars['order']['orderid']; ?>
" />
<input type="hidden" name="send_email" id="send_email1" value="N" />

<table cellpadding="3" cellspacing="1" width="100%">
<tr class="TableHead">
  <td width="35%"><?php echo $this->_tpl_vars['lng']['lbl_product']; ?>
</td>
  <td width="17%"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
</td>
  <td width="7%"><?php echo $this->_tpl_vars['lng']['lbl_price']; ?>
</td>
  <td width="5%"><?php echo $this->_tpl_vars['lng']['lbl_qty']; ?>
</td>
  <td width="7%"><?php echo $this->_tpl_vars['lng']['lbl_net']; ?>
</td>
  <td width="7%"><?php echo $this->_tpl_vars['lng']['lbl_gst']; ?>
</td>
  <td width="7%"><?php echo $this->_tpl_vars['lng']['lbl_pst']; ?>
</td>
  <td width="7%"><?php echo $this->_tpl_vars['lng']['lbl_gross']; ?>
</td>
  <?php if (! $this->_tpl_vars['static']): ?><td width="5%"><?php echo $this->_tpl_vars['lng']['lbl_remove'];  else: ?><td>&nbsp;<?php endif; ?></td>
</tr>

<?php $_from = $this->_tpl_vars['order']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m_id'] => $this->_tpl_vars['v']):
?>
<tr class="distributor-totals-line">
  <td><?php echo $this->_tpl_vars['v']['group_name']; ?>
 <?php echo $this->_tpl_vars['lng']['lbl_items']; ?>
</td>
  <td><?php echo $this->_tpl_vars['v']['code']; ?>
</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['total']['net'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['total']['gst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['total']['pst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['total']['gross'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td>&nbsp;</td>
</tr>
<?php $_from = $this->_tpl_vars['v']['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['prod_num'] => $this->_tpl_vars['product']):
?>
<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'",'name' => "cycle_".($this->_tpl_vars['m_id'])), $this);?>
>
  <td><a href="<?php echo $this->_tpl_vars['product']['links']['customer']; ?>
&cat=<?php echo $this->_tpl_vars['cats'][$this->_tpl_vars['product']['productid']]; ?>
" title="" target="_blank"><?php echo $this->_tpl_vars['product']['product']; ?>
</a></td>
  <td>
    <?php if ($this->_tpl_vars['current_membership_flag'] != 'FS'): ?>
      <a href="<?php echo $this->_tpl_vars['product']['links']['admin']; ?>
" title="" target="_blank"><?php echo $this->_tpl_vars['product']['productcode']; ?>
</a>
    <?php else: ?>
      <?php echo $this->_tpl_vars['product']['productcode']; ?>

    <?php endif; ?>
  </td>
  <td align="right"><?php if (! $this->_tpl_vars['static']): ?><input type="text" size="8" name="items[<?php echo $this->_tpl_vars['product']['itemid']; ?>
][price]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['price'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp)); ?>
" /><?php else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => ((is_array($_tmp=$this->_tpl_vars['product']['price'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?></td>
  <td align="right"><?php if (! $this->_tpl_vars['static']): ?><input type="text" size="5" name="items[<?php echo $this->_tpl_vars['product']['itemid']; ?>
][amount]" value="<?php echo $this->_tpl_vars['product']['amount']; ?>
" /><?php else:  echo $this->_tpl_vars['product']['amount'];  endif; ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['product']['price']*$this->_tpl_vars['product']['amount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['product']['extra_data']['taxes']['GST']['tax_value']+$this->_tpl_vars['product']['extra_data']['taxes']['HST']['tax_value'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['product']['extra_data']['taxes']['PST']['tax_value'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['product']['display_subtotal'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="center"><?php if (! $this->_tpl_vars['static']): ?><input type="checkbox" value="Y" name="items[<?php echo $this->_tpl_vars['product']['itemid']; ?>
][delete]" /><?php else: ?>&nbsp;<?php endif; ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'",'name' => "cycle_".($this->_tpl_vars['m_id'])), $this);?>
>
  <td colspan="4"><?php if (! $this->_tpl_vars['static']): ?><input type="text" maxlength="255" name="groups[<?php echo $this->_tpl_vars['m_id']; ?>
][shipping]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['shipping'])) ? $this->_run_mod_handler('trademark', true, $_tmp, '') : smarty_modifier_trademark($_tmp, '')); ?>
" style="width: 99%;" /><?php else:  echo $this->_tpl_vars['v']['shipping'];  endif; ?></td>
  <td align="right"><?php if (! $this->_tpl_vars['static']): ?><input type="text" size="8" name="groups[<?php echo $this->_tpl_vars['m_id']; ?>
][shipping_cost_net]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['shipping_cost']['net'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp)); ?>
" /><?php else:  echo ((is_array($_tmp=$this->_tpl_vars['v']['shipping_cost']['net'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp));  endif; ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['shipping_cost']['gst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['shipping_cost']['pst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['shipping_cost']['gross'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td>&nbsp;</td>
</tr>
<tr>
<td colspan="9">
<script type="text/javascript">
<!--
multirowInputSets['track_<?php echo $this->_tpl_vars['m_id']; ?>
'] = [];
multirowInputSets['track_<?php echo $this->_tpl_vars['m_id']; ?>
'].noCloneContent = 1;
-->
</script>
<?php echo $this->_tpl_vars['lng']['lbl_status']; ?>
:
<table cellpadding="0" cellspacing="0" border="0">
<?php if ($this->_tpl_vars['active_modules']['Google_Checkout'] == '' || $this->_tpl_vars['order']['extra']['goid'] == ''): ?>
<tr>
	<td style="vertical-align: top; padding-right: 10px; padding-bottom: 4px;">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/order_status.tpl", 'smarty_include_vars' => array('status' => $this->_tpl_vars['v']['status'],'mode' => 'select','name' => "groups[".($this->_tpl_vars['m_id'])."][status]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<td colspan="2" style="padding-bottom: 4px;">
<?php if ($this->_tpl_vars['v']['tracking']):  $_from = $this->_tpl_vars['v']['tracking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
 if ($this->_tpl_vars['t']['tracknum'] != ""): ?>
<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['tracking_links'][$this->_tpl_vars['t']['linkid']]['link'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'tracknum', $this->_tpl_vars['t']['tracknum']) : smarty_modifier_substitute($_tmp, 'tracknum', $this->_tpl_vars['t']['tracknum'])); ?>
" target="_blank"><?php echo $this->_tpl_vars['tracking_links'][$this->_tpl_vars['t']['linkid']]['shipping']; ?>
: <?php echo $this->_tpl_vars['t']['tracknum']; ?>
</a>
<?php else:  echo $this->_tpl_vars['tracking_links'][$this->_tpl_vars['t']['linkid']]['shipping']; ?>
: <?php echo $this->_tpl_vars['tracking_links'][$this->_tpl_vars['t']['linkid']]['link']; ?>

<?php endif; ?>
<br />
<?php endforeach; endif; unset($_from);  endif; ?>
	</td>
</tr>
<?php endif; ?>
<tr>
	<td style="padding-right: 10px;"><?php echo $this->_tpl_vars['lng']['lbl_shipper']; ?>
:</td>
	<td colspan="2"><?php echo $this->_tpl_vars['lng']['lbl_tracking_number']; ?>
:</td>
</tr>

<tr id="track_<?php echo $this->_tpl_vars['m_id']; ?>
_tr">
	<td id="track_<?php echo $this->_tpl_vars['m_id']; ?>
_box_1" style="padding-right: 10px;">
	<select name="groups[<?php echo $this->_tpl_vars['m_id']; ?>
][tracking_shipper][0]">
	<option value=""></option>
<?php $_from = $this->_tpl_vars['tracking_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['linkid'] => $this->_tpl_vars['v']):
?>
	<option value="<?php echo $this->_tpl_vars['linkid']; ?>
"><?php echo $this->_tpl_vars['v']['shipping']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
	</select>
	</td>
	<td id="track_<?php echo $this->_tpl_vars['m_id']; ?>
_box_2" style="padding-right: 5px;">
	<input type="text" name="groups[<?php echo $this->_tpl_vars['m_id']; ?>
][tracking_number][0]" value="" size="40" />
	</td>
	<td width="50%"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/multirow_add.tpl", 'smarty_include_vars' => array('mark' => "track_".($this->_tpl_vars['m_id']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

</table>

</td>
</tr>
<tr><td colspan="9"><hr /></td></tr>
<?php endforeach; endif; unset($_from); ?>

<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'",'name' => 'cycle_totals'), $this);?>
>
  <td><?php echo $this->_tpl_vars['lng']['lbl_total_product_cost']; ?>
</td>
  <td colspan="3">&nbsp;</td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['extra']['product_total']['net'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['extra']['product_total']['gst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['extra']['product_total']['pst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['display_subtotal'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td>&nbsp;</td>
</tr>

<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'",'name' => 'cycle_totals'), $this);?>
>
  <td><?php echo $this->_tpl_vars['lng']['lbl_discount']; ?>
</td>
  <td colspan="3">&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['discount'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td>&nbsp;</td>
</tr>

<?php ob_start(); ?>
<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'",'name' => 'cycle_totals'), $this);?>
>
  <td><?php echo $this->_tpl_vars['lng']['lbl_coupon_saving']; ?>
</td>
  <td colspan="3">&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['coupon_discount'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['order']['coupon_discount'] > 0): ?> (<?php echo $this->_tpl_vars['order']['coupon']; ?>
)<?php endif; ?></td>
  <td>&nbsp;</td>
</tr>
<?php $this->_smarty_vars['capture']['coup_saving'] = ob_get_contents(); ob_end_clean(); ?>

<?php if ($this->_tpl_vars['order']['coupon_type'] != 'free_ship'):  echo $this->_smarty_vars['capture']['coup_saving']; ?>

<?php endif; ?>

<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'",'name' => 'cycle_totals'), $this);?>
>
  <td><?php echo $this->_tpl_vars['lng']['lbl_total_shipping_cost']; ?>
</td>
  <td colspan="3">&nbsp;</td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['extra']['shipping_total']['net'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['extra']['shipping_total']['gst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['extra']['shipping_total']['pst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['shipping_cost'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td>&nbsp;</td>
</tr>

<?php if ($this->_tpl_vars['order']['coupon'] && $this->_tpl_vars['order']['coupon_type'] == 'free_ship'):  echo $this->_smarty_vars['capture']['coup_saving']; ?>

<?php endif; ?>

<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'",'name' => 'cycle_totals'), $this);?>
 style="font-weight: bold;">
  <td><?php echo $this->_tpl_vars['lng']['lbl_grand_total']; ?>
</td>
  <td colspan="3">&nbsp;</td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['extra']['total']['net'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['extra']['total']['gst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['extra']['total']['pst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['total'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td>&nbsp;</td>
</tr>

<tr>
  <td colspan="9">
  <hr />
<?php if (! $this->_tpl_vars['static']): ?>
<script type="text/javascript">
<!--
multirowInputSets['add_to_order'] = [];
multirowInputSets['add_to_order'].noCloneContent = 1;
multirowInputSets['add_to_order'].noCloneHTMLId = 'add_to_order_box_0';
-->
</script>
<?php endif; ?>
  </td>
</tr>

<?php if (! $this->_tpl_vars['static']): ?>
<tr id="add_to_order_tr">
  <td id="add_to_order_box_0"><strong><?php echo $this->_tpl_vars['lng']['lbl_add_to_order']; ?>
:</strong></td>
  <td id="add_to_order_box_1" colspan="2"><input type="text" name="add_productcode[0]" value="" size="16" style="width: 100%;" /></td>
  <td id="add_to_order_box_2"><input type="text" name="add_amount[0]" value="" size="5" /></td>
  <td colspan="6"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/multirow_add.tpl", 'smarty_include_vars' => array('mark' => 'add_to_order')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php endif; ?>

</table>

<br />
<input type="submit" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply_changes'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php if ($this->_tpl_vars['current_membership_flag'] != 'FS'): ?>
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply_changes_send_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: $('#send_email1').val('Y'); this.form.submit();" />
<?php endif; ?>
<br />

</form>

<form action="order.php" method="post" name="ordereditform">
<input type="hidden" name="mode" value="order_edit_apply" />
<input type="hidden" name="orderid" value="<?php echo $this->_tpl_vars['order']['orderid']; ?>
" />
<input type="hidden" name="send_email" id="send_email2" value="N" />

<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_customer_info'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
  <td width="47%" height="25"><b><?php echo $this->_tpl_vars['lng']['lbl_contact_information']; ?>
</b></td>
  <td width="5%">&nbsp;</td>
  <td width="47%" height="25"></td>
</tr>
<tr>
  <td bgcolor="#000000" height="2"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer_black.gif" width="100%" alt="" /></td>
  <td><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" /></td>
  <td></td>
</tr>
<tr>
  <td colspan="3"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
  <td width="47%">
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">
<?php if ($this->_tpl_vars['customer']['default_fields']['company']): ?>
<tr>
  <td><b><?php echo $this->_tpl_vars['lng']['lbl_company']; ?>
:</b></td>
  <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[company]" value="<?php echo $this->_tpl_vars['customer']['company']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['company'];  endif; ?></td>
</tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['tax_number']): ?>
<tr>
  <td><b><?php echo $this->_tpl_vars['lng']['lbl_tax_number']; ?>
:</b></td>
  <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[tax_number]" value="<?php echo $this->_tpl_vars['customer']['tax_number']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['tax_number'];  endif; ?></td>
</tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['title']): ?>
<tr> 
<td><b><?php echo $this->_tpl_vars['lng']['lbl_title']; ?>
:</b></td>
<td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[title]" value="<?php echo $this->_tpl_vars['customer']['title']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['title'];  endif; ?></td>
</tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['firstname']): ?>
<tr>
  <td nowrap="nowrap"><b><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
:</b></td>
  <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[firstname]" value="<?php echo $this->_tpl_vars['customer']['firstname']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['firstname'];  endif; ?></td>
</tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['lastname']): ?>
<tr>
  <td nowrap="nowrap"><b><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
:</b></td>
  <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[lastname]" value="<?php echo $this->_tpl_vars['customer']['lastname']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['lastname'];  endif; ?></td>
</tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['phone']): ?>
<tr>
  <td><b><?php echo $this->_tpl_vars['lng']['lbl_phone']; ?>
:</b></td>
  <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[phone]" value="<?php echo $this->_tpl_vars['customer']['phone']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['phone'];  endif; ?></td>
</tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['fax']): ?>
<tr>
  <td><b><?php echo $this->_tpl_vars['lng']['lbl_fax']; ?>
:</b></td>
  <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[fax]" value="<?php echo $this->_tpl_vars['customer']['fax']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['fax'];  endif; ?></td>
</tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['email']): ?>
<tr>
  <td><b><?php echo $this->_tpl_vars['lng']['lbl_email']; ?>
:</b></td>
  <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[email]" value="<?php echo $this->_tpl_vars['customer']['email']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['email'];  endif; ?></td>
</tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['url']): ?>
<tr>
  <td><b><?php echo $this->_tpl_vars['lng']['lbl_url']; ?>
:</b></td>
  <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[url]" value="<?php echo $this->_tpl_vars['customer']['url']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['url'];  endif; ?></td>
</tr>
<?php endif;  $_from = $this->_tpl_vars['customer']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'C' || $this->_tpl_vars['v']['section'] == 'P'): ?>
<tr>
  <td><b><?php echo $this->_tpl_vars['v']['title']; ?>
:</b></td>
      <td><?php echo $this->_tpl_vars['v']['value']; ?>
</td>
</tr>
<?php endif;  endforeach; endif; unset($_from); ?>
</table>
  </td>
  <td width="5%">&nbsp;</td>
  <td width="47%" style="vertical-align: top;">
  <?php if ($this->_tpl_vars['order']['po_details']): ?>
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_po_number']; ?>
:</b> </td>
    <td><?php echo $this->_tpl_vars['order']['po_details']['po_number']; ?>
</td>
  </tr>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_company_name']; ?>
:</b> </td>
    <td><?php echo $this->_tpl_vars['order']['po_details']['company_name']; ?>
</td>
  </tr>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_name_of_purchaser']; ?>
:</b> </td>
    <td><?php echo $this->_tpl_vars['order']['po_details']['name_of_purchaser']; ?>
</td>
  </tr>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_position']; ?>
:</b> </td>
    <td><?php echo $this->_tpl_vars['order']['po_details']['position']; ?>
</td>
  </tr>
  </table>
  <?php endif; ?>
  </td>
</tr>
</table>
<br />
<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
  <td width="47%" height="25"><b><?php echo $this->_tpl_vars['lng']['lbl_billing_address']; ?>
</b></td>
  <td width="5%">&nbsp;</td>
  <td width="47%" height="25"><b><?php echo $this->_tpl_vars['lng']['lbl_shipping_address']; ?>
</b></td>
</tr>
<tr>
  <td bgcolor="#000000" height="2"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer_black.gif" width="100%" alt="" /></td>
  <td><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" /></td>
  <td bgcolor="#000000" height="2"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer_black.gif" width="100%" alt="" /></td>
</tr>
<tr>
  <td colspan="3"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
  <td>
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">
<?php if ($this->_tpl_vars['customer']['default_fields']['b_firstname']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[b_firstname]" value="<?php echo $this->_tpl_vars['customer']['b_firstname']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['b_firstname'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['b_lastname']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[b_lastname]" value="<?php echo $this->_tpl_vars['customer']['b_lastname']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['b_lastname'];  endif; ?></td>
  </tr>
<?php endif;  $_from = $this->_tpl_vars['customer']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'B'): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['v']['title']; ?>
:</b></td>
        <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="additional_fields[<?php echo $this->_tpl_vars['v']['fieldid']; ?>
]" value="<?php echo $this->_tpl_vars['v']['value']; ?>
" /><?php else:  echo $this->_tpl_vars['v']['value'];  endif; ?></td>
  </tr>
<?php endif;  endforeach; endif; unset($_from);  if ($this->_tpl_vars['customer']['default_fields']['b_address']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_address']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[b_address]" value="<?php echo $this->_tpl_vars['customer']['b_address']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['b_address'];  endif; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap"><b><?php echo $this->_tpl_vars['lng']['lbl_address_2']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[b_address_2]" value="<?php echo $this->_tpl_vars['customer']['b_address_2']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['b_address_2'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['b_city']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_city']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[b_city]" value="<?php echo $this->_tpl_vars['customer']['b_city']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['b_city'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['b_county'] && $this->_tpl_vars['config']['General']['use_counties'] == 'Y'): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_county']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[b_county]" id="customer_info_b_county" value="<?php echo $this->_tpl_vars['customer']['b_county']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['b_countyname'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['b_state']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/states.tpl", 'smarty_include_vars' => array('states' => $this->_tpl_vars['states'],'name' => "customer_info[b_state]",'default' => $this->_tpl_vars['customer']['b_state'],'default_country' => ((is_array($_tmp=@$this->_tpl_vars['customer']['b_country'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['config']['General']['default_country']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['config']['General']['default_country'])),'country_name' => "customer_info[b_country]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  echo $this->_tpl_vars['customer']['b_statename'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['b_country']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?>
<select name="customer_info[b_country]" id="customer_info_b_country" size="1">
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
"<?php if ($this->_tpl_vars['customer']['b_country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']): ?> selected="selected"<?php elseif ($this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code'] == $this->_tpl_vars['config']['General']['default_country'] && $this->_tpl_vars['customer']['b_country'] == ""): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
</option>
<?php endfor; endif; ?>
</select>
<?php if ($this->_tpl_vars['customer']['default_fields']['b_state']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_states.tpl", 'smarty_include_vars' => array('state_name' => "customer_info[b_state]",'country_name' => "customer_info[b_country]",'county_name' => "customer_info[b_county]",'state_value' => $this->_tpl_vars['customer']['b_state'],'county_value' => $this->_tpl_vars['customer']['b_county'],'country_id' => 'customer_info_b_country')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  else:  echo $this->_tpl_vars['customer']['b_countryname'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['b_zipcode']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_zip_code']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[b_zipcode]" value="<?php echo $this->_tpl_vars['customer']['b_zipcode']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['b_zipcode'];  endif; ?></td>
  </tr>
<?php endif; ?>
  </table>
  </td>
  <td>&nbsp;</td>
  <td>
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">
<?php if ($this->_tpl_vars['customer']['default_fields']['s_firstname']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[s_firstname]" value="<?php echo $this->_tpl_vars['customer']['s_firstname']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['s_firstname'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['s_lastname']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[s_lastname]" value="<?php echo $this->_tpl_vars['customer']['s_lastname']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['s_lastname'];  endif; ?></td>
  </tr>
<?php endif;  $_from = $this->_tpl_vars['customer']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'S'): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['v']['title']; ?>
:</b></td>
        <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="additional_fields[<?php echo $this->_tpl_vars['v']['fieldid']; ?>
]" value="<?php echo $this->_tpl_vars['v']['value']; ?>
" /><?php else:  echo $this->_tpl_vars['v']['value'];  endif; ?></td>
  </tr>
<?php endif;  endforeach; endif; unset($_from);  if ($this->_tpl_vars['customer']['default_fields']['s_address']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_address']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[s_address]" value="<?php echo $this->_tpl_vars['customer']['s_address']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['s_address'];  endif; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap"><b><?php echo $this->_tpl_vars['lng']['lbl_address_2']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[s_address_2]" value="<?php echo $this->_tpl_vars['order']['s_address_2']; ?>
" /><?php else:  echo $this->_tpl_vars['order']['s_address_2'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['s_city']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_city']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[s_city]" value="<?php echo $this->_tpl_vars['customer']['s_city']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['s_city'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['s_county'] && $this->_tpl_vars['config']['General']['use_counties'] == 'Y'): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_county']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[s_county]" value="<?php echo $this->_tpl_vars['customer']['s_county']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['s_countyname'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['s_state']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/states.tpl", 'smarty_include_vars' => array('states' => $this->_tpl_vars['states'],'name' => "customer_info[s_state]",'default' => $this->_tpl_vars['customer']['s_state'],'default_country' => ((is_array($_tmp=@$this->_tpl_vars['customer']['s_country'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['config']['General']['default_country']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['config']['General']['default_country'])),'country_name' => "customer_info[s_country]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  echo $this->_tpl_vars['customer']['s_statename'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['s_country']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?>
<select name="customer_info[s_country]" id="customer_info_s_country" size="1">
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
"<?php if ($this->_tpl_vars['customer']['s_country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']): ?> selected="selected"<?php elseif ($this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code'] == $this->_tpl_vars['config']['General']['default_country'] && $this->_tpl_vars['customer']['s_country'] == ""): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
</option>
<?php endfor; endif;  if ($this->_tpl_vars['customer']['default_fields']['s_state']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_states.tpl", 'smarty_include_vars' => array('state_name' => "customer_info[s_state]",'country_name' => "customer_info[s_country]",'county_name' => "customer_info[s_county]",'state_value' => $this->_tpl_vars['customer']['s_state'],'county_value' => $this->_tpl_vars['customer']['s_county'],'country_id' => 'customer_info_s_country')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</select>
<?php else:  echo $this->_tpl_vars['customer']['s_countryname'];  endif; ?></td>
  </tr>
<?php endif;  if ($this->_tpl_vars['customer']['default_fields']['s_zipcode']): ?>
  <tr>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_zip_code']; ?>
:</b> </td>
    <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="customer_info[s_zipcode]" value="<?php echo $this->_tpl_vars['customer']['s_zipcode']; ?>
" /><?php else:  echo $this->_tpl_vars['customer']['s_zipcode'];  endif; ?></td>
  </tr>
<?php endif; ?>
  </table>
      </td>
</tr>

<?php $this->assign('is_header', "");  $_from = $this->_tpl_vars['customer']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'A'):  if ($this->_tpl_vars['is_header'] == ''): ?>
<tr>
<td colspan="3">&nbsp;</td>
</tr>
<tr>
<td width="45%" height="25"><b><?php echo $this->_tpl_vars['lng']['lbl_additional_information']; ?>
</b></td>
<td colspan="2" width="55%">&nbsp;</td>
</tr>
<tr>
<td bgcolor="#000000" height="2"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer_black.gif" width="100%" alt="" /></td>
<td colspan="2" width="55%"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
<td colspan="3"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
<td><table cellspacing="0" cellpadding="0" width="100%" border="0">
<?php $this->assign('is_header', 'E');  endif; ?>
<tr valign="top">
<td><b><?php echo $this->_tpl_vars['v']['title']; ?>
</b></td>
  <td width="100%"><?php if (! $this->_tpl_vars['static']): ?><input type="text" name="additional_fields[<?php echo $this->_tpl_vars['v']['fieldid']; ?>
]" value="<?php echo $this->_tpl_vars['v']['value']; ?>
" /><?php else:  echo $this->_tpl_vars['v']['value'];  endif; ?></td>
</tr>
<?php endif;  endforeach; endif; unset($_from);  if ($this->_tpl_vars['is_header'] == 'E'): ?>
</table></td>
<td colspan="2" width="55%">&nbsp;</td>
</tr>
<?php endif; ?>

</table>

<br />
<?php if (! $this->_tpl_vars['static']): ?>
<input type="submit" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply_changes'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php if ($this->_tpl_vars['current_membership_flag'] != 'FS'): ?>
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply_changes_send_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: $('#send_email2').val('Y'); this.form.submit();" />
<?php endif; ?>
<br />
<?php endif; ?>

</form>

<br />