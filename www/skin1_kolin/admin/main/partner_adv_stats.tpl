{* $Id: partner_adv_stats.tpl,v 1.6.2.1 2004/07/14 10:23:09 mclap Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_adv_statistics}
{$lng.txt_advertising_stats_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->
<BR>
 
{capture name=dialog}
<FORM action="partner_adv_stats.php" method="post">
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
    <TD>{$lng.lbl_campaigns}:</TD>
    <TD><SELECT name="search[campaignid]">
	<OPTION value=''{if $search.campaignid eq ''} selected{/if}>{$lng.lbl_all}</OPTION>
	{if $campaigns ne ''}
	{foreach from=$campaigns item=v}
	<OPTION value='{$v.campaignid}'{if $search.campaignid eq $v.campaignid} selected{/if}>{$v.campaign}</OPTION>
	{/foreach}
	{/if}
	</SELECT></TD>
</TR>
<TR>
	<TD>&nbsp;</TD>
	<TD><INPUT type="submit" value="Search"></TD>
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
	<TD>{$lng.lbl_campaign}</TD>
    <TD>{$lng.lbl_clicks}</TD>
    <TD>{$lng.lbl_estimated_expences}</TD>
    <TD>{$lng.lbl_acquisition_cost}</TD>
	<TD>{$lng.lbl_sales}</TD>
	<TD>{$lng.lbl_roi}</TD>
</TR>
{foreach from=$result item=v}
<TR>
	<TD><A href="partner_adv_campaigns.php?campaignid={$v.campaignid}">{$v.campaign}</A></TD>
	<TD>{$v.clicks}</TD>
    <TD align="right" nowrap>{include file="currency.tpl" value=$v.ee|default:"0"}</TD>
	<TD align="right" nowrap>{include file="currency.tpl" value=$v.acost|default:"0"}</TD>
    <TD align="right" nowrap>{include file="currency.tpl" value=$v.total|default:"0"}</TD>
    <TD>{$v.roi|default:"0"}%</TD>
</TR>
{/foreach}
<TR>
    <TD colspan="6" height="1"><HR size="1"></TD>
</TR>

<TR>
	<TD><B>{$lng.lbl_total}:</B></TD>
    <TD>{$total.clicks}</TD>
    <TD align="right" nowrap>{include file="currency.tpl" value=$total.ee|default:"0"}</TD>
    <TD align="right" nowrap>{include file="currency.tpl" value=$total.acost|default:"0"}</TD>
    <TD align="right" nowrap>{include file="currency.tpl" value=$total.total|default:"0"}</TD>
    <TD>{$total.roi|default:"0"}%</TD>
</TR>
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_advertising_campaigns extra="width=100%"} 
{/if}
