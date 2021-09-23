{* $Id: partner_banners.tpl,v 1.14 2004/05/28 12:20:58 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_banners_management}
{if $banner_type eq ''}
{$lng.txt_banners_note}
{elseif $banner_type eq 'T'}
{$lng.txt_banners_text_link_note}
{elseif $banner_type eq 'G'}
{$lng.txt_banners_graphic_banner_note}
{elseif $banner_type eq 'M'}
{$lng.txt_banners_media_rich_banner_note}
{elseif $banner_type eq 'P'}
{$lng.txt_banners_product_link_note}
{/if}
<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->

<BR>
{if $config.XAffiliate.display_as_iframe eq 'Y'}{assign var="local_type" value="iframe"}{else}{assign var="local_type" value="js"}{/if}

{if $banners ne "" && $banner_type eq ''}
{capture name=dialog}
<TABLE border="0" cellspacing="1" cellpadding="2">
{foreach from=$banners item=v}
<TR>
	<TD><B>{$lng.lbl_banner}:</B>&nbsp;{$v.banner}&nbsp;<I>({if $v.banner_type eq 'T'}{$lng.lbl_text_link}{elseif $v.banner_type eq 'G'}{$lng.lbl_graphic_banner}{elseif $v.banner_type eq 'M'}{$lng.lbl_media_rich_banner}{else}{$lng.lbl_product_banner}{/if})</I><BR><BR>
	<TABLE cellspacing="1" cellpadding="0" border="0" bgcolor="#000000">
	<TR bgcolor="#ffffff">
		<TD>{include file="main/display_banner.tpl" banner=$v type=$local_type partner=''}</TD>
	</TR>
	</TABLE>
	</TD>
</TR>
<TR>
	<TD><INPUT type="button" onclick="javascript: self.location='partner_banners.php?bannerid={$v.bannerid}&mode=delete';" value="{$lng.lbl_delete}">&nbsp;
	<INPUT type="button" onclick="javascript: self.location='partner_banners.php?bannerid={$v.bannerid}';" value="{$lng.lbl_modify}"><BR><BR>&nbsp;</TD>
</TR>
{/foreach}
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_available_banners extra="width=100%"}

{elseif $banner_type ne ''}
{if $banner ne ''}
<BR>

{capture name=dialog}
<TABLE cellspacing="1" cellpadding="0" border="0" bgcolor="#000000">
<TR bgcolor="#ffffff">
	<TD>{include file="main/display_banner.tpl" banner=$banner type=$local_type partner=''}</TD>
</TR>
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_edited_banner extra="width=100%"}
{/if}

<BR>

{capture name=dialog}
<FORM action="partner_banners.php" method="POST" enctype="multipart/form-data">
<INPUT type="hidden" name="mode" value="add">
<INPUT type="hidden" name="banner_type" value="{$banner_type}">
<INPUT type="hidden" name="bannerid" value="{$banner.bannerid}">
<TABLE border="0" cellpadding="0" cellspacing="3" width="100%">
<TR>
    <TD>{$lng.lbl_banner_name}:</TD>
    <TD><INPUT type="text" maxlength="128" size="40" name="add[banner]" value="{$banner.banner}"></TD>
</TR>
<TR>
    <TD>{$lng.lbl_banner_width}:</TD>
    <TD><INPUT type="text" size="5" value="{$banner.banner_x}" name="add[banner_x]"></TD>
</TR>
<TR>
    <TD>{$lng.lbl_banner_height}:</TD>
    <TD><INPUT type="text" size="5" value="{$banner.banner_y}" name="add[banner_y]"></TD>
</TR>
<TR>
    <TD>{$lng.lbl_availability}:</TD>
    <TD><INPUT type="checkbox" value="Y" name="add[avail]"{if $banner.avail eq 'Y' || $banner.bannerid eq ''} checked{/if}></TD>
</TR>
<TR>
    <TD>{$lng.lbl_open_in_new_window}:</TD>
    <TD><INPUT type="checkbox" value="Y" name="add[open_blank]"{if $banner.open_blank eq 'Y' || $banner.bannerid eq ''} checked{/if}></TD>
</TR>
{if $banner_type eq 'T'}
<TR>
    <TD>{$lng.lbl_text}:</TD>
    <TD><TEXTAREA cols="50" rows="3" name="add[body]">{$banner.body}</TEXTAREA></TD>
</TR>
{elseif $banner_type eq 'G'}
<TR>
    <TD>{$lng.lbl_text} ({$lng.lbl_optional}):</TD>
    <TD><TEXTAREA cols="50" rows="3" name="add[legend]">{$banner.legend}</TEXTAREA></TD>
</TR> 
<TR>
    <TD>{$lng.lbl_alt_tag} ({$lng.lbl_optional}):</TD> 
    <TD><TEXTAREA cols="50" rows="3" name="add[alt]">{$banner.alt}</TEXTAREA></TD>
</TR>  
<TR>
    <TD>{$lng.lbl_text_location}:</TD>
    <TD><SELECT name="add[direction]">
    <OPTION value="U"{if $banner.direction eq 'U' || $banner.direction eq ''} selected{/if}>{$lng.lbl_above}</OPTION>
    <OPTION value="L"{if $banner.direction eq 'L'} selected{/if}>{$lng.lbl_left}</OPTION>
    <OPTION value="R"{if $banner.direction eq 'R'} selected{/if}>{$lng.lbl_right}</OPTION>
	<OPTION value="D"{if $banner.direction eq 'D'} selected{/if}>{$lng.lbl_below}</OPTION>
	</SELECT></TD>
</TR>  
<TR>
    <TD>{$lng.lbl_image}:</TD> 
    <TD><INPUT type="file" name="userfile"></TD>
</TR>  
{elseif $banner_type eq 'P'}
<TR>
    <TD>{$lng.lbl_picture}:</TD>
    <TD><INPUT type="checkbox" name="add[is_image]" value='Y'{if $banner.is_image eq 'Y'} checked{/if}></TD>
</TR>
<TR>
    <TD>{$lng.lbl_full_name}:</TD>
    <TD><INPUT type="checkbox" name="add[is_name]" value='Y'{if $banner.is_name eq 'Y'} checked{/if}></TD>
</TR>  
<TR> 
    <TD>{$lng.lbl_description}:</TD>
    <TD><INPUT type="checkbox" name="add[is_descr]" value='Y'{if $banner.is_descr eq 'Y'} checked{/if}></TD>
</TR>
<TR>
    <TD>{$lng.lbl_add_to_cart_link}:</TD>
    <TD><INPUT type="checkbox" name="add[is_add]" value='Y'{if $banner.is_add eq 'Y'} checked{/if}></TD>
</TR>
{elseif $banner_type eq 'M'}
<SCRIPT type="text/javascript" language="JavaScript 1.2">
{literal}
function preview_body() {
	win = window.open('preview_banner.php','PREVIEW_POPUP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=yes,menubar=no,location=no,direction=no');
}
{/literal}
</SCRIPT>
<TR> 
    <TD>{$lng.lbl_body}:</TD>
    <TD><TEXTAREA cols="60" rows="10" name="add[body]" id="banner_body" wrap>{$banner.body}</TEXTAREA></TD>
</TR>
<TR>
	<TD>&nbsp;</TD>
	<TD><A href="javascript: void(0);" onclick="javascript: document.getElementById('banner_body').value += '<#A#>';"><IMG align="absmiddle" src="{$ImagesDir}/open_a.gif" border="0">&nbsp;{$lng.lbl_add_link_opening_tag}</A>&nbsp;
	<A href="javascript: void(0);" onclick="javascript: document.getElementById('banner_body').value += '<#/A#>';"><IMG align="absmiddle" src="{$ImagesDir}/close_a.gif" border="0">&nbsp;{$lng.lbl_add_link_closing_tag}</A>&nbsp;
	<A href="javascript: void(0);" onclick="javascript: preview_body();"><IMG align="absmiddle" src="{$ImagesDir}/preview_img.gif" border="0">&nbsp;{$lng.lbl_preview}</A>
	</TD>
</TR>
{if $elements ne ''}
<TR>
    <TD>&nbsp;</TD>
    <TD><BR><B>{$lng.lbl_media_library}:</B><BR>
	<IFRAME width="100%" height="300" src="{$catalogs.admin}/partner_element_list.php"></IFRAME>
    </TD>
</TR>
{/if}
{/if}
<TR>
	<TD>&nbsp;</TD>
	<TD><INPUT type="submit" value="Save banner">{if $banner.bannerid > 0}&nbsp;<INPUT type="submit" name="close" value="{$lng.lbl_close}">{/if}</TD>
</TR>
</TABLE>
</FORM>
{if $banner_type eq 'M'}
<BR>
<B>{$lng.lbl_add_media_object}</B><BR>
<FORM action="partner_banners.php" method="POST" enctype="multipart/form-data">
<INPUT type="hidden" name="mode" value="upload">
<INPUT type="hidden" name="banner_type" value="{$banner_type}">
<INPUT type="hidden" name="bannerid" value="{$banner.bannerid}">
<TABLE border="0" cellpadding="0" cellspacing="3">
<TR>
	<TD>{$lng.lbl_media_object}:</TD>
	<TD><INPUT type="file" name="userfile"></TD>
</TR>
<TR> 
    <TD colspan="2"><BR><B>{$lng.txt_flash_note}</B></TD>
</TR>
<TR> 
    <TD>{$lng.lbl_width}:</TD>
    <TD><INPUT type="text" size="5" name="width"></TD>
</TR>
<TR>  
    <TD>{$lng.lbl_height}:</TD>
    <TD><INPUT type="text" size="5" name="height"></TD>
</TR>
<TR>
	<TD>&nbsp;</TD>
	<TD><INPUT type="submit" value="{$lng.lbl_add}"></TD>
</TR>
</TABLE>
</FORM>
{/if}
{/capture}
{if $banner ne ''}{assign var="title" value=$lng.lbl_modify_banner}{else}{assign var="title" value=$lng.lbl_add_banner}{/if}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$title extra="width=100%"}
{/if}
