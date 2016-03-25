{* $Id: home.tpl,v 1.4.2.1 2006/07/05 09:36:32 svowl Exp $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{if $current_storefront_info.storefrontid ne ""}
<link rel="shortcut icon" href="{$xcart_web_dir}/image.php?id={$current_storefront_info.storefrontid}&amp;type=F" type="image/vnd.microsoft.icon" />
{else}
<link rel="shortcut icon" href="{$ImagesDir}/favicon.ico" type="image/vnd.microsoft.icon" />
{/if}
{config_load file="$skin_config"}
<html>
<head>
<title>
{if $config.SEO.page_title_format eq "A"}
{section name=position loop=$location}
{$location[position].0|escape}
{if not %position.last%} :: {/if}
{/section}
{else}
{section name=position loop=$location step=-1}
{$location[position].0|escape}
{if not %position.last%} :: {/if}
{/section}
{/if}
</title>
{ include file="meta.tpl" }
<link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />
<link rel="stylesheet" href="{$SkinDir}/modules/Fast_Lane_Checkout/{#CSSFile#}" />
<link rel="stylesheet" href="{$SkinDir}/US_City_List/jquery.autocomplete.css" />

<link rel="stylesheet" href="{$SkinDir}/lib/colorbox/colorbox.css" />
<script src="{$SkinDir}/lib/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>

</head>
<body>


{* ------------------- *}
{include file="cidev_tracking_code.tpl" }
{* ------------------- *}


{ include file="head.tpl" }
{ include file="rectangle_top.tpl" }
{if $active_modules.SnS_connector}
{include file="modules/SnS_connector/header.tpl"}
{/if}
<!-- main area -->
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
{*
<td valign="top" width="150"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
<td width="6">&nbsp;</td>
*}
<td valign="top" align="center">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td align="left" colspan="3" width="100%">
<!-- central space -->
<br />

{include file="modules/Fast_Lane_Checkout/tabs_menu.tpl"}

{if $checkout_step ge 0}

{*
{include file="modules/Fast_Lane_Checkout/tabs_menu.tpl"}
*}

{if $smarty.get.shipping_error eq "Y"}
{include file="dialog_message.tpl"}
{/if}

<table cellpadding="0" cellspacing="0" width="100%">                                                                                                                    
{*
<tr><td height="10" width="9" style="background-color: #fbfbf3; text-align: left;"><img src="{$ImagesDir}/top-left.jpg"></td>
<td style="background-color: #fbfbf3; text-align: center;" height="10"><img src="{$ImagesDir}/spacer.gif" height="10"></td>
<td height="10" width="9" style="background-color: #fbfbf3; text-align: right;"><img src="{$ImagesDir}/top-right.jpg"></td></tr>
*}

<tr>                                                                                                                                                                    
<td {* bgcolor="#fbfbf3" *} bgcolor="#ffffff" colspan=3 style="padding-left: 10px; padding-right: 10px;">{include file="modules/Fast_Lane_Checkout/home_main.tpl"}</td>                                           
</tr>                                                                                                                                                                   
{*
<tr><td height="10" width="9" style="background-color: #fbfbf3; text-align: left;"><img src="{$ImagesDir}/bottom-left.jpg"></td>
<td width="100%" style="background-color: #fbfbf3; text-align: center;"><img src="{$ImagesDir}/spacer.gif" height="10"></td>
<td height="10" width="9" style="background-color: #fbfbf3; text-align: right;"><img src="{$ImagesDir}/bottom-right.jpg"></td></tr>
*}
</table>                       


{*include file="modules/Fast_Lane_Checkout/home_main.tpl"*}

{else}

{*
{include file="dialog_message.tpl"}
*}

<table cellpadding="0" cellspacing="0" width="100%">                                                                                                                   
{* 
<tr><td height="10" width="9" style="background-color: #fbfbf3; text-align: left;"><img src="{$ImagesDir}/top-left.jpg"></td>
<td style="background-color: #fbfbf3; text-align: center;" height="10" width="100%"><img src="{$ImagesDir}/spacer.gif" height="10"></td>
<td height="10" width="9" style="background-color: #fbfbf3; text-align: right;"><img src="{$ImagesDir}/top-right.jpg"></td></tr>
*}
<tr>                                                                                                                                                                    
<td {* bgcolor="#fbfbf3" *} bgcolor="#ffffff" colspan=3 style="padding-left: 10px; padding-right: 10px;">{include file="modules/Fast_Lane_Checkout/home_main.tpl"}</td>                                           
</tr>                                                                                                                                                                   
{*
<tr><td height="10" width="9" style="background-color: #fbfbf3; text-align: left;"><img src="{$ImagesDir}/bottom-left.jpg"></td>
<td width="100%" style="background-color: #fbfbf3; text-align: center;"><img src="{$ImagesDir}/spacer.gif" height="10"></td>
<td height="10" width="9" style="background-color: #fbfbf3; text-align: right;"><img src="{$ImagesDir}/bottom-right.jpg"></td></tr>
*}
</table>                       


{*include file="modules/Fast_Lane_Checkout/home_main.tpl"*}

{/if}

<!-- /central space -->
&nbsp;
</td>
</tr>
</table>
</td>
{*
<td width="6">&nbsp;</td>
<td valign="top" width="150"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /> </td>
*}
</tr>
</table>
{ include file="rectangle_bottom.tpl" }
{ include file="ga_code.tpl" }

{* ------------------- *}
{if $config.Company.cidev_google_adwords ne ""}
	{assign var="ecomm_prodid_replacement" value="ecomm_prodid: ''"}
	{assign var="ecomm_pagetype_replacement" value="ecomm_pagetype: 'siteview'"}
	{assign var="ecomm_totalvalue_replacement" value="ecomm_totalvalue: ''"}

	{if $main eq "fast_lane_checkout" && $checkout_step eq "-1"}
		{assign var="ecomm_prodid_replacement" value="ecomm_prodid: `$productids_in_cart_imploded`"}
		{assign var="ecomm_pagetype_replacement" value="ecomm_pagetype: 'cart'"}
		{assign var="ecomm_totalvalue_replacement" value="ecomm_totalvalue: '`$cart.total_cost`'"}
	{/if}

	{$config.Company.cidev_google_adwords|replace:"ecomm_prodid: ''":"`$ecomm_prodid_replacement`"|replace:"ecomm_pagetype: 'siteview'":"`$ecomm_pagetype_replacement`"|replace:"ecomm_totalvalue: ''":"`$ecomm_totalvalue_replacement`"}
{/if}
{* ------------------- *}

 {if $GTS_badge_code ne ""}
       {$GTS_badge_code}
 {/if}
 {if $GTS_order_confirmation_module_code ne ""}
       {$GTS_order_confirmation_module_code}
 {/if}

</body>
</html>
