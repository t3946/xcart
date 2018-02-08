{* $Id: partner_element_list.tpl,v 1.5 2004/06/24 14:04:55 max Exp $ *}
{ config_load file="$skin_config" }
<HTML>
<HEAD>
<TITLE>{$lng.txt_site_title}</TITLE>
{ include file="meta.tpl" }
<LINK rel="stylesheet" href="{$SkinDir}/{#CSSFile#}">
</HEAD>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" onload="javascript: change_images_width(self.document.documentElement.getElementsByTagName('body')[0].scrollWidth);">
<SCRIPT type="text/javascript" language="JavaScript 1.2">
var images = new Array();
{foreach from=$elements item=v key=k} 
images[{$k}] = new Array({if $v.data_type eq 'application/x-shockwave-flash'}0, 0{else}{$v.elementid}, {$v.data_x|default:"0"}{/if});
{/foreach}

{literal}
function change_images_width(w) {
var x;
	for(x = 0; x < images.length; x++)
		if(images[x][0] > 0 && images[x][1] > 370)
			document.getElementById('img'+images[x][0]).width = w-8;
}
{/literal}
</SCRIPT>
<TABLE cellspacing="2" cellpadding="0" border="0" width="100%" class="DialogBox">
{foreach from=$elements item=v} 
<TR>
	<TD align="center" valign="top" class="DialogBox"> 
	<TABLE cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" border="1" id="table">
	<TR>
		<TD colspan="2" align="center" class="VertMenuBox"><B>{$v.elementid}</B></TD> 
	</TR>
	<TR>
		<TD height="70" colspan="2" align="center" valign="middle"><A href="javascript: void(0);" onclick="javascript: window.open('{$catalogs.admin}/banner_element.php?eid={$v.elementid}', 'ZOOMIN_POPUP','width={math equation="x+20" x=$v.data_x},height={math equation="x+20" x=$v.data_y},toolbar=no,status=no,scrollbars=no,resizable=yes,menubar=no,location=no,direction=no');">{if $v.data_type ne 'application/x-shockwave-flash'}<IMG vspace="3" id="img{$v.elementid}" src="{$current_location}/banner_element.php?eid={$v.elementid}"{if $v.data_x > 370} width="370"{/if} border="0">{else}<IMG src="{$ImagesDir}/flash_icon1.gif" border="0">{/if}</A>
	</TD>
	</TR>
	<TR>
		<TD width="50%">
		<TABLE cellspacing="0" cellpadding="0" border="0" height="100%">
	{if $v.data_type ne 'application/x-shockwave-flash'}
		<TR> 
    		<TD valign="middle"><A href="javascript: void(0);" onclick="javascript: window.top.document.getElementById('banner_body').value +='<#A{$v.elementid}#>';"><IMG src="{$ImagesDir}/add_obj.gif" border="0"></A></TD>
			<TD valign="middle"><A href="javascript: void(0);" onclick="javascript: window.top.document.getElementById('banner_body').value +='<#A{$v.elementid}#>';">{$lng.lbl_add} ({$lng.lbl_clickable})</A></TD>
		</TR>
	{/if}
		<TR>
	    	<TD valign="middle"><A href="javascript: void(0);" onclick="javascript: window.top.document.getElementById('banner_body').value +='<#{$v.elementid}#>';"><IMG src="{$ImagesDir}/add_obj.gif" border="0"></A></TD>
			<TD valign="middle"><A href="javascript: void(0);" onclick="javascript: window.top.document.getElementById('banner_body').value +='<#{$v.elementid}#>';">{$lng.lbl_add}{if $v.data_type ne 'application/x-shockwave-flash'} ({$lng.lbl_non_clickable}){/if}</A></TD>
		</TR>
		<TR> 
			<TD valign="middle"><A href="javascript: void(0);" onclick="javascript:  window.open('{$catalogs.admin}/banner_element.php?eid={$v.elementid}', 'ZOOMIN_POPUP','width={math equation="x+16" x=$v.data_x},height={math equation="x+16" x=$v.data_y},toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');"><IMG src="{$ImagesDir}/zoom.gif" border="0"></A></TD>
			<TD valign="middle"><A href="javascript: void(0);" onclick="javascript:  window.open('{$catalogs.admin}/banner_element.php?eid={$v.elementid}', 'ZOOMIN_POPUP','width={math equation="x+16" x=$v.data_x},height={math equation="x+16" x=$v.data_y},toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');">{$lng.lbl_zoom_in}</A></TD>
		</TR>
		<TR> 
			<TD valign="middle"><A href="partner_banners.php?elementid={$v.elementid}&mode=delete"><IMG src="{$ImagesDir}/delete_obj.gif" border="0"></A></TD>
			<TD valign="middle"><A href="partner_banners.php?elementid={$v.elementid}&mode=delete">{$lng.lbl_delete}</A></TD>
		</TR>
		</TABLE>
		</TD>
		<TD valign="top">
		<TABLE cellspacing="0" cellpadding="0" border="0">
		<TR>
			<TD class="MediaElementProperties">{$lng.lbl_width}:&nbsp;</TD>
			<TD class="MediaElementProperties">{$v.data_x} px</TD>
		</TR>
        <TR>
            <TD class="MediaElementProperties">{$lng.lbl_height}:&nbsp;</TD>
            <TD class="MediaElementProperties">{$v.data_y} px</TD>
        </TR>
        <TR>
            <TD class="MediaElementProperties">{$lng.lbl_type}:&nbsp;</TD>
            <TD class="MediaElementProperties">{$v.data_type|regex_replace:"/^[^\/]*\//":""}</TD>
        </TR>
		</TABLE>
		</TD>
	</TR>
	<TR>
		<TD>&nbsp;</TD>
	</TR> 
	</TABLE>
	</TD> 
</TR>
{/foreach}
</TABLE>
</BODY>
</HTML>

