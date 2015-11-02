{* popup_shipquote.tpl random *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{ config_load file="$skin_config" }
<html>
<head>
<title>{$config.Company.company_name} {$lng.lbl_shipping_quote}</title>
{ include file="meta.tpl" }
<link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />
{include file="check_zipcode_js.tpl"}
{if $config.General.use_js_states eq 'Y'}
{include file="change_states_js.tpl"}
{/if}
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
	function check_zip_code_ship() {
		return check_zip_code_field(document.forms["shipquoteform"].s_country, document.forms["shipquoteform"].s_zipcode);
	}
{/literal}
-->
</script>
</head>
<body{$reading_direction_tag} style="background-color: #FBFBF3;">
<form action="popup_shipquote.php" method="post" name="shipquoteform">
<input type="hidden" name="mode" value="{if $mode eq 'grandtotal'}checkout{elseif $mode eq 'shipping'}grandtotal{else}shipping{/if}" />

<table width="100%" cellpadding="0" cellspacing="0" align="center" class="Container">
<tr>
	<td class="PopupTitle">{$lng.lbl_shipping_quote}</td>
</tr>
<tr>
	<td height="1"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>
<tr>
	<td class="PopupBG" height="1"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>
{if $err ne ''}
<tr> 
    <td align="center"><br /><font class="Star">{if $err eq 'exception'}{$lng.txt_exception_warning}{elseif $err eq 'avail'}{$lng.txt_out_of_stock}{/if}</font><br /></td>
</tr> 
{/if}
<tr>
	<td class="Container">
	<table cellspacing="6" cellpadding="0" width="100%">

	{if $mode eq ''}
	<tr>
		<td height="150"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
	</tr>
	<tr>
		<td align="center">
	    <table>
		<tr>
			<td align="left" colspan="2">
		    {include file="customer/main/subheader.tpl" title="`$lng.lbl_enter_your_shipping_address`:"}
			</td>
		</tr>
		<tr>
			<td align="right">{$lng.lbl_country}</td>
			<td nowrap="nowrap">
			<select name="s_country" id="s_country" size="1" onchange="check_zip_code_ship()">
			{section name=country_idx loop=$countries}
			<option value="{$countries[country_idx].country_code}"{if $userinfo.s_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $userinfo.s_country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
			{/section}
			</select>
			</td>
		</tr>
		<tr>
			<td align="right">{$lng.lbl_state}</td>
			<td nowrap="nowrap">
			{include file="main/states.tpl" states=$states name="s_state" default=$userinfo.s_state default_country=$userinfo.s_country|default:$config.General.default_country country_name="s_country"}
			</td>
		</tr>
		<tr style="display: none;">
			<td>
			{include file="main/register_states.tpl" state_name="s_state" country_name="s_country" county_name="s_county" state_value=$userinfo.s_state county_value=$userinfo.s_county}
			</td>
		</tr>
		<tr>
			<td align="right">{$lng.lbl_city}</td>
			<td nowrap="nowrap">
			<input type="text" id="s_city" name="s_city" size="27" maxlength="64" value="{$userinfo.s_city}" />
			</td>
		</tr>
		<tr>
			<td align="right">{$lng.lbl_zip_code}</td>
			<td nowrap="nowrap">
			<input type="text" id="s_zipcode" name="s_zipcode" size="27" maxlength="32" value="{$userinfo.s_zipcode}" onchange="check_zip_code_ship()" />
			</td>
		</tr>
		<tr>
			<td class="ButtonsRow" align="center" colspan="2"><br />{include file="buttons/button.tpl" button_title=$lng.lbl_calculate_shippings type="input" href="javascript: document.shipquoteform.submit()" js_to_href="Y" b="1"}</td>
		</tr>
		</table>
		</td>
	</tr>
	{/if}

	{if $mode eq 'shipping' and $shipping_groups}
	<tr>
		<td>
		<table cellspacing="5px">
		<tr>
			<td style="vertical-align: top;">
			{include file="customer/main/subheader.tpl" title=$lng.lbl_shipping_address}
			{$userinfo.s_countryname}<br />
			{$userinfo.s_statename}<br />
			{$userinfo.s_city}<br />
			{$userinfo.s_zipcode}
			<br /><br />
			{include file="buttons/modify.tpl" href="popup_shipquote.php?update"}
			</td>
			<td width="70%">
			{foreach from=$shipping_groups item=v key=k}
			{assign var="found_any_shipping" value="N"}
			{assign var="selected_any" value="N"}
			{cycle values=''}
			{assign var=delivery_text value=$lng.txt_for_fastlane_checkout_delivery|replace:"XX":"`$v.m_city`, `$v.m_state`, `$v.m_country`."|replace:"YY":"`$v.group_name`"}
			{include file="customer/main/subheader.tpl" title="`$lng.lbl_delivery_methods` `$delivery_text`"}
			{foreach from=$shippings[$k] item=s}
			{if $s.active eq "Y" && $s.allowed eq "1"}
			{assign var="found_any_shipping" value="Y"}
			<table cellpadding="1" cellspacing="0" width="100%" {cycle values=" class='TableSubHead', "}>
			<tr>
				<td width="5"><input type="radio" id="shippingid{$s.shippingid}" name="shippingids[{$k}]" value="{$s.shippingid}"{if $s.shippingid eq $shippingids[$k].shippingid || ($shippingids[$k] eq "" && $selected_any eq "N")}{assign var="selected_any" value="Y"} checked="checked"{/if}{if $allow_cod} onclick="javascript: display_cod({if $s.is_cod eq 'Y'}true{else}false{/if});"{/if} /></td>
				<td><label for="shippingid{$s.shippingid}">{$s.shipping|trademark:$insert_trademark}{if $s.shipping_time ne ""} - {$s.shipping_time}{/if}{if $config.Appearance.display_shipping_cost eq "Y"}: {include file="currency.tpl" value=$s.rate}{/if}</label></td>
			</tr>
			</table>
			{/if}
			{/foreach}
			{if $found_any_shipping ne "Y" and $need_shipping}
			<font class="ErrorMessage">{$lng.lbl_no_shipping_for_location}</font><br />
			<br />
			{/if}
			<br />
			{/foreach}
			<td>
		</tr>
		</table>
		</td>
	</tr>
	
	<tr>
		<td class="ButtonsRow" align="center">{include file="buttons/button.tpl" button_title=$lng.lbl_calculate_grandtotal type="input" href="javascript: document.shipquoteform.submit()" js_to_href="Y" b="1"}</td>
	</tr>
	{/if}

	{if $mode eq 'grandtotal'}
	<tr>
		<td style="vertical-align: top;" colspan="2">
		{include file="customer/main/subheader.tpl" title=$lng.lbl_shipping_address}
		{$lng.lbl_country}: {$userinfo.s_countryname}<br />
		{$lng.lbl_state}: {$userinfo.s_statename}<br />
		{$lng.lbl_city}: {$userinfo.s_city}<br />
		{$lng.lbl_zip_code}: {$userinfo.s_zipcode}<br /><br />
		</td>
	</tr>

	<tr>
		<td colspan="2">
		{if $config.Appearance.show_cart_details eq "Y" or $config.Appearance.show_cart_details eq "L"}
		{include file="customer/main/cart_details.tpl" link_qty="Y"}
		{else}
		{include file="customer/main/cart_contents.tpl" link_qty="Y"}
		{/if}
		{include file="customer/main/cart_totals.tpl" link_shipping="Y" no_form_fields=true}
		<br /><br />
		</td>
	</tr>

	<tr>
		<td class="ButtonsRow" align="left">{include file="buttons/button.tpl" button_title=$lng.lbl_shipquote_close type="input" href="javascript: window.close()" js_to_href="Y"}</td>
		<td class="ButtonsRow" align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_shipquote_proceed type="input" href="javascript: document.shipquoteform.submit()" js_to_href="Y" b="1"}</td>
	</tr>

	{/if}


	</table>

	</td>
</tr>
{*
<tr>
	<td valign="bottom">{include file="popup_bottom.tpl"}</td>
</tr>
*}
</table>
</form>
</body>
</html>
