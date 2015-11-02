<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:28
         compiled from mail/html/order_data.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'trademark', 'mail/html/order_data.tpl', 25, false),array('modifier', 'formatprice', 'mail/html/order_data.tpl', 52, false),array('modifier', 'substitute', 'mail/html/order_data.tpl', 68, false),array('modifier', 'capitalize', 'mail/html/order_data.tpl', 187, false),array('function', 'math', 'mail/html/order_data.tpl', 59, false),)), $this); ?>
<?php func_load_lang($this, "mail/html/order_data.tpl","lbl_products_ordered,lbl_sku,lbl_product,lbl_tax,lbl_item_price,lbl_qty,lbl_total,lbl_items,lbl_delivery_by,lbl_options,lbl_download,eml_order_shipped,lbl_tracking_number_is,eml_order_shipped_nolink,lbl_gift_certificate,lbl_gc_send_via_postal_mail,lbl_mail_address,lbl_phone,lbl_recipient_email,lbl_total,lbl_discount,lbl_coupon_saving,lbl_discounted_total,lbl_total_shipping_cost,lbl_coupon_saving,lbl_payment_method_surcharge,lbl_payment_method_discount,lbl_giftcert_discount,lbl_grand_total,lbl_including_tax,txt_tax_exemption_applied,lbl_applied_giftcerts,lbl_giftcert_ID,lbl_giftcert_cost"); ?><table cellspacing="0" cellpadding="0" width="100%" border="0">

<tr>
<td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;"><?php echo $this->_tpl_vars['lng']['lbl_products_ordered']; ?>
</font></td>
</tr>

</table>

<table cellspacing="0" cellpadding="3" width="100%" border="1">

<tr>
<th width="60" bgcolor="#cccccc"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
</th>
<th bgcolor="#cccccc"><?php echo $this->_tpl_vars['lng']['lbl_product']; ?>
</th>
<?php if ($this->_tpl_vars['order']['extra']['tax_info']['display_cart_products_tax_rates'] == 'Y' && $this->_tpl_vars['_userinfo']['tax_exempt'] != 'Y'): ?>
<th nowrap="nowrap" width="100" bgcolor="#cccccc"><?php if ($this->_tpl_vars['order']['extra']['tax_info']['product_tax_name'] != ""):  echo $this->_tpl_vars['order']['extra']['tax_info']['product_tax_name'];  else:  echo $this->_tpl_vars['lng']['lbl_tax'];  endif; ?></th>
<?php endif; ?>
<th nowrap="nowrap" width="100" bgcolor="#cccccc" align="center"><?php echo $this->_tpl_vars['lng']['lbl_item_price']; ?>
</th>
<th width="60" bgcolor="#cccccc"><?php echo $this->_tpl_vars['lng']['lbl_qty']; ?>
</th>
<th width="60" bgcolor="#cccccc"><?php echo $this->_tpl_vars['lng']['lbl_total']; ?>
<br />  </th>
</tr>
<?php $_from = $this->_tpl_vars['order']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['shgrform'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['shgrform']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
        $this->_foreach['shgrform']['iteration']++;
?>
<tr>
<td colspan="6">
<b><?php echo $this->_tpl_vars['v']['group_name']; ?>
 <?php echo $this->_tpl_vars['lng']['lbl_items']; ?>
 (<?php echo $this->_tpl_vars['lng']['lbl_delivery_by']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['v']['shipping'])) ? $this->_run_mod_handler('trademark', true, $_tmp, '') : smarty_modifier_trademark($_tmp, '')); ?>
, <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['shipping_cost']['gross'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>):</b>
</td>
</tr>
<?php $_from = $this->_tpl_vars['v']['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
<tr>
<td align="center"><?php echo $this->_tpl_vars['product']['productcode']; ?>
</td>
<td><font style="FONT-SIZE: 11px"><?php echo $this->_tpl_vars['product']['product']; ?>
</font>
<?php if ($this->_tpl_vars['product']['product_options'] != '' && $this->_tpl_vars['active_modules']['Product_Options']): ?>
<table>

<tr>
<td valign="top"><b><?php echo $this->_tpl_vars['lng']['lbl_options']; ?>
:</b></td> 
<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/display_options.tpl", 'smarty_include_vars' => array('options' => $this->_tpl_vars['product']['product_options'],'options_txt' => $this->_tpl_vars['product']['product_options_txt'],'force_product_options_txt' => $this->_tpl_vars['product']['force_product_options_txt'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

</table>
<?php endif;  if ($this->_tpl_vars['active_modules']['Egoods'] && $this->_tpl_vars['product']['download_key'] && ( $this->_tpl_vars['order']['status'] == 'P' || $this->_tpl_vars['order']['status'] == 'C' )): ?>
<br />
<a href="<?php echo $this->_tpl_vars['catalogs']['customer']; ?>
/download.php?id=<?php echo $this->_tpl_vars['product']['download_key']; ?>
" class="SmallNote" target="_blank"><?php echo $this->_tpl_vars['lng']['lbl_download']; ?>
</a>
<?php endif; ?>
</td>
<?php if ($this->_tpl_vars['order']['extra']['tax_info']['display_cart_products_tax_rates'] == 'Y' && $this->_tpl_vars['_userinfo']['tax_exempt'] != 'Y'): ?>
<td align="center">
<?php $_from = $this->_tpl_vars['product']['extra_data']['taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
 if ($this->_tpl_vars['tax']['tax_value'] > 0):  if ($this->_tpl_vars['order']['extra']['tax_info']['product_tax_name'] == ""):  echo $this->_tpl_vars['tax']['tax_display_name']; ?>
 <?php endif;  if ($this->_tpl_vars['tax']['rate_type'] == "%"):  echo ((is_array($_tmp=$this->_tpl_vars['tax']['rate_value'])) ? $this->_run_mod_handler('formatprice', true, $_tmp, false, false, 1) : smarty_modifier_formatprice($_tmp, false, false, 1)); ?>
%<?php else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['tax']['rate_value'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?><br />
<?php endif;  endforeach; endif; unset($_from); ?>
</td>
<?php endif; ?>
<td align="right" nowrap="nowrap"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['product']['display_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;</td>
<td align="center"><?php echo $this->_tpl_vars['product']['amount']; ?>
</td>
<td align="right" nowrap="nowrap"><?php echo smarty_function_math(array('assign' => 'total','equation' => "amount*price",'amount' => $this->_tpl_vars['product']['amount'],'price' => $this->_tpl_vars['product']['display_price']), $this); $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['total'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;</td>
</tr>
<?php endforeach; endif; unset($_from);  if ($this->_tpl_vars['show_shipping_groups'] == 'Y'): ?>
    <?php if ($this->_tpl_vars['v']['tracking']): ?>
        <?php $_from = $this->_tpl_vars['v']['tracking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tr']):
?>
            <tr>
                <td colspan="6" style="padding: 10px;">
                    <?php if ($this->_tpl_vars['tr']['tracknum'] != ""): ?>
                        <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['eml_order_shipped'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'shipper', $this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['shipping']) : smarty_modifier_substitute($_tmp, 'shipper', $this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['shipping'])))) ? $this->_run_mod_handler('substitute', true, $_tmp, 'distributor', $this->_tpl_vars['v']['group_name']) : smarty_modifier_substitute($_tmp, 'distributor', $this->_tpl_vars['v']['group_name'])); ?>
<br />
                        <?php echo $this->_tpl_vars['lng']['lbl_tracking_number_is']; ?>
 <?php echo $this->_tpl_vars['tr']['tracknum']; ?>
<br />
                        <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['link'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'tracknum', $this->_tpl_vars['tr']['tracknum']) : smarty_modifier_substitute($_tmp, 'tracknum', $this->_tpl_vars['tr']['tracknum'])); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['link'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'tracknum', $this->_tpl_vars['tr']['tracknum']) : smarty_modifier_substitute($_tmp, 'tracknum', $this->_tpl_vars['tr']['tracknum'])); ?>
</a>
                    <?php else: ?>
                        <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['eml_order_shipped_nolink'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'shipper', $this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['shipping']) : smarty_modifier_substitute($_tmp, 'shipper', $this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['shipping'])))) ? $this->_run_mod_handler('substitute', true, $_tmp, 'distributor', $this->_tpl_vars['v']['group_name']) : smarty_modifier_substitute($_tmp, 'distributor', $this->_tpl_vars['v']['group_name'])); ?>
<br />
                        <?php echo $this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['link']; ?>

                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>
    <?php if (! ($this->_foreach['shgrform']['iteration'] == $this->_foreach['shgrform']['total'])): ?>
        <tr>
            <td colspan="6" style="border: none;">&nbsp;</td>
        </tr>
    <?php endif;  endif;  endforeach; endif; unset($_from); ?>

<?php if ($this->_tpl_vars['giftcerts'] != ''):  $_from = $this->_tpl_vars['giftcerts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gc']):
?>
<tr>
	<td>&nbsp;</td>
	<td nowrap="nowrap">
<?php echo $this->_tpl_vars['lng']['lbl_gift_certificate']; ?>
: <?php echo $this->_tpl_vars['gc']['gcid']; ?>
<br />
<div style="padding-left: 10px; white-space: nowrap;">
<?php if ($this->_tpl_vars['gc']['send_via'] == 'P'):  echo $this->_tpl_vars['lng']['lbl_gc_send_via_postal_mail']; ?>
<br />
<?php echo $this->_tpl_vars['lng']['lbl_mail_address']; ?>
: <?php echo $this->_tpl_vars['gc']['recipient_firstname']; ?>
 <?php echo $this->_tpl_vars['gc']['recipient_lastname']; ?>
<br />
<?php echo $this->_tpl_vars['gc']['recipient_address']; ?>
, <?php echo $this->_tpl_vars['gc']['recipient_city']; ?>
,<br />
<?php if ($this->_tpl_vars['gc']['recipient_countyname'] != ''):  echo $this->_tpl_vars['gc']['recipient_countyname']; ?>
 <?php endif;  echo $this->_tpl_vars['gc']['recipient_state']; ?>
 <?php echo $this->_tpl_vars['gc']['recipient_country']; ?>
, <?php echo $this->_tpl_vars['gc']['recipient_zipcode']; ?>
<br />
<?php echo $this->_tpl_vars['lng']['lbl_phone']; ?>
: <?php echo $this->_tpl_vars['gc']['recipient_phone']; ?>

<?php else:  echo $this->_tpl_vars['lng']['lbl_recipient_email']; ?>
: <?php echo $this->_tpl_vars['gc']['recipient_email']; ?>

<?php endif; ?>
</div>
	</td>
<?php if ($this->_tpl_vars['order']['extra']['tax_info']['display_cart_products_tax_rates'] == 'Y' && $this->_tpl_vars['_userinfo']['tax_exempt'] != 'Y'): ?>
	<td align="center">&nbsp;-&nbsp;</td>
<?php endif; ?>
	<td align="right" nowrap="nowrap"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['gc']['amount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;</td>
	<td align="center">1</td>
	<td align="right" nowrap="nowrap"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['gc']['amount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;</td>
</tr>
<?php endforeach; endif; unset($_from);  endif; ?>

</table>
<table cellspacing="0" cellpadding="0" width="100%" border="0">

<tr>
<td align="right" width="100%" height="20"><b><?php echo $this->_tpl_vars['lng']['lbl_total']; ?>
:</b>&nbsp;</td>
<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['display_subtotal'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;&nbsp;</td>
</tr>

<?php if ($this->_tpl_vars['order']['discount'] > 0): ?>
<tr>
<td align="right" height="20"><b><?php echo $this->_tpl_vars['lng']['lbl_discount']; ?>
:</b>&nbsp;</td>
<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;&nbsp;</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['order']['coupon'] && $this->_tpl_vars['order']['coupon_type'] != 'free_ship'): ?>
<tr>
<td align="right" height="20"><b><?php echo $this->_tpl_vars['lng']['lbl_coupon_saving']; ?>
:</b>&nbsp;</td>
<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['coupon_discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;&nbsp;</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['order']['discounted_subtotal'] != $this->_tpl_vars['order']['subtotal']): ?>
<tr>
<td align="right" width="100%" height="20"><b><?php echo $this->_tpl_vars['lng']['lbl_discounted_total']; ?>
:</b>&nbsp;</td>
<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['display_discounted_subtotal'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;&nbsp;</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['config']['Shipping']['disable_shipping'] != 'Y'): ?>
<tr>
<td align="right" height="20"><b><?php echo $this->_tpl_vars['lng']['lbl_total_shipping_cost']; ?>
:</b>&nbsp;</td>
<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['display_shipping_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;&nbsp;</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['order']['coupon'] && $this->_tpl_vars['order']['coupon_type'] == 'free_ship'): ?>
<tr>
<td align="right" height="20"><b><?php echo $this->_tpl_vars['lng']['lbl_coupon_saving']; ?>
:</b>&nbsp;</td>
<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['coupon_discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;&nbsp;</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['order']['applied_taxes'] && $this->_tpl_vars['order']['extra']['tax_info']['display_taxed_order_totals'] != 'Y'):  $_from = $this->_tpl_vars['order']['applied_taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
?>
<tr>
<td align="right" width="100%" height="20"><b><?php echo $this->_tpl_vars['tax']['tax_display_name'];  if ($this->_tpl_vars['tax']['rate_type'] == "%"): ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['tax']['rate_value'])) ? $this->_run_mod_handler('formatprice', true, $_tmp, false, false, 3) : smarty_modifier_formatprice($_tmp, false, false, 3)); ?>
%<?php endif; ?>:</b>&nbsp;</td>
<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['tax']['tax_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;&nbsp;</td>
</tr>
<?php endforeach; endif; unset($_from);  endif; ?>

<?php if ($this->_tpl_vars['order']['payment_surcharge'] != 0): ?>
<tr>
<td align="right" height="20"><b><?php if ($this->_tpl_vars['order']['payment_surcharge'] > 0):  echo $this->_tpl_vars['lng']['lbl_payment_method_surcharge'];  else:  echo $this->_tpl_vars['lng']['lbl_payment_method_discount'];  endif; ?>:</b>&nbsp;</td>
<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['payment_surcharge'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;&nbsp;</td>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['order']['giftcert_discount'] > 0): ?>
<tr>
<td align="right" height="20"><b><?php echo $this->_tpl_vars['lng']['lbl_giftcert_discount']; ?>
:</b>&nbsp;</td>
<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['giftcert_discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;&nbsp;</td>
</tr>
<?php endif; ?>

<tr>
<td colspan="2">  <hr style="width: 100%;"></td>
</tr>

<tr>
<td align="right" bgcolor="#cccccc" height="25"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_grand_total'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
:</b>&nbsp;</td>
<td align="right" bgcolor="#cccccc" height="25"><b><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['total'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></b></td>
</tr>

<?php if ($this->_tpl_vars['_userinfo']['tax_exempt'] != 'Y'): ?>

<?php if ($this->_tpl_vars['order']['applied_taxes'] && $this->_tpl_vars['order']['extra']['tax_info']['display_taxed_order_totals'] == 'Y'):  $_from = $this->_tpl_vars['order']['applied_taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
?>
<tr>
<td align="right" width="100%" height="20"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_including_tax'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'tax', $this->_tpl_vars['tax']['tax_display_name']) : smarty_modifier_substitute($_tmp, 'tax', $this->_tpl_vars['tax']['tax_display_name']));  if ($this->_tpl_vars['tax']['rate_type'] == "%"): ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['tax']['rate_value'])) ? $this->_run_mod_handler('formatprice', true, $_tmp, false, false, 3) : smarty_modifier_formatprice($_tmp, false, false, 3)); ?>
%<?php endif; ?>:</b>&nbsp;</td>
<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['tax']['tax_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;&nbsp;</td>
</tr>
<?php endforeach; endif; unset($_from);  endif; ?>

<?php else: ?>

<tr>
<td align="right" colspan="2" width="100%" height="20"><?php echo $this->_tpl_vars['lng']['txt_tax_exemption_applied']; ?>
</td>
</tr>

<?php endif; ?>

</table>

<?php if ($this->_tpl_vars['order']['applied_giftcerts']): ?>
<br />
<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
	<td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;"><?php echo $this->_tpl_vars['lng']['lbl_applied_giftcerts']; ?>
</font></td>
</tr>
</table>

<table cellspacing="1" cellpadding="0" width="100%" border="0">

<tr>
<th width="60" bgcolor="#cccccc"><?php echo $this->_tpl_vars['lng']['lbl_giftcert_ID']; ?>
</th>
<th bgcolor="#cccccc"><?php echo $this->_tpl_vars['lng']['lbl_giftcert_cost']; ?>
</th>
</tr>

<?php $_from = $this->_tpl_vars['order']['applied_giftcerts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gc']):
?>
<tr>
<td align="center"><?php echo $this->_tpl_vars['gc']['giftcert_id']; ?>
</td>
<td align="right" nowrap="nowrap"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['gc']['giftcert_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;&nbsp;&nbsp;</td>
</tr>
<?php endforeach; endif; unset($_from); ?>

</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['order']['extra']['special_bonuses'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/special_offers_order_bonuses.tpl", 'smarty_include_vars' => array('bonuses' => $this->_tpl_vars['order']['extra']['special_bonuses'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
