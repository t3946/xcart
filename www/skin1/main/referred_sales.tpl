{* $Id: referred_sales.tpl,v 1.6.2.3 2005/04/12 07:18:57 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_referred_sales}
{$lng.txt_reffered_sales_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->
<BR>

{capture name=dialog}
<FORM action="referred_sales.php" method="post">
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
    <TD>{$lng.lbl_sku}:</TD>
    <TD><INPUT type="text" name="search[productcode]" size="20" value="{$search.productcode}"></TD>
</TR>
<TR>
    <TD>{$lng.lbl_show_top_products}</TD>
    <TD><INPUT type="checkbox" name="search[top]" value="Y"{if $search.top eq 'Y'} checked{/if}></TD>
</TR>
{if $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode ne '')}
<TR>
    <TD>{$lng.lbl_partner}:</TD>
    <TD><SELECT name="search[partner]">
	<OPTION value=''{if $search.partner eq ''} selected{/if}>{$lng.lbl_all}</OPTION>
	{foreach from=$partners item=v}
	<OPTION value='{$v.login}'{if $search.partner eq $v.login} selected{/if}>{$v.firstname} {$v.lastname}</OPTION>
	{/foreach}
	</SELECT></TD>
</TR>
{/if}
<TR>
    <TD>{$lng.lbl_status}</TD>
    <TD><SELECT name="search[status]">
	<OPTION value=''{if $search.status eq ''} selected{/if}>{$lng.lbl_all}</OPTION>
    <OPTION value='N'{if $search.status eq 'N'} selected{/if}>{$lng.lbl_pending}</OPTION>
    <OPTION value='Y'{if $search.status eq 'Y'} selected{/if}>{$lng.lbl_paid}</OPTION>
	</SELECT></TD>
</TR>
{if $search.top eq 'Y'}
<TR>
    <TD>Sort by</TD>
    <TD><SELECT name="search[sort_by]">
	<OPTION value='total'{if $search.sort_by eq 'total' || $search.status eq ''} selected{/if}>{$lng.lbl_total}</OPTION>
    <OPTION value='amount'{if $search.sort_by eq 'amount'} selected{/if}>{$lng.lbl_amount}</OPTION>
    <OPTION value='product_commission'{if $search.sort_by eq 'product_commission'} selected{/if}>{$lng.lbl_commissions}</OPTION>
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

{if $sales ne ''}
<BR>

{capture name=dialog}
<TABLE width="100%" border="0" cellspacing="2" cellpadding="2">
<TR class="TableHead">
{if ($usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode ne '')) && $search.top ne 'Y'}
    <TD rowspan="2">{$lng.lbl_partner}</TD>
    <TD rowspan="2">{$lng.lbl_partner_parent}</TD>
{/if}
	<TD rowspan="2">{$lng.lbl_product}</TD>
{if $search.top ne 'Y'}
	<TD colspan="2" align="center">{$lng.lbl_order}</TD>
{/if}
	<TD rowspan="2" align="center">{$lng.lbl_amount}</TD>
{if $config.XAffiliate.partner_allow_see_total eq 'Y' || $usertype ne 'B'}
	<TD rowspan="2" align="center">{$lng.lbl_total}</TD>
{/if}
    <TD rowspan="2" align="center">{$lng.lbl_commission}</TD>
{if $search.top ne 'Y'}
	<TD rowspan="2" align="center">{$lng.lbl_status}</TD>
{/if}
</TR>
<TR class="TableHead">
{if $search.top ne 'Y'}

    <TD align="center">#</TD> 
    <TD align="center">{$lng.lbl_date}</TD>
{/if}
</TR>
{assign var="total_amount" value=0}
{assign var="total_total" value=0}
{assign var="total_product_commissions" value=0}
{foreach from=$sales item=v}
<TR>
{if ($usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode ne '')) && $search.top ne 'Y'}
	<TD><A href="user_modify.php?user={$v.login|escape:"url"}&usertype=B">{$v.login}</A></TD>
	<TD>{if $v.parent ne ''}<A href="user_modify.php?user={$v.parent|escape:"url"}&usertype=B">{$v.parent}</A>{/if}</TD>
{/if}
	<TD>{if $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode ne '')}<A href="product_modify.php?productid={$v.productid}">{/if}{$v.product}{if $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode ne '')}</A>{/if}</TD>
{if $search.top ne 'Y'}
    <TD>{if $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode ne '')}<A href="order.php?orderid={$v.orderid}">{/if}{$v.orderid}{if $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode ne '')}</A>{/if}</TD>
	<TD nowrap>{$v.add_date|date_format:"%m/%d/%y"}</TD>
{/if}
	<TD>{$v.amount}</TD>
{math assign="total_amount" equation="x+y" x=$total_amount y=$v.amount}
{if $config.XAffiliate.partner_allow_see_total eq 'Y' || $usertype ne 'B'}
	<TD align="right" nowrap>{include file="currency.tpl" value=$v.total}</TD>
{math assign="total_total" equation="x+y" x=$total_total y=$v.total}
{/if}
	<TD align="right" nowrap>{include file="currency.tpl" value=$v.product_commission}</TD>
{math assign="total_product_commissions" equation="x+y" x=$total_product_commissions y=$v.product_commission}
{if $search.top ne 'Y'}
	<TD>{if $v.paid eq 'Y'}Paid{else}Pending{/if}</TD>
{/if}
</TR>
{/foreach}
{assign var="colspan_count" value=3}
{if ($usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode ne '')) && $search.top ne 'Y'}{math assign="colspan_count" equation="x+2" x=$colspan_count}{/if}
{if $search.top ne 'Y'}{math assign="colspan_count" equation="x+2" x=$colspan_count}{/if}
{if $usertype eq 'B' && $search.top ne 'Y'}
<TR>
	<TD colspan="{$colspan_count}">Affiliate pending commission</TD>
	<TD align="right" nowrap>{include file="currency.tpl" value=$parent_pending}</TD>
</TR>
{math assign="total_product_commissions" equation="x+y" x=$total_product_commissions y=$parent_pending|default:0}
<TR> 
    <TD colspan="{$colspan_count}">Affiliate paid commission</TD>
	<TD align="right" nowrap>{include file="currency.tpl" value=$parent_paid}</TD>
{math assign="total_product_commissions" equation="x+y" x=$total_product_commissions y=$parent_paid|default:0}
</TR>
{/if}
{if $search.top ne 'Y'}{math assign="colspan_count" equation="x+1" x=$colspan_count}{/if}
<TR>
    <TD colspan="{math equation="x+1" x=$colspan_count}" height="1"><HR size="1"></TD>
</TR>

<TR>
{assign var="colspan_count" value=1}
{if ($usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode ne '')) && $search.top ne 'Y'}{math assign="colspan_count" equation="x+2" x=$colspan_count}{/if}
{if $search.top ne 'Y'}{math assign="colspan_count" equation="x+2" x=$colspan_count}{/if}
<TD colspan="{$colspan_count}"><B>{$lng.lbl_total}:</B></TD>
	<TD>{$total_amount}</TD>
{if $config.XAffiliate.partner_allow_see_total eq 'Y' || $usertype ne 'B'}
	<TD align="right" nowrap>{include file="currency.tpl" value=$total_total|default:"0"}</TD>
{/if}
	<TD align="right" nowrap>{include file="currency.tpl" value=$total_product_commissions|default:"0"}</TD>
</TR>
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_sales extra="width=100%"}
{/if}
