{include file="page_title.tpl" title="Customer's cart: <B>`$cart.cart_number`</B>"}

{assign var=shipping_found value=""}
{foreach from=$cart.shippingids item=v key=mid}
	{if $v ne ""}
		{assign var=shipping_found value="Y"}
	{/if}
{/foreach}

{if $shipping_found eq "Y" || $cart.payment_method ne ""}
{capture name=dialog}

<table cellpadding="5" cellspacing="5" width="100%">

<tr>
<td valign="top" width="30%">

{if $userinfo}
{include file="customer/main/subheader.tpl" title=$lng.lbl_shipping_address}
{$userinfo.s_address}<br />
{if $userinfo.s_address_2}
{$userinfo.s_address_2}<br />
{/if}
{$userinfo.s_city}<br />
{$userinfo.s_statename}<br />
{$userinfo.s_countryname}<br />
{$userinfo.s_zipcode}
{/if}

</td>
<td valign="top" width="*">
{if $shipping_found eq "Y"}
    {foreach from=$cart.shippingids item=v key=mid}
	
	{if $cart.shipping_groups[$mid].m_country_code eq "US"}
	{assign var="m_country_code" value="USA"}
	{else}
	{assign var="m_country_code" value=$cart.shipping_groups[$mid].m_country}
	{/if}
	{assign var=delivery_text value=$lng.txt_for_fastlane_checkout_delivery|replace:"XX":"`$cart.shipping_groups[$mid].m_city`, `$cart.shipping_groups[$mid].m_state_code`, `$m_country_code`"|replace:"YY":""}
	{include file="customer/main/subheader.tpl" title="`$lng.lbl_delivery_methods` `$delivery_text`"}

	{if $cart.all_shippings[$mid] ne ""}
		{foreach from=$cart.all_shippings[$mid] item=s key=key}

			<input type="radio" {if $cart.shippingids[$mid] eq $s.shippingid}checked="checked"{/if} disabled="disabled" />
			{if $cart.shippingids[$mid] eq $s.shippingid}<B>{/if}
			{$s.shipping|trademark:$insert_trademark}{if $s.shipping_time ne ""} - {$s.shipping_time}{/if}{if $config.Appearance.display_shipping_cost eq "Y" and ($config.General.apply_default_country eq "Y" or $cart.shipping_cost gt 0)}: {include file="currency.tpl" value=$s.rate}{/if}
			{if $cart.shippingids[$mid] eq $s.shippingid}</B>{/if}
			<br />
		{/foreach}
	{/if}
    {/foreach}
{/if}
</td>
</tr>

<tr>
<td valign="top" width="30%">

{if $userinfo}
{include file="customer/main/subheader.tpl" title=$lng.lbl_billing_address}
{$userinfo.b_address}<br />
{if $userinfo.b_address_2}
{$userinfo.b_address_2}<br />
{/if}
{$userinfo.b_city}<br />
{$userinfo.b_statename}<br />
{$userinfo.b_countryname}<br />
{$userinfo.b_zipcode}
{/if}

</td>
<td valign="top" width="*">

{if $cart.payment_method ne ""}
	{include file="customer/main/subheader.tpl" title="Payment method"}
	{$cart.payment_method}
{/if}

</td>
</tr>
</table>

{/capture}
{include file="dialog.tpl" title="Checkout options" content=$smarty.capture.dialog extra='width="100%"'}
<br />
{/if}


{capture name=dialog}
	{include file="customer/main/cart.tpl" from_admin_area="Y"}
{/capture}
{include file="dialog.tpl" title="Items in cart" content=$smarty.capture.dialog extra='width="100%"'}

<br />
{include file="main/other_customer_orders.tpl"}
