<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:05
         compiled from customer/main/cart_totals.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'customer/main/cart_totals.tpl', 27, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/cart_totals.tpl","lbl_total,lbl_discount,lbl_discount_coupon,lbl_unset_coupon,lbl_unset_coupon,lbl_discounted_total,lbl_total_shipping_cost,lbl_discounted,lbl_unset_coupon,lbl_unset_coupon,txt_not_available_value,txt_not_available_value,lbl_payment_method_surcharge,lbl_payment_method_discount,lbl_giftcert_discount,lbl_grand_total,lbl_including,lbl_applied_giftcerts,lbl_unset_gc,txt_order_total_msg"); ?><div align="right">
<?php $this->assign('subtotal', $this->_tpl_vars['cart']['subtotal']);  $this->assign('discounted_subtotal', $this->_tpl_vars['cart']['discounted_subtotal']);  $this->assign('shipping_cost', $this->_tpl_vars['cart']['display_shipping_cost']); ?>

<table cellpadding="3" cellspacing="0" width="30%">

<tr>
<td nowrap="nowrap"><font class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_total']; ?>
:</font></td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="FormButton"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['display_subtotal'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['cart']['display_subtotal'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<?php if ($this->_tpl_vars['cart']['discount'] > 0): ?>
<tr>
<td nowrap="nowrap"><font class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_discount']; ?>
:</font></td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['cart']['discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['cart']['coupon_discount'] != 0 && $this->_tpl_vars['cart']['coupon_type'] != 'free_ship'): ?>
<tr>
<td nowrap="nowrap"><font class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_discount_coupon']; ?>
 <a href="cart.php?mode=unset_coupons" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_unset_coupon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/clear.gif" width="11" height="11" border="0" valign="top" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_unset_coupon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a> :</font></td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['coupon_discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['cart']['coupon_discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['cart']['display_discounted_subtotal'] != $this->_tpl_vars['cart']['display_subtotal']): ?>
<tr>
<td nowrap="nowrap"><font class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_discounted_total']; ?>
:</font></td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['display_discounted_subtotal'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['cart']['display_discounted_subtotal'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['config']['Shipping']['disable_shipping'] != 'Y'): ?>
<tr>
<td nowrap="nowrap" class="Green2"><?php echo $this->_tpl_vars['lng']['lbl_total_shipping_cost'];  if ($this->_tpl_vars['cart']['coupon_discount'] != 0 && $this->_tpl_vars['cart']['coupon_type'] == 'free_ship'): ?> (<?php echo $this->_tpl_vars['lng']['lbl_discounted']; ?>
 <a href="cart.php?mode=unset_coupons" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_unset_coupon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/clear.gif" width="11" height="11" border="0" valign="top" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_unset_coupon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a>)<?php endif; ?>
:</td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right" class="Green2"><?php if ($this->_tpl_vars['login'] != "" || $this->_tpl_vars['config']['General']['apply_default_country'] == 'Y' || $this->_tpl_vars['cart']['shipping_cost'] > 0):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['shipping_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['shipping_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  echo $this->_tpl_vars['lng']['txt_not_available_value'];  $this->assign('not_logged_message', '1'); ?></font></td><td><?php endif; ?></td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['cart']['taxes'] && $this->_tpl_vars['config']['Taxes']['display_taxed_order_totals'] != 'Y'):  $_from = $this->_tpl_vars['cart']['taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
?>
<tr>
<td nowrap="nowrap"><font class="FormButton"><?php echo $this->_tpl_vars['tax']['tax_display_name'];  if ($this->_tpl_vars['tax']['rate_type'] == "%"): ?> <?php echo $this->_tpl_vars['tax']['rate_value']; ?>
%<?php endif; ?>:</font></td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall"><?php if ($this->_tpl_vars['login'] != "" || $this->_tpl_vars['config']['General']['apply_default_country'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['tax']['tax_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['tax']['tax_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  echo $this->_tpl_vars['lng']['txt_not_available_value'];  $this->assign('not_logged_message', '1'); ?></font></td><td><?php endif; ?></td>
</tr>
<?php endforeach; endif; unset($_from);  endif; ?>

<?php if ($this->_tpl_vars['cart']['payment_surcharge']): ?>
<tr>
<td nowrap="nowrap"><font class="FormButton"><?php if ($this->_tpl_vars['cart']['payment_surcharge'] > 0):  echo $this->_tpl_vars['lng']['lbl_payment_method_surcharge'];  else:  echo $this->_tpl_vars['lng']['lbl_payment_method_discount'];  endif; ?>:</font></td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['payment_surcharge'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['cart']['payment_surcharge'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['cart']['applied_giftcerts']): ?>
<tr>
<td nowrap="nowrap"><font class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_giftcert_discount']; ?>
:</font></td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['giftcert_discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['cart']['giftcert_discount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font></td>
</tr>
<?php endif; ?>

<tr>
<td colspan="4" height="1"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer_black.gif" width="100%" height="1" alt="" /><br /></td>
</tr>

<tr>
<td nowrap="nowrap"><font class="ProductPriceSmall" style="text-transform: uppercase;"><?php echo $this->_tpl_vars['lng']['lbl_grand_total']; ?>
:</font></td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['total_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['cart']['total_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<?php if ($this->_tpl_vars['cart']['taxes'] && $this->_tpl_vars['config']['Taxes']['display_taxed_order_totals'] == 'Y'): ?>

<tr>
<td colspan="4" align="right"><b><?php echo $this->_tpl_vars['lng']['lbl_including']; ?>
:</b></td>
</tr>

<?php $_from = $this->_tpl_vars['cart']['whole_taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
?>
<tr class="TableSubHead">
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['tax']['tax_display_name']; ?>
:</td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['tax']['tax_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['tax']['tax_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>

<?php endif; ?>


</table>
<?php if ($this->_tpl_vars['cart']['applied_giftcerts']): ?>
<br />
<br />
<font class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_applied_giftcerts']; ?>
:</font>
<br />
<?php unset($this->_sections['gc']);
$this->_sections['gc']['name'] = 'gc';
$this->_sections['gc']['loop'] = is_array($_loop=$this->_tpl_vars['cart']['applied_giftcerts']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
 echo $this->_tpl_vars['cart']['applied_giftcerts'][$this->_sections['gc']['index']]['giftcert_id']; ?>
 <a href="cart.php?mode=unset_gc&amp;gcid=<?php echo $this->_tpl_vars['cart']['applied_giftcerts'][$this->_sections['gc']['index']]['giftcert_id'];  if ($GLOBALS['HTTP_GET_VARS']['paymentid']): ?>&amp;paymentid=<?php echo $GLOBALS['HTTP_GET_VARS']['paymentid'];  endif; ?>"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/clear.gif" width="11" height="11" border="0" valign="top" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_unset_gc'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a> : <font class="ProductPriceSmall"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['applied_giftcerts'][$this->_sections['gc']['index']]['giftcert_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font><br />
<?php endfor; endif;  endif; ?>

<?php if ($this->_tpl_vars['not_logged_message'] == '1'):  echo $this->_tpl_vars['lng']['txt_order_total_msg'];  endif; ?>

<?php if (! $this->_tpl_vars['no_form_fields']): ?>
<input type="hidden" name="paymentid" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['paymentid'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="mode" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['mode'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="action" value="update" />
<?php endif; ?>
</div>
<?php if ($this->_tpl_vars['active_modules']['Special_Offers'] != ""): ?>
<hr align="left" noshade="noshade" size="1" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/cart_bonuses.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>