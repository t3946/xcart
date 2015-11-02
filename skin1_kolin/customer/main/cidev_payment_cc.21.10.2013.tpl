<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{ config_load file="$skin_config" }
<html>
<head>
<title>Confirmation page</title>
{ include file="meta.tpl" }
<link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />
</head>
<body{$reading_direction_tag} style="background-color: #FFFFFF;">

{* ------------------- *}
{include file="cidev_tracking_code.tpl" }
{* ------------------- *}

<table align="center">
<tr>
<td align="center">
<font style="font-size: 30px; color: #22B14C; font-weight: normal;">PayPal Payment Instructions</font>
<br />
<br />
<font style="font-size: 16px; color: #000000; font-weight: bold;"><I>The PayPal website will ask you to 'Choose a way to pay'.</I></font>
<br />
</td>
</tr>
<tr>
<td align="center">
<img src="{$SkinDir}/images/Credit-Card-PayPal-Instructions.png" alt="" />
</td>
</tr>
<tr>
<td align="center">
<form action="" method="post" name="checkout_form_new">
{if $cidev_post ne ""}
{foreach from=$cidev_post item=item key=key}
<input type="hidden" name="{$key}" value="{$item}" />
{/foreach}
<input type="hidden" name="cidev_confirm" value="Y" />
{/if}
<br />
<br />

{*
{include file="buttons/button.tpl" button_title="I understand, transfer me to PayPal website" type="input" href="javascript: document.checkout_form_new.submit()" js_to_href="Y" b="1"}
*}

{include file="buttons/button.tpl" button_title=$lng.lbl_continue btn_to_checkout="Y" type="input" href="javascript: document.checkout_form_new.submit()" js_to_href="Y" b="1" button_type="continue"}
<br /><font style="color: #000000"><I>Continue to PayPal Payment Processing Website</I></font>

</form>
</td>
</tr>
</table>

{if $config.Company.cidev_google_adwords ne ""}

{* {$config.Company.cidev_google_adwords} *}

{assign var="ecomm_prodid_replacement" value="ecomm_prodid: ''"}
{assign var="ecomm_pagetype_replacement" value="ecomm_pagetype: 'siteview'"}
{assign var="ecomm_totalvalue_replacement" value="ecomm_totalvalue: ''"}

        {$config.Company.cidev_google_adwords|replace:"ecomm_prodid: ''":"`$ecomm_prodid_replacement`"|replace:"ecomm_pagetype: 'siteview'":"`$ecomm_pagetype_replacement`"|replace:"ecomm_totalvalue: ''":"`$ecomm_totalvalue_replacement`"}

{/if}

</body>
</html>
