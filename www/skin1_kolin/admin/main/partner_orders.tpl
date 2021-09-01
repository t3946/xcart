{* $Id: partner_orders.tpl,v 1.7.2.3 2005/04/12 07:18:56 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_partners_orders}
{$lng.txt_partner_orders_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}
<BR>

<!-- IN THIS SECTION -->

{include file="customer/main/navigation.tpl"}
{assign var="found" value="N"}
{capture name=dialog}
<FORM method="post" action="partner_orders.php" name="searchform">
<INPUT type="hidden" name="mode" value="">
<TABLE border="0">
<TR>
<TD height="10" class="FormButton" nowrap>{$lng.lbl_date_from}:</TD>
<TD height="10" width="10">&nbsp;</TD>
<TD nowrap>{html_select_date prefix="Start" time=$search.start_date|default:$month_begin start_year=$config.Company.start_year end_year=$config.Company.end_year}</TD>
</TR>
<TR>
<TD height="10" class="FormButton" nowrap>{$lng.lbl_date_to}:</TD>
<TD height="10" width="10">&nbsp;</TD>
<TD nowrap>{html_select_date prefix="End" time=$search.end_date start_year=$config.Company.start_year end_year=$config.Company.end_year}</TD>
</TR>
<TR>
	<TD height="10" class="FormButton" nowrap>{$lng.lbl_order_id}:</TD>
	<TD height="10" width="10">&nbsp;</TD>
	<TD nowrap><INPUT type="text" size="8" name="search[orderid]" value="{$search.orderid}"></TD>
</TR>
<TR>
	<TD height="10" class="FormButton" nowrap>{$lng.lbl_partner}:</TD>
	<TD height="10" width="10">&nbsp;</TD>
	<TD nowrap><SELECT name="search[login]">
	<OPTION value=''{if $search.login eq ''} selected{/if}>{$lng.lbl_all}</OPTION>
	{if $partners ne ''}
	{foreach from=$partners item=v}
	<OPTION value="{$v.login}"{if $search.login eq $v.login} selected{/if}>{$v.login}</OPTION>
	{/foreach}
	{/if}
	</SELECT></TD>
</TR>
<TR>
    <TD height="10" class="FormButton" nowrap>{$lng.lbl_order_status}</TD>
    <TD height="10" width="10">&nbsp;</TD>
    <TD nowrap><SELECT name="search[status]">
		<OPTION value=""{if $search.status eq ""} selected{/if}>{$lng.lbl_all}</OPTION>
		<OPTION value="I"{if $search.status eq "I"} selected{/if}>{$lng.lbl_not_finished}</OPTION>
		<OPTION value="Q"{if $search.status eq "Q"} selected{/if}>{$lng.lbl_queued}</OPTION>
		<OPTION value="P"{if $search.status eq "P"} selected{/if}>{$lng.lbl_processed}</OPTION>
		<OPTION value="B"{if $search.status eq "B"} selected{/if}>{$lng.lbl_backordered}</OPTION>
		<OPTION value="D"{if $search.status eq "D"} selected{/if}>{$lng.lbl_declined}</OPTION>
		<OPTION value="F"{if $search.status eq "F"} selected{/if}>{$lng.lbl_failed}</OPTION>
		<OPTION value="C"{if $search.status eq "C"} selected{/if}>{$lng.lbl_complete}</OPTION>
	</SELECT></TD>
</TR>
<TR> 
    <TD height="10" class="FormButton" nowrap>{$lng.lbl_payment_status}</TD>
    <TD height="10" width="10">&nbsp;</TD>
    <TD nowrap><SELECT name="search[paid]">
	<OPTION value=''{if $search.paid eq ''} selected{/if}>{$lng.lbl_all}</OPTION>
    <OPTION value='N'{if $search.paid eq 'N'} selected{/if}>{$lng.lbl_pending}</OPTION>
	<OPTION value='A'{if $search.paid eq 'A'} selected{/if}>{$lng.lbl_approved}</OPTION>
    <OPTION value='Y'{if $search.paid eq 'Y'} selected{/if}>{$lng.lbl_paid}</OPTION>
    </SELECT></TD>
</TR>
<TR>
<TD height="10" class="FormButton">{$lng.lbl_csv_delimiter}:</TD>
<TD height="10" width="10">&nbsp;</TD>
<TD height="10">{include file="provider/main/ie_delimiter.tpl"}</TD>
</TR>
<TR>
<TD colspan="3"><BR>
<INPUT type="button" value="{$lng.lbl_search}" onclick="javascript: document.searchform.mode.value='go'; document.searchform.submit();">&nbsp;&nbsp;
<INPUT type="button" value="{$lng.lbl_export}" onclick="javascript: document.searchform.mode.value='export'; document.searchform.submit();">
</TD>
</TR>
</TABLE>
</FORM>
{$lng.txt_partner_orders_bottom}
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_search extra="width=100%"}

<BR>

{if $orders ne ''}
{capture name=dialog}
<TABLE border="0" cellpadding="2" cellspacing="2" width="100%">
<TR class="TableHead">
	<TD nowrap rowspan="2">{$lng.lbl_partner}</TD>
    <TD nowrap colspan="2" align="center">{$lng.lbl_order}</TD>
    <TD nowrap rowspan="2" align="center">{$lng.lbl_total}</TD>
    <TD nowrap rowspan="2" align="center">{$lng.lbl_commission}</TD>
    <TD nowrap rowspan="2" align="center">{$lng.lbl_owner}</TD>
    <TD nowrap colspan="2" align="center">{$lng.lbl_status}</TD>
</TR>
<TR class="TableHead">
    <TD nowrap align="center">#</TD>
    <TD nowrap align="center">{$lng.lbl_date}</TD>
    <TD nowrap align="center">{$lng.lbl_order}</TD>
    <TD nowrap align="center">{$lng.lbl_commission}</TD>
</TR>
{foreach from=$orders item=v}
<TR>
	<TD><A href="user_modify.php?user={$v.login|escape:"url"}&usertype=B">{$v.login}</A></TD>
    <TD><A href="order.php?orderid={$v.orderid}">{$v.order_prefix}{$v.orderid}</A></TD>
	<TD nowrap>{$v.date|date_format:"%m/%d/%y"}</TD>
	<TD align="right" nowrap>{include file="currency.tpl" value=$v.subtotal}</TD>
	<TD align="right" nowrap>{include file="currency.tpl" value=$v.commissions}</TD>
	<TD nowrap>{if $v.affiliate ne ''}{$lng.lbl_child} ({$v.affiliate}){else}{$lng.lbl_affiliate}{/if}</TD>
	<TD>{include file="main/order_status.tpl" status=$v.order_status mode="static" name="status"}</TD>
	<TD>{if $v.paid eq 'Y'}{$lng.lbl_paid}{elseif $v.paid eq 'A'}{$lng.lbl_approved}{else}{$lng.lbl_pending}{/if}</TD>
</TR>
{/foreach}
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_partners_orders extra="width=100%"} 
{/if}
