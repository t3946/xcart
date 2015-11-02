{* $Id: partner_top_performers.tpl,v 1.5 2004/05/28 12:20:58 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_top_performers}
{$lng.txt_top_performers_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->
<BR>
 
{capture name=dialog}
<FORM action="partner_top_performers.php" method="post">
<TABLE>
<TR>
	<TD>{$lng.lbl_period_from}:</TD>
	<TD>{html_select_date prefix="Start" time=$search.start_date|default:$month_begin start_year=$config.Company.start_year end_year=$config.Company.end_year}</TD>
</TR>
<TR>
    <TD>{$lng.lbl_period_to}:</TD>
    <TD>{html_select_date prefix="End" time=$search.end_date start_year=$config.Company.start_year end_year=$config.Company.end_year}</TD>
</TR>
<TR>
    <TD>{$lng.lbl_report_by}:</TD>
    <TD><SELECT name="search[report]">
	<OPTION value='login'{if $search.report eq 'login'} selected{/if}>{$lng.lbl_affilaites}</OPTION>
    <OPTION value='referer'{if $search.report eq 'referer'} selected{/if}>{$lng.lbl_referrer}</OPTION>
	</SELECT></TD>
</TR>
<TR>
    <TD>{$lng.lbl_sort_by}:</TD>
    <TD><SELECT name="search[sort]">
    <OPTION value='clicks'{if $search.sort eq 'clicks'} selected{/if}>{$lng.lbl_clicks}</OPTION>
    <OPTION value='sales'{if $search.sort eq 'sales'} selected{/if}>{$lng.lbl_sales}</OPTION>
    </SELECT></TD>
</TR>
<TR>
	<TD>&nbsp;</TD>
	<TD><INPUT type="submit" value="{$lng.lbl_search}"></TD>
</TR>
</TABLE>
</FORM>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_search extra="width=100%"}

<BR>

{if $result ne ''}
{capture name=dialog}
<TABLE border="0" cellspacing="2" cellpadding="2">
<TR class="TableHead">
	<TD>{if $search.report eq 'login'}{$lng.lbl_affilaites}{else}{$lng.lbl_referrer}{/if}</TD>
    <TD>{$lng.lbl_clicks}</TD>
    <TD>{$lng.lbl_sales_number}</TD>
    <TD>{$lng.lbl_sales}</TD>
</TR>
{foreach from=$result item=v}
<TR>
	<TD>{$v.name|default:$lng.lbl_unknown}</TD>
	<TD>{$v.clicks}</TD>
	<TD>{$v.num_sales}</TD>
    <TD align="right" nowrap>{include file="currency.tpl" value=$v.sales|default:"0"}</TD>
</TR>
{/foreach}
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_top_performers extra="width=100%"} 
{/if}
