<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:28
         compiled from mail/order_data.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'trademark', 'mail/order_data.tpl', 7, false),array('modifier', 'truncate', 'mail/order_data.tpl', 10, false),array('modifier', 'cat', 'mail/order_data.tpl', 10, false),array('modifier', 'string_format', 'mail/order_data.tpl', 10, false),array('modifier', 'formatprice', 'mail/order_data.tpl', 21, false),array('modifier', 'substitute', 'mail/order_data.tpl', 31, false),array('modifier', 'strip_tags', 'mail/order_data.tpl', 107, false),)), $this); ?>
<?php func_load_lang($this, "mail/order_data.tpl","lbl_products_ordered,lbl_items,lbl_delivery_by,lbl_sku,lbl_product,lbl_quantity,lbl_selected_options,lbl_item_price,eml_order_shipped,lbl_tracking_number_is,eml_order_shipped_nolink,lbl_gift_certificate,lbl_amount,lbl_recipient,lbl_gc_send_via_postal_mail,lbl_mail_address,lbl_phone,lbl_recipient_email,lbl_total,lbl_payment_method,lbl_delivery,lbl_subtotal,lbl_discount,lbl_coupon_saving,lbl_discounted_total,lbl_shipping_cost,lbl_coupon_saving,lbl_payment_method_surcharge,lbl_payment_method_discount,lbl_giftcert_discount,lbl_total,lbl_including,txt_tax_exemption_applied,lbl_applied_giftcerts"); ?><?php echo $this->_tpl_vars['lng']['lbl_products_ordered']; ?>
:
-----------------

<?php $_from = $this->_tpl_vars['order']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['shgrform'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['shgrform']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
        $this->_foreach['shgrform']['iteration']++;
?>

<?php echo $this->_tpl_vars['v']['group_name']; ?>
 <?php echo $this->_tpl_vars['lng']['lbl_items']; ?>
 (<?php echo $this->_tpl_vars['lng']['lbl_delivery_by']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['v']['shipping'])) ? $this->_run_mod_handler('trademark', true, $_tmp, '') : smarty_modifier_trademark($_tmp, '')); ?>
, <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['shipping_cost']['gross'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>):

<?php unset($this->_sections['prod_num']);
$this->_sections['prod_num']['name'] = 'prod_num';
$this->_sections['prod_num']['loop'] = is_array($_loop=$this->_tpl_vars['products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['prod_num']['show'] = true;
$this->_sections['prod_num']['max'] = $this->_sections['prod_num']['loop'];
$this->_sections['prod_num']['step'] = 1;
$this->_sections['prod_num']['start'] = $this->_sections['prod_num']['step'] > 0 ? 0 : $this->_sections['prod_num']['loop']-1;
if ($this->_sections['prod_num']['show']) {
    $this->_sections['prod_num']['total'] = $this->_sections['prod_num']['loop'];
    if ($this->_sections['prod_num']['total'] == 0)
        $this->_sections['prod_num']['show'] = false;
} else
    $this->_sections['prod_num']['total'] = 0;
if ($this->_sections['prod_num']['show']):

            for ($this->_sections['prod_num']['index'] = $this->_sections['prod_num']['start'], $this->_sections['prod_num']['iteration'] = 1;
                 $this->_sections['prod_num']['iteration'] <= $this->_sections['prod_num']['total'];
                 $this->_sections['prod_num']['index'] += $this->_sections['prod_num']['step'], $this->_sections['prod_num']['iteration']++):
$this->_sections['prod_num']['rownum'] = $this->_sections['prod_num']['iteration'];
$this->_sections['prod_num']['index_prev'] = $this->_sections['prod_num']['index'] - $this->_sections['prod_num']['step'];
$this->_sections['prod_num']['index_next'] = $this->_sections['prod_num']['index'] + $this->_sections['prod_num']['step'];
$this->_sections['prod_num']['first']      = ($this->_sections['prod_num']['iteration'] == 1);
$this->_sections['prod_num']['last']       = ($this->_sections['prod_num']['iteration'] == $this->_sections['prod_num']['total']);
 echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_sku'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['productcode']; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_product'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['product']; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_quantity'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['amount']; ?>

<?php if ($this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['product_options'] != ""):  echo $this->_tpl_vars['lng']['lbl_selected_options']; ?>
:
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/display_options.tpl", 'smarty_include_vars' => array('options' => $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['product_options'],'options_txt' => $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['product_options_txt'],'is_plain' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_item_price'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['display_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['order']['extra']['tax_info']['display_cart_products_tax_rates'] == 'Y' && $this->_tpl_vars['_userinfo']['tax_exempt'] != 'Y'): ?>

<?php $_from = $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['extra_data']['taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
 if ($this->_tpl_vars['tax']['tax_value'] > 0):  echo $this->_tpl_vars['tax']['tax_display_name']; ?>
 <?php if ($this->_tpl_vars['tax']['rate_type'] == "%"):  echo ((is_array($_tmp=$this->_tpl_vars['tax']['rate_value'])) ? $this->_run_mod_handler('formatprice', true, $_tmp, false, false, 3) : smarty_modifier_formatprice($_tmp, false, false, 3)); ?>
%<?php else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['tax']['rate_value'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endif; ?>

<?php endforeach; endif; unset($_from);  endif; ?>


<?php endfor; endif;  if ($this->_tpl_vars['show_shipping_groups'] == 'Y' && $this->_tpl_vars['v']['tracking']):  $_from = $this->_tpl_vars['v']['tracking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tr']):
 if ($this->_tpl_vars['tr']['tracknum'] != ""):  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['eml_order_shipped'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'shipper', $this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['shipping']) : smarty_modifier_substitute($_tmp, 'shipper', $this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['shipping'])))) ? $this->_run_mod_handler('substitute', true, $_tmp, 'distributor', $this->_tpl_vars['v']['group_name']) : smarty_modifier_substitute($_tmp, 'distributor', $this->_tpl_vars['v']['group_name'])); ?>

<?php echo $this->_tpl_vars['lng']['lbl_tracking_number_is']; ?>
 <?php echo $this->_tpl_vars['tr']['tracknum']; ?>

<?php echo ((is_array($_tmp=$this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['link'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'tracknum', $this->_tpl_vars['tr']['tracknum']) : smarty_modifier_substitute($_tmp, 'tracknum', $this->_tpl_vars['tr']['tracknum'])); ?>

<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['lng']['eml_order_shipped_nolink'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'shipper', $this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['shipping']) : smarty_modifier_substitute($_tmp, 'shipper', $this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['shipping'])); ?>

<?php echo $this->_tpl_vars['tracking_links'][$this->_tpl_vars['tr']['linkid']]['link']; ?>

<?php endif;  endforeach; endif; unset($_from); ?>

<?php endif;  endforeach; endif; unset($_from);  unset($this->_sections['giftcert']);
$this->_sections['giftcert']['name'] = 'giftcert';
$this->_sections['giftcert']['loop'] = is_array($_loop=$this->_tpl_vars['giftcerts']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['giftcert']['show'] = true;
$this->_sections['giftcert']['max'] = $this->_sections['giftcert']['loop'];
$this->_sections['giftcert']['step'] = 1;
$this->_sections['giftcert']['start'] = $this->_sections['giftcert']['step'] > 0 ? 0 : $this->_sections['giftcert']['loop']-1;
if ($this->_sections['giftcert']['show']) {
    $this->_sections['giftcert']['total'] = $this->_sections['giftcert']['loop'];
    if ($this->_sections['giftcert']['total'] == 0)
        $this->_sections['giftcert']['show'] = false;
} else
    $this->_sections['giftcert']['total'] = 0;
if ($this->_sections['giftcert']['show']):

            for ($this->_sections['giftcert']['index'] = $this->_sections['giftcert']['start'], $this->_sections['giftcert']['iteration'] = 1;
                 $this->_sections['giftcert']['iteration'] <= $this->_sections['giftcert']['total'];
                 $this->_sections['giftcert']['index'] += $this->_sections['giftcert']['step'], $this->_sections['giftcert']['iteration']++):
$this->_sections['giftcert']['rownum'] = $this->_sections['giftcert']['iteration'];
$this->_sections['giftcert']['index_prev'] = $this->_sections['giftcert']['index'] - $this->_sections['giftcert']['step'];
$this->_sections['giftcert']['index_next'] = $this->_sections['giftcert']['index'] + $this->_sections['giftcert']['step'];
$this->_sections['giftcert']['first']      = ($this->_sections['giftcert']['iteration'] == 1);
$this->_sections['giftcert']['last']       = ($this->_sections['giftcert']['iteration'] == $this->_sections['giftcert']['total']);
 echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_gift_certificate'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['gcid']; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_amount'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['amount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_recipient'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient']; ?>

<?php if ($this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['send_via'] == 'P'):  echo $this->_tpl_vars['lng']['lbl_gc_send_via_postal_mail']; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_mail_address'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient_firstname']; ?>
 <?php echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient_lastname']; ?>

		<?php echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient_address']; ?>
, <?php echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient_city']; ?>
,
		<?php if ($this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient_countyname'] != ''):  echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient_countyname']; ?>
 <?php endif;  echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient_state']; ?>
 <?php echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient_country']; ?>
, <?php echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient_zipcode']; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_phone'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient_phone']; ?>

<?php else:  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_recipient_email'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['giftcerts'][$this->_sections['giftcert']['index']]['recipient_email']; ?>

<?php endif; ?>

<?php endfor; endif; ?>

<?php echo $this->_tpl_vars['lng']['lbl_total']; ?>
:
-------
<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_payment_method'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['payment_method']; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delivery'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space'])); ?>

<?php $_from = $this->_tpl_vars['order']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
 echo ((is_array($_tmp=$this->_tpl_vars['v']['shipping'])) ? $this->_run_mod_handler('trademark', true, $_tmp, "") : smarty_modifier_trademark($_tmp, "")); ?>

<?php endforeach; endif; unset($_from);  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_subtotal'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['display_subtotal'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['order']['discount'] > 0):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_discount'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['order']['coupon'] && $this->_tpl_vars['order']['coupon_type'] != 'free_ship'):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_coupon_saving'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['coupon_discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> (<?php echo $this->_tpl_vars['order']['coupon']; ?>
)
<?php endif;  if ($this->_tpl_vars['order']['discounted_subtotal'] != $this->_tpl_vars['order']['subtotal']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_discounted_total'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['display_discounted_subtotal'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif;  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_shipping_cost'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['display_shipping_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['order']['coupon'] && $this->_tpl_vars['order']['coupon_type'] == 'free_ship'):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_coupon_saving'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['coupon_discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> (<?php echo $this->_tpl_vars['order']['coupon']; ?>
)

<?php endif;  if ($this->_tpl_vars['order']['applied_taxes'] && $this->_tpl_vars['order']['extra']['tax_info']['display_taxed_order_totals'] != 'Y'):  $_from = $this->_tpl_vars['order']['applied_taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
 if ($this->_tpl_vars['tax']['rate_type'] == "%"):  $this->assign('rate_value', ((is_array($_tmp=$this->_tpl_vars['tax']['rate_value'])) ? $this->_run_mod_handler('formatprice', true, $_tmp, false, false, 3) : smarty_modifier_formatprice($_tmp, false, false, 3)));  $this->assign('tax_display_name', ($this->_tpl_vars['tax']['tax_display_name'])." ".($this->_tpl_vars['rate_value'])."%");  else:  $this->assign('tax_display_name', $this->_tpl_vars['tax']['tax_display_name']);  endif;  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tax_display_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['tax']['tax_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endforeach; endif; unset($_from);  endif;  if ($this->_tpl_vars['order']['payment_surcharge'] != 0):  if ($this->_tpl_vars['order']['payment_surcharge'] > 0):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_payment_method_surcharge'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  else:  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_payment_method_discount'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['payment_surcharge'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['order']['giftcert_discount'] > 0):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_giftcert_discount'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['giftcert_discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_total'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['total'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['_userinfo']['tax_exempt'] != 'Y'):  if ($this->_tpl_vars['order']['applied_taxes'] && $this->_tpl_vars['order']['extra']['tax_info']['display_taxed_order_totals'] == 'Y'):  echo $this->_tpl_vars['lng']['lbl_including']; ?>
:
<?php $_from = $this->_tpl_vars['order']['applied_taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
 if ($this->_tpl_vars['tax']['rate_type'] == "%"):  $this->assign('rate_value', ((is_array($_tmp=$this->_tpl_vars['tax']['rate_value'])) ? $this->_run_mod_handler('formatprice', true, $_tmp, false, false, 3) : smarty_modifier_formatprice($_tmp, false, false, 3)));  $this->assign('tax_display_name', ($this->_tpl_vars['tax']['tax_display_name'])." ".($this->_tpl_vars['rate_value'])."%");  else:  $this->assign('tax_display_name', $this->_tpl_vars['tax']['tax_display_name']);  endif;  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tax_display_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['tax']['tax_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endforeach; endif; unset($_from);  endif;  else:  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_tax_exemption_applied'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['order']['applied_giftcerts']):  echo $this->_tpl_vars['lng']['lbl_applied_giftcerts']; ?>
:
<?php unset($this->_sections['gc']);
$this->_sections['gc']['name'] = 'gc';
$this->_sections['gc']['loop'] = is_array($_loop=$this->_tpl_vars['order']['applied_giftcerts']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['gc']['show'] = true;
$this->_sections['gc']['max'] = $this->_sections['gc']['loop'];
$this->_sections['gc']['step'] = 1;
$this->_sections['gc']['start'] = $this->_sections['gc']['step'] > 0 ? 0 : $this->_sections['gc']['loop']-1;
if ($this->_sections['gc']['show']) {
    $this->_sections['gc']['total'] = $this->_sections['gc']['loop'];
    if ($this->_sections['gc']['total'] == 0)
        $this->_sections['gc']['show'] = false;
} else
    $this->_sections['gc']['total'] = 0;
if ($this->_sections['gc']['show']):

            for ($this->_sections['gc']['index'] = $this->_sections['gc']['start'], $this->_sections['gc']['iteration'] = 1;
                 $this->_sections['gc']['iteration'] <= $this->_sections['gc']['total'];
                 $this->_sections['gc']['index'] += $this->_sections['gc']['step'], $this->_sections['gc']['iteration']++):
$this->_sections['gc']['rownum'] = $this->_sections['gc']['iteration'];
$this->_sections['gc']['index_prev'] = $this->_sections['gc']['index'] - $this->_sections['gc']['step'];
$this->_sections['gc']['index_next'] = $this->_sections['gc']['index'] + $this->_sections['gc']['step'];
$this->_sections['gc']['first']      = ($this->_sections['gc']['iteration'] == 1);
$this->_sections['gc']['last']       = ($this->_sections['gc']['iteration'] == $this->_sections['gc']['total']);
?>
    <?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['order']['applied_giftcerts'][$this->_sections['gc']['index']]['giftcert_id'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['order']['applied_giftcerts'][$this->_sections['gc']['index']]['giftcert_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endfor; endif;  endif; ?>

<?php if ($this->_tpl_vars['order']['extra']['special_bonuses'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/special_offers_order_bonuses.tpl", 'smarty_include_vars' => array('bonuses' => $this->_tpl_vars['order']['extra']['special_bonuses'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
