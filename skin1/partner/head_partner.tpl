{* $Id: head_partner.tpl,v 1.1.2.1 2004/08/26 12:36:33 max Exp $ *}
<TABLE border="0" cellpadding="0" cellspacing="0" width="100%">
<TR> 
<TD width="27">&nbsp;</TD>
<TD><A href="{$http_location}"><IMG src="{$ImagesDir}/xlogo.gif" width="244" height="67" border="0"></A></TD>
<TD valign="top" align="right">
</TD></TR>
</TABLE>
<TABLE border="0" cellpadding="0" cellspacing="0" width="100%">
<TR> 
<TD  colspan="2" class="VertMenuBorder"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" border="0"></TD>
</TR>
<TR> 
<TD class="HeadLine" height="22">
</TD>
<TD class="HeadLine" align="right">
{if $all_languages_cnt gt 1}
<TABLE border="0" cellpadding="0" cellspacing="0">
<FORM action="home.php" method="GET" name="sl_form">
<INPUT type="hidden" name="redirect" value="{$smarty.server.PHP_SELF}?{$smarty.server.QUERY_STRING}">
<TR>
<TD>
<B>{$lng.lbl_select_language}:&nbsp;</B>
<SELECT name="sl" onChange="javascript: document.sl_form.submit()">
{section name=ai loop=$all_languages}
<OPTION value="{$all_languages[ai].code}"{if $store_language eq $all_languages[ai].code} selected{/if}>{$all_languages[ai].language}</OPTION>
{/section}
</SELECT>
</TD></TR>
</FORM>
</TABLE>
{else}
&nbsp;
{/if}
</TD>
</TR>
<TR> 
<TD  colspan="2" class="VertMenuBorder"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" border="0"></TD>
</TR>
<TR>
	<TD colspan="2" valign="middle" height="32">
<TABLE cellspacing="0" cellpadding="0" border="0" width="100%" height="18">
<TR>
	<TD><IMG src="{$ImagesDir}/spacer.gif" width="1" height="18" border="0"></TD>
</TR>
</TABLE>
	</TD>
</TR>
</TABLE>
