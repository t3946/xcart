{* $Id: cc_verisign.tpl,v 1.3.2.1 2004/08/19 10:06:34 max Exp $ *}
<H3>VeriSign PayFlow Pro</H3>
{$lng.txt_cc_configure_top_text}
<P>
{capture name=dialog}
<FORM action="cc_processing.php?cc_processor={$smarty.get.cc_processor|escape:"url"}" method="post">
<CENTER>
<TABLE border="0" cellspacing="10">
<TR>
<TD>{$lng.lbl_cc_verisign_merchantuser}:</TD>
<TD><INPUT type="text" name="param01" size="24" value="{$module_data.param01}"></TD>
</TR>
<TR>
<TD>{$lng.lbl_cc_verisign_merchantpass}:</TD>
<TD><INPUT type="password" name="param04" size="24" value="{$module_data.param04}"></TD>
</TR>
<TR>
<TD>{$lng.lbl_cc_verisign_vendor}:</TD>
<TD><INPUT type="text" name="param02" size="24" value="{$module_data.param02}"></TD>
</TR>
<TR>
<TD>{$lng.lbl_cc_verisign_partner}:</TD>
<TD><INPUT type="text" name="param03" size="24" value="{$module_data.param03}"></TD>
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
<TD><INPUT type="text" name="param05" size="24" value="{$module_data.param05}"></TD>
</TR>
<TR>
<TD>{$lng.lbl_cc_testlive_mode}:</TD>
<TD><SELECT name="testmode">
<OPTION value="Y"{if $module_data.testmode eq "Y"} selected{/if}>{$lng.lbl_cc_testlive_test}
<OPTION value="N"{if $module_data.testmode eq "N"} selected{/if}>{$lng.lbl_cc_testlive_live}
</SELECT>
</TD>
</TR>
<TR>
<TD colspan="2">
<INPUT type="submit" value="{$lng.lbl_update}">
</TD></TR>
</TABLE>
</FORM>
</CENTER>

{$lng.txt_cc_verisign_note}
<BR>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_cc_settings content=$smarty.capture.dialog extra="width=100%"}
