{* $Id: cc_epoch.tpl,v 1.4 2004/06/24 12:46:45 max Exp $ *}
<H3>Epoch Systems (PayCom)</H3>
{$lng.txt_cc_configure_top_text}
<P>
{capture name=dialog}
<FORM action="cc_processing.php?cc_processor={$smarty.get.cc_processor|escape:"url"}" method="post">
<CENTER>
<TABLE border="0" cellspacing="10">
<TR>
<TD>{$lng.lbl_cc_epoch_ccode}:</TD>
<TD><INPUT type="text" name=param01 size="32" value="{$module_data.param01}"></TD>
</TR>
<TR>
<TD>{$lng.lbl_cc_epoch_password}:</TD>
<TD><INPUT type="text" name=param04 size="32" value="{$module_data.param04}"></TD>
</TR>
<TR>
<TD>{$lng.lbl_cc_epoch_pcode}:</TD>
<TD><INPUT type="text" name=param02 size="32" value="{$module_data.param02}"></TD>
</TR>
<TR>
<TD>{$lng.lbl_cc_order_prefix}:</TD>
<TD><INPUT type="text" name=param03 size="32" value="{$module_data.param03}"></TD>
</TR>
</TABLE>
<P>
<INPUT type="submit" value="{$lng.lbl_update}">
</FORM>
</CENTER>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_cc_settings content=$smarty.capture.dialog extra="width=100%"}
