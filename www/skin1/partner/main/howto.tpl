{* $Id: howto.tpl,v 1.15.2.2 2005/06/03 11:59:21 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_banner_html_code}
{$lng.txt_banner_html_code_note}<BR><BR>
 
<!-- IN THIS SECTION -->
 
{include file="dialog_tools.tpl"}
 
<!-- IN THIS SECTION -->
<BR>
 {if $config.XAffiliate.display_as_iframe eq 'Y'}{assign var="local_type" value="iframe"}{else}{assign var="local_type" value="js"}{/if}
 
{if $config.XAffiliate.partner_enable_level eq 'Y'}
<P>
{$lng.txt_banner_html_code_register_note}<BR>
<B>{$lng.lbl_link}:</B><BR>
<TEXTAREA cols="60" rows="3" wrap readonly>&lt;A href="{$catalogs.partner}/register.php?parent={$login}"&gt;Register&lt;/A&gt;</TEXTAREA>
<BR>
{/if}

<P align="justify">
{$lng.txt_banner_html_code_comment}</P>
{capture name=dialog}
<TABLE border="0" cellspacing="1" cellpadding="2" width="100%">
{if $banners ne ''}
{foreach from=$banners item=v}
<TR>
	<TD colspan="2" class="AdminTitle">{$v.banner}</TD>
</TR>
<TR valign="top">
	<TD colspan="2">{include file="main/display_banner.tpl" banner=$v type=$local_type partner=$login}</TD>
</TR>
<TR>
	<TD colspan="2"><B>{$lng.lbl_iframe_code}:</B></TD>
</TR>
<TR>
    <TD colspan="2"><TEXTAREA cols="60" rows="5" wrap readonly>{include file="main/display_banner.tpl" banner=$v type="iframe" partner=$login current_location=$http_location}</TEXTAREA></TD>
</TR>
<TR>
	{if $v.banner_type eq 'G'}
	<TD colspan="2"><B>{$lng.lbl_html_code}:</B></TD>
</TR>
<TR>
	<TD colspan="2"><TEXTAREA cols="60" rows="5" wrap readonly>{include file="main/display_banner.tpl" banner=$v type="js" partner=$login current_location=$http_location}</TEXTAREA></TD>
	{else}
    <TD><B>{$lng.lbl_javascript_version}:</B></TD>
	<TD><B>{$lng.lbl_ssi_version}:</B></TD>
</TR>
<TR>
	<TD><TEXTAREA cols="35" rows="5" wrap readonly>{include file="main/display_banner.tpl" banner=$v type="js" partner=$login current_location=$http_location}</TEXTAREA></TD>
    <TD><TEXTAREA cols="35" rows="5" wrap readonly>{include file="main/display_banner.tpl" banner=$v type="ssi" partner=$login current_location=$http_location}</TEXTAREA></TD>
	{/if}
</TR>
{/foreach}
{else}
<TR>
	<TD colspan="2" align="center">{$lng.lbl_no_banners_have_been_defined}</TD>
</TR>
{/if}
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_available_banners extra="width=100%"}
