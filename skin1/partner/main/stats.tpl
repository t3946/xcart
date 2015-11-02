{* $Id: stats.tpl,v 1.12 2004/05/28 12:21:15 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_summary_statistics}
{$lng.txt_summary_stats_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->
<BR>

{capture name=dialog}
<TABLE border="0" cellpadding="0" cellspacing="2">
<TR>
<TD nowrap><B>{$lng.lbl_total_sales}</B></TD>
<TD>{$stats_info.total_sales}</TD>
</TR>
<TR>
<TD nowrap><B>{$lng.lbl_total_unapproved_sales}</B></TD>
<TD>{$stats_info.unapproved_sales}</TD>
</TR>
<TR>
<TD nowrap><B>{$lng.lbl_pending_sale_commissions}</B></TD>
<TD>{include file="currency.tpl" value=$stats_info.pending_commissions}</TD>
</TR>
<TR>
<TD nowrap><B>{$lng.lbl_approved_sale_commissions}</B></TD>
<TD>{include file="currency.tpl" value=$stats_info.approved_commissions}</TD>
</TR>
<TR>
<TD nowrap><B>{$lng.lbl_paid_sales_commissions}</B></TD>
<TD>{include file="currency.tpl" value=$stats_info.paid_commissions}</TD>
</TR>
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_summary_statistics extra="width=100%"}
