{* $Id: cc_datatrans.tpl,v 1.3 2004/06/24 12:46:45 max Exp $ *}
<H3>DataTrans</H3>
{$lng.txt_cc_configure_top_text}
<P>
{capture name=dialog}
<FORM action="cc_processing.php?cc_processor={$smarty.get.cc_processor|escape:"url"}" method="post">
<CENTER>
<TABLE border="0" cellspacing="10">
<TR>
<TD>{$lng.lbl_cc_datatrans_merchantid}:</TD>
<TD><INPUT type="text" name="param01" size="32" value="{$module_data.param01}"></TD>
</TR>

<TR>
<TD>{$lng.lbl_cc_datatrans_server1}:</TD>
<TD><INPUT type="text" name="param02" size="32" value="{$module_data.param02}"></TD>
</TR>

<TR>
<TD>{$lng.lbl_cc_datatrans_server2}:</TD>
<TD><INPUT type="text" name="param06" size="32" value="{$module_data.param06}"></TD>
</TR>

<TR>
<TD>{$lng.lbl_cc_datatrans_java}:</TD>
<TD><INPUT type="text" name="param05" size="32" value="{$module_data.param05}"><BR>
{$lng.lbl_cc_datatrans_java_note}
</TD>
</TR>

<TR>
<TD>{$lng.lbl_cc_currency}:</TD>
<TD>
<SELECT name="param03">
<OPTION value="CHF"{if $module_data.param03 eq "CHF"} selected{/if}>Swiss Franc
<OPTION value="EUR"{if $module_data.param03 eq "EUR"} selected{/if}>Euro
<OPTION value="USD"{if $module_data.param03 eq "USD"} selected{/if}>US Dollar
</SELECT>
</TD>
</TR>


<TR>
<TD>{$lng.lbl_cc_order_prefix}:</TD>
<TD><INPUT type="text" name="param04" size="32" value="{$module_data.param04}"></TD>
</TR>

</TABLE>
<P>
<INPUT type="submit" value="{$lng.lbl_update}">
</FORM>
</CENTER>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_cc_settings content=$smarty.capture.dialog extra="width=100%"}
