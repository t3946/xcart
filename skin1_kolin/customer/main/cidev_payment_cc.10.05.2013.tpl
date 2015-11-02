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
<font style="font-size: 40px; color: #C1198D; font-weight: normal;">PayPal Payment Instructions</font>
<br />
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
{include file="buttons/button.tpl" button_title="I understand, transfer me to PayPal website" type="input" href="javascript: document.checkout_form_new.submit()" js_to_href="Y" b="1"}
</form>
</td>
</tr>
</table>

</body>
</html>
