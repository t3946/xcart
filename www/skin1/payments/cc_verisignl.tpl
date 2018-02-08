{* $Id: cc_verisignl.tpl,v 1.3.2.1 2005/02/17 06:53:49 max Exp $ *}
<H3>VeriSign Payflow Link</H3>
{$lng.txt_cc_configure_top_text}
<P>
{$lng.txt_cc_verisignl_note|substitute:"http_location":$http_location}
<P>
{capture name=dialog}
<FORM action="cc_processing.php?cc_processor={$smarty.get.cc_processor|escape:"url"}" method="post">
<CENTER>
<TABLE border="0" cellspacing="10">
<TR>
<TD>{$lng.lbl_cc_verisignl_login}:</TD>
<TD><INPUT type="text" name="param01" size="32" value="{$module_data.param01}"></TD>
</TR>
<TR>
<TD>{$lng.lbl_cc_verisignl_partner}:</TD>
<TD><INPUT type="text" name="param02" size="32" value="{$module_data.param02}"></TD>
</TR>
<TR>
<TD>{$lng.lbl_verisign_server_being_used}:</TD>
<TD><SELECT name="param06">
<OPTION value="AU"{if $module_data.param06 eq 'AU'}selected{/if}>{$lng.country_AU}</OPTION>
<OPTION value="US"{if $module_data.param06 eq 'US'}selected{/if}>{$lng.country_US}</OPTION>
</SELECT></TD>
</TR>
<TR>
<TD>{$lng.lbl_cc_order_prefix}:</TD>
<TD><INPUT type="text" name="param03" size="32" value="{$module_data.param03}"></TD>
</TR>
</TABLE>
<P>
<INPUT type="submit" value="{$lng.lbl_update}">
</FORM>
</CENTER>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_cc_settings content=$smarty.capture.dialog extra="width=100%"}
