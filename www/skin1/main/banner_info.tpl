{* $Id: banner_info.tpl,v 1.11.2.3 2006/08/08 08:02:59 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_banners_statistics}
{$lng.txt_banner_stats_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->
<BR>
 
{capture name=dialog}
<FORM action="banner_info.php" method="post">
<TABLE>
<TR>
	<TD>{$lng.lbl_period_from}:</TD>
	<TD>{html_select_date prefix="Start" time=$search.start_date|default:$month_begin start_year=$config.Company.start_year end_year=$config.Company.end_year}</TD>
</TR>
<TR>
    <TD>{$lng.lbl_period_to}:</TD>
    <TD>{html_select_date prefix="End" time=$search.end_date start_year=$config.Company.start_year end_year=$config.Company.end_year}</TD>
</TR>
{if $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode ne '')}
<TR>
    <TD>{$lng.lbl_partner}:</TD>
    <TD><SELECT name="search[partner]">
	<OPTION value=''{if $search.partner eq ''} selected{/if}>{$lng.lbl_all}</OPTION>
	{if $partners ne ''}
	{foreach from=$partners item=v}
	<OPTION value='{$v.login}'{if $search.partner eq $v.login} selected{/if}>{$v.login} ({$v.firstname} {$v.lastname})</OPTION>
	{/foreach}
	{/if}
	</SELECT></TD>
</TR>
{/if}
<TR>
	<TD>&nbsp;</TD>
	<TD><INPUT type="submit" value="{$lng.lbl_search}"></TD>
</TR>
</TABLE>
</FORM>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_search extra="width=100%"}

{if $banners ne ''}
<BR>

{capture name=dialog}
<TABLE width="100%" border="0" cellpadding="2" cellspacing="2">
<TR class="TableHead">
	<TD>{$lng.lbl_banner}</TD>
	<TD>{$lng.lbl_clicks}</TD>
	<TD>{$lng.lbl_views}</TD>
	<TD nowrap>{$lng.lbl_click_rate}</TD>
</TR>
{foreach from=$banners item=v}
<TR>
	<TD>{if $v.bannerid > 0}{if $usertype ne 'B' && $v.banner}<A href="partner_banners.php?bannerid={$v.bannerid}">{/if}{$v.banner|default:$lng.lbl_deleted_banner}{if $usertype ne 'B' && $v.banner}</A>{/if}{else}{$lng.lbl_default_banner}{/if}{if $v.productid > 0} ({$lng.lbl_product}: <A href="product.php?productid={$v.productid}">{$v.product|truncate:50}</A>){if $v.class eq 1}, {$lng.lbl_detailed}{elseif $v.class eq 2}, {$lng.lbl_normal}{elseif $v.class eq 3}, {$lng.lbl_compact}{/if}{/if}</TD>
    <TD align="right">{$v.clicks}</TD>
    <TD align="right">{$v.views}</TD>
	<TD align="right">{$v.click_rate}</TD>
</TR>
{/foreach}
<TR>
    <TD colspan="4" height="1"><HR size="1"></TD>
</TR>
<TR>
	<TD><B>{$lng.lbl_total}:</B></TD>
    <TD align="right">{$total.clicks}</TD>
    <TD align="right">{$total.views}</TD>
    <TD align="right">{$total.click_rate}</TD>
</TR>
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_banners_statistics extra="width=100%"}
{/if}
