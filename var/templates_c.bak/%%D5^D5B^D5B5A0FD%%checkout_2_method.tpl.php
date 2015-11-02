<?php /* Smarty version 2.6.12, created on 2011-10-11 06:22:21
         compiled from modules/Fast_Lane_Checkout/checkout_2_method.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'modules/Fast_Lane_Checkout/checkout_2_method.tpl', 71, false),array('modifier', 'escape', 'modules/Fast_Lane_Checkout/checkout_2_method.tpl', 135, false),)), $this); ?>
<?php func_load_lang($this, "modules/Fast_Lane_Checkout/checkout_2_method.tpl","txt_gc_not_enough_money,lbl_billing_address,lbl_payment_method,lbl_continue,lbl_shipping_and_payment,lbl_redeem_discount_coupon,txt_add_coupon_header,lbl_coupon_code,lbl_discount_coupon,lbl_unset_coupon,lbl_unset_coupon"); ?>

<script type="text/javascript">
<!--
<?php echo '
function display_cod(flag) {
	for (var i = 0; i < paymentsCOD.length; i++) {
		if (!paymentsCOD[i] || !document.getElementById(\'cod_tr\'+paymentsCOD[i]))
			continue;

		document.getElementById(\'cod_tr\'+paymentsCOD[i]).style.display = flag ? "" : "none";
	}

	return true;
}
'; ?>

-->
</script>
<br>
<?php ob_start(); ?>

<?php if ($GLOBALS['HTTP_GET_VARS']['err'] == 'gc_not_enough_money'): ?>
<div style="text-align: center;">
<font class="ErrorMessage"><?php echo $this->_tpl_vars['lng']['txt_gc_not_enough_money']; ?>
</font>
</div>
<br />
<?php endif; ?>
<form action="cart.php" method="post" name="cartform">

<input type="hidden" name="mode" value="checkout" />
<input type="hidden" name="cart_operation" value="cart_operation" />
<input type="hidden" name="action" value="update" />


<?php if ($this->_tpl_vars['config']['Shipping']['disable_shipping'] != 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/shipping_methods.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>

<table cellpadding="5" cellspacing="5" width="100%">

<tr>
<td valign="top" width="30%">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_billing_address'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['userinfo']): ?> 
<?php echo $this->_tpl_vars['userinfo']['b_address']; ?>
<br /> 
<?php if ($this->_tpl_vars['userinfo']['b_address_2']):  echo $this->_tpl_vars['userinfo']['b_address_2']; ?>
<br />
<?php endif;  echo $this->_tpl_vars['userinfo']['b_city']; ?>
<br /> 
<?php echo $this->_tpl_vars['userinfo']['b_statename']; ?>
<br />
<?php echo $this->_tpl_vars['userinfo']['b_countryname']; ?>
<br />
<?php echo $this->_tpl_vars['userinfo']['b_zipcode']; ?>
 
<?php else: ?> 
No data 
<?php endif; ?> 
 
<?php if ($this->_tpl_vars['login'] != ""): ?>
<br /><br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/modify.tpl", 'smarty_include_vars' => array('href' => "register.php?mode=update&amp;action=cart")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

</td>
<td valign="top" width="70%">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_payment_method'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table cellspacing="0" cellpadding="2" width="100%">
<?php $_from = $this->_tpl_vars['payment_methods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['payment']):
?>
<tr<?php echo smarty_function_cycle(array('values' => ' class="TableSubHead", '), $this); if ($this->_tpl_vars['payment']['is_cod'] == 'Y'): ?> id="cod_tr<?php echo $this->_tpl_vars['payment']['paymentid']; ?>
"<?php endif; ?>>
<td width="1"><input type="radio" name="paymentid" id="pm<?php echo $this->_tpl_vars['payment']['paymentid']; ?>
" value="<?php echo $this->_tpl_vars['payment']['paymentid']; ?>
"<?php if ($this->_tpl_vars['payment']['is_default'] == '1'): ?> checked="checked"<?php endif; ?> /></td>
<?php if ($this->_tpl_vars['payment']['processor'] == "ps_paypal_pro.php"): ?>
<td colspan="2">
<table cellpadding="0" cellspacing="0"><tr>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "payments/ps_paypal_pro_express_checkout.tpl", 'smarty_include_vars' => array('paypal_express_link' => 'logo')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<td>&nbsp;&nbsp;</td>
	<td><label for="pm<?php echo $this->_tpl_vars['payment']['paymentid']; ?>
"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "payments/ps_paypal_pro_express_checkout.tpl", 'smarty_include_vars' => array('paypal_express_link' => 'text')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></label></td>
</tr>
</table>
</td>
<?php else: ?>
<td width="20%" nowrap="nowrap" style="padding-right: 15px;"><label for="pm<?php echo $this->_tpl_vars['payment']['paymentid']; ?>
"><b><?php echo $this->_tpl_vars['payment']['payment_method']; ?>
</b></label></td>
<td width="80%"><?php echo $this->_tpl_vars['payment']['payment_details']; ?>
</td>
<?php endif; ?>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>

</td>
</tr>
</table>

<?php if (! $this->_tpl_vars['js_enabled']): ?>
	<br />
	<div align="center">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "submit_wo_js.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['lng']['lbl_continue'],'b' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
<?php endif; ?>
</form>

<script type="text/javascript">
<!--
var paymentsCOD = [<?php echo '';  $_from = $this->_tpl_vars['payment_methods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['payment']):
 echo '';  if ($this->_tpl_vars['payment']['is_cod'] == 'Y'):  echo '';  echo $this->_tpl_vars['payment']['paymentid'];  echo ',';  endif;  echo '';  endforeach; endif; unset($_from);  echo '0'; ?>
];
display_cod(<?php if ($this->_tpl_vars['display_cod'] == 'Y'): ?>true<?php else: ?>false<?php endif; ?>);
-->
</script>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_shipping_and_payment'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['cart']['coupon_discount'] == 0 && $this->_tpl_vars['products'] != ""): ?>
	<?php if ($this->_tpl_vars['active_modules']['Discount_Coupons'] != "" && $this->_tpl_vars['show_discount_coupons'] == 'Y'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Discount_Coupons/add_coupon.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif;  else: ?>
	<?php if ($this->_tpl_vars['cart']['coupon_type'] != 'free_ship'): ?>
		<table cellpadding="5" cellspacing="5" width="100%">
		<tr>
			<td valign="top" width="30%">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_redeem_discount_coupon'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<?php echo $this->_tpl_vars['lng']['txt_add_coupon_header']; ?>

			</td>
			<td valign="top" width="70%">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_coupon_code'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<table cellpadding="1" cellspacing="1">
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
				</table>
			</td>
		</tr>
		</table>
	<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['js_enabled']): ?>
	<div align="center">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/continue.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "javascript: document.cartform.submit()",'b' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<br />
	</div>
<?php endif; ?>