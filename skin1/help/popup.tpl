{* $Id: popup.tpl,v 1.6 2004/06/24 10:09:14 max Exp $ *}
{ config_load file="$skin_config" }
<HTML>
<HEAD>
<TITLE>{$lng.txt_site_title}</TITLE>
{ include file="meta.tpl" }
<LINK rel="stylesheet" href="{$SkinDir}/{#CSSFile#}">
</HEAD>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0" align="center" height="100%">
<TR height="30" valign="middle" align="center">
<TD class="Bottom" height="30"><B>{if $popup_title ne ""}{$popup_title|upper}{else}&nbsp;{/if}</B></TD>
</TR>
<TR><TD height="1"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" alt=""><BR></TD></TR>
<TR><TD class="Bottom" height="1"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" alt=""><BR></TD></TR>

<TR>
<TD height="380" valign="top">
<TABLE cellspacing="10" cellpadding="0">
<TR>
	<TD>
	</TD>
</TR>
</TABLE>
</TD>
</TR>

<TR>
<TH align="right">
<A href="javascript:void(0);" onclick="javascript:window.close();">Close window</A>
&nbsp;&nbsp;
</TH>
</TR>

<TR><TD height="3"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="3" alt=""><BR></TD></TR>
<TR><TD class="Bottom" height="1"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" alt=""><BR></TD></TR>
<TR><TD height="1"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" alt=""><BR></TD></TR>
<TR height="30" valign="middle" align="right">
<TD class="Bottom" height="30">{ include file="copyright.tpl" }</TD>
</TR>
</TABLE>
</BODY>
</HTML>
