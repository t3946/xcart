{* $Id: preview_banner.tpl,v 1.6 2004/06/24 10:09:13 max Exp $ *}
{ config_load file="$skin_config" }
<HTML>
<HEAD>
<TITLE>{$lng.lbl_preview_banner}</TITLE>
{ include file="meta.tpl" }
<LINK rel="stylesheet" href="{$SkinDir}/{#CSSFile#}">
</HEAD>
{if $mode eq ''}
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" onload="javascript: document.getElementById('preview').value = window.opener.document.getElementById('banner_body').value; document.previewform.submit();">
<FORM action="preview_banner.php" method="post" name="previewform">
<INPUT type="hidden" name="type" value="preview">
<INPUT type="hidden" name="preview" id="preview">
</FORM>
{else}
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0" align="center" height="100%">
<TR height="30" valign="middle" align="center">
<TD class="Bottom" height="30">{$lng.lbl_preview_banner}</TD>
</TR>
<TR><TD height="1"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" alt=""><BR></TD></TR>
<TR><TD class="Bottom" height="1"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" alt=""><BR></TD></TR>

<TR>
<TD height="380" valign="top">
<TABLE height="350" width="100%" cellpadding="15" cellspacing="0" border="0"><TR><TD valign="middle" align="center">

<TABLE cellspacing="1" cellpadding="0" border="0" bgcolor="#000000">
    <TR bgcolor="#ffffff">
        <TD>{$banner}</TD>
    </TR>
</TABLE>

</TD></TR></TABLE>
</TD>
</TR>

<TR>
<TH align="right">
<A href="javascript:void(0);" onclick="javascript:window.close();">{$lng.lbl_close_window}</A>
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
{/if}
</BODY>
</HTML>
