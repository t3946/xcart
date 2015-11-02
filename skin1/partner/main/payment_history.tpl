{* $Id: payment_history.tpl,v 1.21 2004/06/28 10:53:38 mclap Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_payment_history}
{$lng.txt_payment_history_note}<BR><BR>
 
<!-- IN THIS SECTION -->
 
{include file="dialog_tools.tpl"}
 
<!-- IN THIS SECTION -->
<BR>
 
{include file="customer/main/navigation.tpl"}
{capture name=dialog}
<FORM method="GET" action="payment_history.php" name="searchform">
<INPUT type="hidden" name="mode" value="go">
<TABLE border="0">
<TR>
<TD height="10" class="FormButton" nowrap>{$lng.lbl_search_using_date}:</TD>
<TD height="10" width="10">&nbsp;</TD>
<TD height="10" class="FormButton" nowrap><INPUT type="checkbox" value='Y' name="use_date" {if $smarty.get.use_date eq "Y" or $smarty.get.mode eq ""}checked{/if}></TD>
</TR>
<TR>
<TD height="10" class="FormButton" nowrap>{$lng.lbl_date_from}:</TD>
<TD height="10" width="10">&nbsp;</TD>
<TD>{html_select_date prefix="Start" time=$start_date start_year=$config.Company.start_year end_year=$config.Company.end_year display_days=yes}</TD>
</TR>
<TR>
<TD height="10" class="FormButton" nowrap>{$lng.lbl_date_to}:</TD>
<TD height="10" width="10">&nbsp;</TD>
<TD>{html_select_date prefix="End" time=$end_date start_year=$config.Company.start_year end_year=$config.Company.end_year display_days=yes}</TD>
</TR>
<TR>
<TD height="10" class="FormButton" nowrap>{$lng.lbl_use_paging}:</TD>
<TD height="10" width="10">&nbsp;</TD>
<TD height="10"><INPUT type="checkbox" name="use_paging" value='Y' {if $smarty.get.use_paging eq "Y" or $smarty.get.mode eq ""}checked{/if}></TD>
</TR>
<TR>
<TD colspan="3"><BR>{include file="buttons/search.tpl" href="javascript: document.searchform.submit()" js_to_href="Y"}</TD>
</TR>
</TABLE>
</FORM>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_search extra="width=100%"}
{if $smarty.get.mode ne ""}
<BR>
{capture name=dialog}
{if $payments eq ""}
{$lng.lbl_no_records_found}<BR>
{else}
<TABLE border="0" cellpadding="2" cellspacing="1">
<TR class="TableHead">
<TD><B>{$lng.lbl_date}</B></TD>
<TD><B>{$lng.lbl_amount}</B></TD>
</TR>
{section name=pi loop=$payments}
<TR>
<TD>{$payments[pi].add_date|date_format:$config.Appearance.datetime_format}</TD>
<TD>{include file="currency.tpl" value=$payments[pi].commissions}</TD>
</TR>
{/section}
</TABLE>
{/if}
<BR><B>{$lng.lbl_paid_total}: {include file="currency.tpl" value=$paid_total}</B><BR>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_payment_history extra="width=100%"}
{/if}
