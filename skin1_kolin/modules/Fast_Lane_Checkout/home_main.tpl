{if $checkout_step eq 0}
{include file="modules/Fast_Lane_Checkout/checkout_0_enter.tpl"}

{elseif $checkout_step eq 1}
{include file="modules/Fast_Lane_Checkout/checkout_1_profile.tpl"}

{elseif $checkout_step eq 2}
{include file="modules/Fast_Lane_Checkout/checkout_2_method.tpl"}

{elseif $checkout_step eq 3}
{include file="modules/Fast_Lane_Checkout/checkout_3_place.tpl"}

{else}
{if $last_categoryid ne 0}
{assign var=last_categoryid value="?cat=`$last_categoryid`"}
{else}
{assign var=last_categoryid value=""}
{/if}

{*
{if $cart.shipping_groups ne ""}
{assign var=warehouse_cart_url value=""}
{foreach from=$cart.shipping_groups item=v key=k name=shipping_groups_f}
{if $v.need_add_more ne "" && $warehouse_cart_url eq ""}

{assign var="warehouse_cart_url" value="cart.php#warehouse"}

{assign var="d_minimum_order_amount_in_us" value=$`$v.d_minimum_order_amount_in_us`}
{assign var=lbl_minimum_order_amount_mes value=$lng.lbl_minimum_order_amount_message|substitute:"minimum_order_amount":$d_minimum_order_amount_in_us}
{/if}
{/foreach}
{/if}
*}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

var set_warehouse_background = 0;

function func_set_warehouse_background(m){

return true;

/*
	if (set_warehouse_background == "0"){
		document.getElementById('warehouse').style.background = '#CC3333';
		alert(m);
	}
	if (set_warehouse_background == "1") {
		document.getElementById('warehouse').style.background = '#ffffff';
	}
        if (set_warehouse_background == "2") {
		return true;
	}

	set_warehouse_background++;
	setTimeout(func_set_warehouse_background(), 100);
	set_warehouse_background = 0;
*/
}
{/literal}
//]]>
</script>


<div align="left" width="100%">
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td>{include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title=$lng.lbl_continue_shopping style="button" href="home.php`$last_categoryid`"}</td>
	<td>
{if $variant_id_for_point2 ne "" && $variant_id_for_point2 eq "0"}
	{include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title=$lng.lbl_shipping_quote bold="N" style="button" href="javascript: window.open('popup_shipquote.php','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" js_to_href="Y"}
{/if}
	</td>

	<td>
{if $variant_id_for_point6 eq "1"}
        {include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title="Request a quote" bold="N" style="button" href="javascript: window.open('popup_requestaquote.php','popup_requestaquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" js_to_href="Y"}
{/if}
	</td>

	<td width="34%" align="center" valign="top">

{if $cart.cart_number ne ""}
	<B>Your cart number is: {$cart.cart_number}</B>
{/if}

	</td>
	<td align="right" width="33%">
{if $cart.paymentid ne ""}
	{if $warehouse_cart_url ne ""}
{include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title=$lng.lbl_checkout style="button" href=$warehouse_cart_url color="red" arrow="Y" js_onclick_to_href="func_set_warehouse_background('$lbl_minimum_order_amount_mes');"}
	{else}
{include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title=$lng.lbl_checkout style="button" href="cart.php?mode=checkout&l=y&review=y&paymentid=`$cart.paymentid`" color="red" arrow="Y"}
	{/if}
{else}
	{if $warehouse_cart_url ne ""}
{include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title=$lng.lbl_checkout style="button" href=$warehouse_cart_url color="red" arrow="Y" js_onclick_to_href="func_set_warehouse_background('$lbl_minimum_order_amount_mes');"}
	{else}
{include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title=$lng.lbl_checkout style="button" href="cart.php?mode=checkout&l=y" color="red" arrow="Y"}
	{/if}
{/if}
	</td>
</tr>
</table>
</div>

{include file="customer/main/cart.tpl"}

{/if}
