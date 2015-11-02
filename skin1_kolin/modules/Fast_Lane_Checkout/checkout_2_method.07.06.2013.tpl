{* $Id: checkout_2_method.tpl,v 1.9.2.2 2006/10/23 06:31:05 max Exp $ *}

{*<h3>{$lng.lbl_shipping_and_payment}</h3>*}

<script type="text/javascript">
<!--
{literal}
function display_cod(flag) {
	for (var i = 0; i < paymentsCOD.length; i++) {
		if (!paymentsCOD[i] || !document.getElementById('cod_tr'+paymentsCOD[i]))
			continue;

		document.getElementById('cod_tr'+paymentsCOD[i]).style.display = flag ? "" : "none";
	}

	return true;
}
{/literal}
-->
</script>
<br>
{capture name=dialog}

{if $smarty.get.err eq 'gc_not_enough_money'}
<div style="text-align: center;">
<font class="ErrorMessage">{$lng.txt_gc_not_enough_money}</font>
</div>
<br />
{/if}
<form action="cart.php" method="post" name="cartform">

<input type="hidden" name="mode" value="checkout" />
<input type="hidden" name="cart_operation" value="cart_operation" />
<input type="hidden" name="action" value="update" />


{if $config.Shipping.disable_shipping ne "Y"}
{include file="modules/Fast_Lane_Checkout/shipping_methods.tpl"}

{/if}

<table cellpadding="5" cellspacing="5" width="100%">

<tr>
<td valign="top" width="30%">
{include file="customer/main/subheader.tpl" title=$lng.lbl_billing_address}
{if $userinfo} 
{$userinfo.b_address}<br /> 
{if $userinfo.b_address_2}
{$userinfo.b_address_2}<br />
{/if}
{$userinfo.b_city}<br /> 
{$userinfo.b_statename}<br />
{$userinfo.b_countryname}<br />
{$userinfo.b_zipcode} 
{else} 
No data 
{/if} 
 
{if $login ne ""}
<br /><br />
{include file="buttons/modify.tpl" href="register.php?mode=update&amp;action=cart"}
{/if}

</td>
<td valign="top" width="70%">
{include file="customer/main/subheader.tpl" title=$lng.lbl_payment_method}

<table cellspacing="0" cellpadding="2" width="100%">
{foreach from=$payment_methods item=payment}
<tr{cycle values=' class="TableSubHead", '}{if $payment.is_cod eq "Y"} id="cod_tr{$payment.paymentid}"{/if}>
<td width="1"><input type="radio" name="paymentid" id="pm{$payment.paymentid}" value="{$payment.paymentid}"{if $payment.is_default eq "1"} checked="checked"{/if} /></td>
{if $payment.processor eq "ps_paypal_pro.php"}
<td colspan="2">
<table cellpadding="0" cellspacing="0"><tr>
	<td>{include file="payments/ps_paypal_pro_express_checkout.tpl" paypal_express_link="logo"}</td>
	<td>&nbsp;&nbsp;</td>
	<td><label for="pm{$payment.paymentid}">{include file="payments/ps_paypal_pro_express_checkout.tpl" paypal_express_link="text"}</label></td>
</tr>
</table>
</td>
{else}
<td width="20%" nowrap="nowrap" style="padding-right: 15px;"><label for="pm{$payment.paymentid}"><b>{$payment.payment_method}</b></label></td>
<td width="80%">{$payment.payment_details}</td>
{/if}
</tr>
{/foreach}
</table>

</td>
</tr>
</table>

{if !$js_enabled}
	<br />
	<div align="center">
		{include file="submit_wo_js.tpl" value=$lng.lbl_continue b=1}
	</div>
{/if}
</form>

<script type="text/javascript">
<!--
var paymentsCOD = [{strip}
{foreach from=$payment_methods item=payment}
{if $payment.is_cod eq "Y"}
{$payment.paymentid},
{/if}
{/foreach}
0
{/strip}];
display_cod({if $display_cod eq 'Y'}true{else}false{/if});
-->
</script>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_shipping_and_payment_2 content=$smarty.capture.dialog extra='width="100%"'}

{if $cart.coupon_discount eq 0 and $products ne ""}
	{if $active_modules.Discount_Coupons ne "" && $show_discount_coupons eq 'Y'}
		{include file="modules/Discount_Coupons/add_coupon.tpl}
	{/if}
{else}
	{if $cart.coupon_type ne "free_ship"}
		<table cellpadding="5" cellspacing="5" width="100%">
		<tr>
			<td valign="top" width="30%">
				{include file="customer/main/subheader.tpl" title=$lng.lbl_redeem_discount_coupon}
				{$lng.txt_add_coupon_header}
			</td>
			<td valign="top" width="70%">
				{include file="customer/main/subheader.tpl" title=$lng.lbl_coupon_code}
				<table cellpadding="1" cellspacing="1">
				<tr>
					<td nowrap="nowrap"><font class="FormButton">{$lng.lbl_discount_coupon} <a href="cart.php?mode=unset_coupons" alt="{$lng.lbl_unset_coupon|escape}"><img src="{$ImagesDir}/clear.gif" width="11" height="11" border="0" valign="top" alt="{$lng.lbl_unset_coupon|escape}" /></a> :</font></td>
					<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
					<td nowrap="nowrap" align="right"><font class="ProductPriceSmall">{include file="currency.tpl" value=$cart.coupon_discount}</font></td>
					<td nowrap="nowrap" align="right">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$cart.coupon_discount}</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	{/if}
{/if}

{if $js_enabled}
	<div align="center">
		{include file="buttons/continue.tpl" style="button" href="javascript: document.cartform.submit()" b=1}
		<br />
	</div>
{/if}

<div align="center">
<font style="color: #000000"><I>{$lng.lbl_continue_checkout_2}</I></font>
</div>

