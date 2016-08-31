{* $Id: home.tpl,v 1.80.2.1 2006/11/08 14:38:27 svowl Exp $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{ config_load file="$skin_config" }
<html>
<head>
<title>{if $login ne ""}{if $current_storefront_info.prefix eq "MAIN_SF_PREFIX"}AR-{else}{$current_storefront_info.prefix}{/if}Operator: {$cidev_firstname} ({$login}){else}{$lng.txt_site_title}{/if}</title>
{ include file="meta.tpl" }
<link rel="stylesheet" href="{$SkinDir}/skin1_admin.css" />
</head>
<body{$reading_direction_tag}>
{ include file="rectangle_top.tpl" }
{ include file="head_admin.tpl" }
<!-- main area -->
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<tr>
<td valign="top">
<!-- central space -->
{include file="location.tpl"}

{include file="dialog_message.tpl"}

{if $main == "home"}
{include file="verificator/main/account.tpl"}
{elseif $main eq "change_password"}
{include file="main/change_password.tpl"}
{elseif $main eq "access_denied"}
{include file="verificator/main/error_access_denied.tpl"}
{else}
{include file="common_templates.tpl"}
{/if}

&nbsp;
</td>
<td>
<img src="{$ImagesDir}/spacer.gif" width="20" height="1" alt="" />
</td>
</tr>
</table>
{ include file="rectangle_bottom.tpl" }
</body>
</html>
