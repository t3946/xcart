{* $Id: cc_w2p.tpl,v 1.4 2004/06/24 12:46:45 max Exp $ *}
<H3>Way2Pay</H3>
{$lng.txt_cc_configure_top_text}
<P>
{capture name=dialog}
<FORM action="cc_processing.php?cc_processor={$smarty.get.cc_processor|escape:"url"}" method="post">
<CENTER>
<TABLE border="0" cellspacing="10">
<TR>
<TD>{$lng.lbl_cc_w2p_merchantid}:</TD>
<TD><INPUT type="text" name=param01 size="24" value="{$module_data.param01}"></TD>
</TR>
<TR>
<TD>{$lng.lbl_cc_testlive_mode}:</TD>
<TD>
<SELECT name="testmode">
<OPTION value=Y{if $module_data.testmode eq "Y"} selected{/if}>{$lng.lbl_cc_testlive_test}
<OPTION value=N{if $module_data.testmode eq "N"} selected{/if}>{$lng.lbl_cc_testlive_live}
</SELECT>
</TD>
</TR>

<TR>
<TD>{$lng.lbl_cc_order_prefix}:</TD>
<TD><INPUT type="text" name=param04 size="36" value="{$module_data.param04}"></TD>
</TR>
</TABLE>
<P>
<INPUT type="submit" value="{$lng.lbl_update}">
</FORM>
</CENTER>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_cc_settings content=$smarty.capture.dialog extra="width=100%"}
