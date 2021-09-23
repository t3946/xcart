{* $Id: bonuses_points2gc.tpl,v 1.7.2.4 2006/07/11 08:39:34 svowl Exp $ *}
{include file="check_email_script.tpl"}
<script type="text/javascript" language="JavaScript 1.2">
<!--
var offers_bp_rate = {$config.Special_Offers.offers_bp_rate};
var offers_bp_min = {$config.Special_Offers.offers_bp_min};
var offers_bp_max = {$bonus.points};
var msg_not_enough = "{$lng.lbl_sp_bpconv_not_enough|escape:"javascript"}";
var msg_too_low = "{$lng.lbl_sp_bpconv_too_low|escape:"javascript"}";
var txt_recipient_invalid = "{$lng.txt_recipient_invalid|strip_tags|replace:"\n":" "|replace:"\r":" "}";
var txt_amount_invalid = "{$lng.txt_amount_invalid|strip_tags|replace:"\n":" "|replace:"\r":" "}";
-->
</script>

{include file="main/include_js.tpl" src="modules/Special_Offers/customer/bonuses_points2gc.js"}
{include file="check_zipcode_js.tpl"}

<table cellpadding="5">
<tr>
	<td><img src="{$ImagesDir}/gift.gif" alt="" /></td>
	<td>{$lng.txt_gc_header}</td>
</tr>
</table>

<p />
{include file="main/subheader.tpl" title=$lng.lbl_gift_certificate_details class="grey"}

{if $amount_error}
<p class="ErrorMessage">{$lng.txt_amount_invalid}</p>
{/if}

<form name="gccreate" action="bonuses.php" method="post" onsubmit="javascript: return check_gc_form()">
<input type="hidden" name="mode" value="points" />

<table width="100%" cellpadding="0">

<tr>
	<td colspan="3">
<b><font class="ProductDetailsTitle">1. {$lng.lbl_gc_whom_sending}<br />
</font></b>
{$lng.lbl_gc_whom_sending_subtitle}<br />
	</td>
</tr>

<tr>
	<td align="right">{$lng.lbl_from}</td>
	<td><font class="Star">*</font></td>
	<td align="left">
<input type="text" name="purchaser" size="30" value="{if $giftcert.purchaser}{$giftcert.purchaser|escape:"html"}{else}{$userinfo.firstname} {$userinfo.lastname}{/if}" />
	</td>
</tr>

<tr>
	<td align="right">{$lng.lbl_through}</td>
	<td><font class="Star">*</font></td>
	<td align="left">
<input type="text" name="recipient" size="30" value="{if $giftcert.recipient}{$giftcert.recipient|escape:"html"}{else}{$userinfo.firstname} {$userinfo.lastname}{/if}" />
	</td>
</tr>

<tr>
	<td colspan="3">
<b><font class="ProductDetailsTitle"><br />2. {$lng.lbl_gc_add_message}<br /></font></b>
{$lng.lbl_gc_add_message_subtitle}<br />
	</td>
</tr>

<tr>
	<td align="right">{$lng.lbl_message}</td>
	<td><font class="Star"></font></td>
	<td align="left"><textarea name="message" rows="8" cols="50">{$giftcert.message|escape}</textarea></td>
</tr>

<tr>
	<td colspan="3">
<b><font class="ProductDetailsTitle"><br />3. {$lng.lbl_gc_choose_amount}<br /></font></b>
{capture name="rate"}
<b>{include file="currency.tpl" value=$config.Special_Offers.offers_bp_rate}</b> {include file="customer/main/alter_currency_value.tpl" alter_currency_value=$config.Special_Offers.offers_bp_rate}
{/capture}
{$lng.txt_sp_bpconv_details|substitute:"points":$bonus.points:"min":$config.Special_Offers.offers_bp_min:"rate":$smarty.capture.rate}
	</td>
</tr>


<tr valign="middle">
	<td align="right">{$lng.lbl_sp_customer_bonus_points}:</td>
	<td><font class="Star">*</font></td>
	<td align="left" valign="middle">
<input type="text" id="bp_amount" name="amount" size="6" maxlength="5" value="{$giftcert.amount}" onchange="conv_amount()" />
x
<b>{$config.Special_Offers.offers_bp_rate}</b>
=
{$config.General.currency_symbol}<b id='converted_amount'>{math equation="x*y" x=$giftcert.amount y=$config.Special_Offers.offers_bp_rate format="%.2f"}</b>
&nbsp;&nbsp;
<input type="button" value="{$lng.lbl_sp_recalculate|strip_tags:false|escape}" onclick="conv_amount()" />
	</td>
</tr>
 
<tr>
	<td colspan="2">
	<td><div id="bp_conv_err" class="Star"></div></td>
</tr>

<tr>
	<td colspan="3">
<b><font class="ProductDetailsTitle"><br />4. {$lng.lbl_email_address}<br /></font></b>
{$lng.lbl_gc_enter_email}<br />
	</td>
</tr>

<tr>
	<td nowrap="nowrap" align="right">{$lng.lbl_email}</td>
	<td><font class="Star">*</font></td>
	<td align="left"><input type="text" name="recipient_email" size="30" value="{$giftcert.recipient_email}" /></td>
</tr>


</table>
</form>

<p />

<center>{include file="buttons/button.tpl" button_title=$lng.lbl_gc_create href="javascript: formSubmit();" js_to_href="Y"}</center>
