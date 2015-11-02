<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:13
         compiled from modules/Fast_Lane_Checkout/cart_subtotal.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Fast_Lane_Checkout/cart_subtotal.tpl', 1, false),array('modifier', 'escape', 'modules/Fast_Lane_Checkout/cart_subtotal.tpl', 26, false),)), $this); ?>
<?php func_load_lang($this, "modules/Fast_Lane_Checkout/cart_subtotal.tpl","lbl_subtotal,lbl_discount,lbl_discount_coupon,lbl_unset_coupon,lbl_unset_coupon,lbl_discounted_subtotal,lbl_giftcert_discount,lbl_applied_giftcerts,lbl_unset_gc,txt_order_total_msg"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Fast_Lane_Checkout/cart_subtotal.tpl"), $this); endif; ?><div align="right">
<?php $this->assign('subtotal', $this->_tpl_vars['cart']['subtotal']);  $this->assign('discounted_subtotal', $this->_tpl_vars['cart']['discounted_subtotal']); ?>

<table cellpadding="3" cellspacing="0" width="21%">

<tr>
<td nowrap="nowrap"><font class="FormButton" ><?php echo $this->_tpl_vars['lng']['lbl_subtotal']; ?>
:</font></td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall"><?php $_smarty_tpl_vars = $this->_tpl_vars;
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

<?php if ($this->_tpl_vars['cart']['discounted_subtotal'] != $this->_tpl_vars['cart']['subtotal']): ?>
<tr>
<td colspan="4" height="1"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer_black.gif" width="100%" height="1" alt="" /><br /></td>
</tr>

<tr>
<td nowrap="nowrap"><font class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_discounted_subtotal']; ?>
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

<?php if ($this->_tpl_vars['cart']['taxes'] && $this->_tpl_vars['config']['Taxes']['display_taxed_order_totals'] == 'Y'): ?>


<?php $_from = $this->_tpl_vars['cart']['taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
?>
<tr class="TableSubHead">
<td nowrap="nowrap" align="left"><B>Including <?php echo $this->_tpl_vars['tax']['rate_value']; ?>
% <?php echo $this->_tpl_vars['tax']['tax_display_name']; ?>
:</B></td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><B><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['tax']['tax_cost_no_shipping'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></B></td>
<td nowrap="nowrap" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['tax']['tax_cost_no_shipping'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>

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
 <a href="cart.php?mode=unset_gc&gcid=<?php echo $this->_tpl_vars['cart']['applied_giftcerts'][$this->_sections['gc']['index']]['giftcert_id']; ?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/clear.gif" width="11" height="11" border="0" valign="top" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_unset_gc'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a> : <font class="ProductPriceSmall"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['applied_giftcerts'][$this->_sections['gc']['index']]['giftcert_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font><br />
<?php endfor; endif;  endif; ?>

<?php if ($this->_tpl_vars['not_logged_message'] == '1'):  echo $this->_tpl_vars['lng']['txt_order_total_msg'];  endif; ?>

</div>
<input type="hidden" name="action" value="update" />
<hr align="left" noshade="noshade" size="1" />
<?php if ($this->_tpl_vars['active_modules']['Special_Offers'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/cart_bonuses.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>


<?php if ($this->_tpl_vars['hidden_manufacturerid'] != ""): ?>
<input type="hidden" id="cidev_hidden_deliv_subt_<?php echo $this->_tpl_vars['hidden_manufacturerid']; ?>
" name="cidev_hidden_deliv_subt_<?php echo $this->_tpl_vars['hidden_manufacturerid']; ?>
" value='<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cidev_hidden_deliv_subt'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>' />

<input type="hidden" id="cidev_hidden_allow_to_checkout" name="cidev_hidden_allow_to_checkout" value='<?php echo $this->_tpl_vars['hidden_allow_to_checkout']; ?>
' />

<input type="hidden" id="cidev_hidden_need_add_more_<?php echo $this->_tpl_vars['hidden_manufacturerid']; ?>
" name="cidev_hidden_need_add_more_<?php echo $this->_tpl_vars['hidden_manufacturerid']; ?>
" value='<?php echo $this->_tpl_vars['hidden_need_add_more']; ?>
' />

<input type="hidden" id="cidev_hidden_display_price_<?php echo $this->_tpl_vars['hidden_cartid']; ?>
" name="cidev_hidden_display_price_<?php echo $this->_tpl_vars['hidden_cartid']; ?>
" value='<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cidev_hidden_display_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>' />
<input type="hidden" id="cidev_hidden_price_on_amount_<?php echo $this->_tpl_vars['hidden_cartid']; ?>
" name="cidev_hidden_price_on_amount_<?php echo $this->_tpl_vars['hidden_cartid']; ?>
" value='<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cidev_hidden_price_on_amount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>' />

  <?php if ($this->_tpl_vars['cidev_hidden_set_new_amount'] != ""): ?>
    <input type="hidden" id="cidev_hidden_set_new_amount_<?php echo $this->_tpl_vars['hidden_cartid']; ?>
" name="cidev_hidden_set_new_amount_<?php echo $this->_tpl_vars['hidden_cartid']; ?>
" value="<?php echo $this->_tpl_vars['cidev_hidden_set_new_amount']; ?>
" />
  <?php endif;  endif; ?>

<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Fast_Lane_Checkout/cart_subtotal.tpl"), $this); endif; ?>