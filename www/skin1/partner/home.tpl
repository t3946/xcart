{* $Id: home.tpl,v 1.25.2.2 2004/11/19 11:42:41 max Exp $ *}
{ config_load file="$skin_config" }
<HTML>
<HEAD>
<TITLE>{$lng.txt_site_title}</TITLE>
{ include file="meta.tpl" }
<LINK rel="stylesheet" href="{$SkinDir}/skin1_admin.css">
</HEAD>
<BODY  leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
{ include file="rectangle_top.tpl" }
{ include file="partner/head_partner.tpl" }
<!-- main area -->
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
<TR>
<TD width="3">&nbsp;</TD>
<TD width="150" valign="top">
{if $login eq "" }
{ include file="auth.tpl" }
{else}
{ include file="partner/menu.tpl" }
<BR>
{ include file="menu_profile.tpl" }
{/if}
<BR>
{ include file="help.tpl" }
<IMG src="{$ImagesDir}/spacer.gif" width="150" height="1" border="0">
</TD>
<TD width="20">&nbsp;</TD>
<TD valign="top">
<!-- central space -->
{include file="location.tpl"}

{include file="dialog_message.tpl"}

{if $main eq "stats"}
{include file="partner/main/stats.tpl"}

{elseif $main eq "module_disabled"}
{include file="partner/main/module_disabled.tpl"}

{elseif $main eq "banner_info"}
{include file="main/banner_info.tpl"}

{elseif $main eq "referred_sales"}
{include file="main/referred_sales.tpl"}

{elseif $main eq "register"}
{include file="partner/main/register.tpl"}

{elseif $main eq "payment_history"}
{include file="partner/main/payment_history.tpl"}

{elseif $main eq "affiliates"}
{include file="main/affiliates.tpl"}

{elseif $main eq "howto"}
{include file="partner/main/howto.tpl"}

{elseif $main eq "products"}
{include file="partner/main/search_result.tpl"}

{elseif $main eq "product"}
{include file="partner/main/product.tpl"}

{elseif $main eq "home" and $login ne ""}
{include file="partner/main/promotions.tpl"}

{elseif $main eq "home" && $mode eq 'profile_created'}
{include file="partner/main/welcome_queued.tpl"}

{elseif $main eq "home"}
{include file="partner/main/welcome.tpl"}

{elseif $main eq "secure_login_form"}
{include file="partner/main/secure_login_form.tpl"}

{elseif $main eq "change_password"}
{include file="customer/main/change_password.tpl"}

{else}
{include file="common_templates.tpl"}
{/if}

<!-- /central space -->
&nbsp;
</TD>
<TD width="20">&nbsp;</TD>
<TD width="150" valign="top">
{if $active_modules.Users_online ne ""}
{ include file="modules/Users_online/menu_users_online.tpl" }
<BR>
{/if}
{if $login eq "" }
{ include file="news.tpl" }
{else}
{ include file="authbox.tpl" }
{/if}
<BR>
{ include file="poweredby.tpl" }
<BR>
<IMG src="{$ImagesDir}/spacer.gif" width="150" height="1" border="0">
</TD>
</TR>
</TABLE>
{ include file="rectangle_bottom.tpl" }
</BODY>
</HTML>
