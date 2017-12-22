{* $Id: partner_plans_edit.tpl,v 1.11 2004/06/18 07:26:22 max Exp $ *}
{include file="main/popup_product_js.tpl"}
{include file="page_title.tpl" title=$lng.lbl_affiliate_plan_management}
{$lng.txt_affiliate_plan_manement_note}<BR><BR>

{capture name=dialog}
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0">
<TR>
<TD valign="top"><H2>{$lng.lbl_affiliate_plan}: {$partner_plan_info.plan_title|default:$lng.lbl_new}</H2></TD>
<TD align="right" valign="top">{include file="buttons/button.tpl" button_title=$lng.lbl_list_all_affiliate_plans href="partner_plans.php"}</TD>
</TR>
</TABLE>
<TABLE border="0" cellpadding="2" cellspacing="2" width="100%">
<FORM action="partner_plans.php" name="productcommissionsform" method="POST">
<INPUT type="hidden" name="form" value="products">
<INPUT type="hidden" name="mode" value="modify">
<INPUT type="hidden" name="planid" value="{$smarty.get.planid|escape:"html"}">

<TR>
<TD colspan="4" class="TopLabel">
<TABLE border="0" cellpadding="0" cellspacing="0" width="100%">
<TR><TD colspan="2">
<A name="products"><B><FONT class="ProductDetailsTitle">{$lng.lbl_commission_rates_on_products}</FONT></B>
</TD></TR>
<TR><TD class="Line" height="1" colspan="2"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" border="0"></TD></TR>
<TR><TD colspan="2">&nbsp;</TD></TR>
</TABLE>
</TD>
</TR>


<TR>
<TD class="TableHead" width="5" nowrap>#</TD>
<TD class="TableHead" width="90%" nowrap>{$lng.lbl_product}</TD>
<TD class="TableHead" width="10%" nowrap colspan="4">{$lng.lbl_commission_rate}</TD>
</TR>
{assign var="count" value=0}
{capture name=products_commissions}
{section name=comm loop=$partner_plans_commissions}
{if $partner_plans_commissions[comm].item_type eq "P"}
{math assign="count" equation="x+1" x=$count}
{if $bgcolor eq "" and $iteration}
{assign var="bgcolor" value="bgcolor=#EEEEEE"}
{else}
{assign var="bgcolor" value=""}
{/if}
<TR {$bgcolor}>
<TD>{$partner_plans_commissions[comm].item_id}</TD>
<TD class="ItemsList">
<INPUT type="checkbox" name="productid[]" value="{$partner_plans_commissions[comm].item_id}">
<A href="product_modify.php?productid={$partner_plans_commissions[comm].item_id}">{$partner_plans_commissions[comm].product}</A></TD>
<TD><INPUT type="text" name="products[{$partner_plans_commissions[comm].item_id}][commission]" size="10" maxlength="13" value="{$partner_plans_commissions[comm].commission}"></TD>
<TD><SELECT name="products[{$partner_plans_commissions[comm].item_id}][commission_type]">
<OPTION value="%"{if $partner_plans_commissions[comm].commission_type eq "%"} selected{/if}>%</OPTION>
<OPTION value="$"{if $partner_plans_commissions[comm].commission_type eq "$"} selected{/if}>$</OPTION>
</SELECT>
</TD>
</TR>
{assign var="iteration" value="1"}
{/if}
{/section}
{if $count > 0}
<TR>
	<TD colspan="3"><INPUT type="button" value="{$lng.lbl_delete_selected}" onclick="document.productcommissionsform.mode.value='delete_rate';document.productcommissionsform.submit();"></TD>
</TR>
{/if}
{/capture}
{if $smarty.capture.products_commissions}
{$smarty.capture.products_commissions}
{else}
<TR>
<TD colspan="4" align="center">{$lng.txt_no_products_commission}</TD>
</TR>
{/if}

<TR><TD colspan="4">&nbsp;</TD></TR>

<INPUT type="hidden" name="newproduct">

<TR>
<TD colspan="2">
{$lng.lbl_product} #:
<INPUT type="text" name="product_ids" size="30" readonly>
<INPUT type="button" value="{$lng.lbl_find_products_}"  onClick="popup_product('productcommissionsform.product_ids', 'productcommissionsform.newproduct');">
</TD>
<TD><INPUT type="text" name="new_product_commission" size="10" maxlength="13" value="0.00"></TD>
<TD><SELECT name="new_product_commission_type">
<OPTION value="%" selected>%</OPTION>
<OPTION value="$">$</OPTION>
</SELECT>
</TD>
</TR>

<TR>
<TD colspan="4">
<INPUT type="submit" value="Update">
</TD>
</TR>

</FORM>

<FORM action="partner_plans.php" name="categorycommissionsform" method="POST">
<INPUT type="hidden" name="form" value="categories">
<INPUT type="hidden" name="mode" value="modify">
<INPUT type="hidden" name="planid" value="{$smarty.get.planid|escape:"html"}">

<TR><TD colspan="4"><BR><BR></TD></TR>

<TR>
<TD colspan="4" class="TopLabel">
<TABLE border="0" cellpadding="0" cellspacing="0" width="100%">
<TR><TD colspan="2">
<A name="categories"><B><FONT class="ProductDetailsTitle">{$lng.lbl_commission_rates_on_categories}</FONT></B>
</TD></TR>
<TR><TD class="Line" height="1" colspan="2"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" border="0"></TD></TR>
<TR><TD colspan="2">&nbsp;</TD></TR>
</TABLE>
</TD>
</TR>


<TR>
<TD class="TableHead" width="5" nowrap>#</TD>
<TD class="TableHead" width="90%" nowrap>{$lng.lbl_category}</TD>
<TD class="TableHead" width="10%" nowrap colspan="2">{$lng.lbl_commission_rate}</TD>
</TR>

{assign var="iteration" value=""}
{assign var="count" value=0}

{capture name=products_commissions}
{section name=comm loop=$partner_plans_commissions}
{if $partner_plans_commissions[comm].item_type eq "C"}
{math assign="count" equation="x+1" x=$count}
{if $bgcolor eq "" and $iteration}
{assign var="bgcolor" value="bgcolor=#EEEEEE"}
{else}
{assign var="bgcolor" value=""}
{/if}
<TR {$bgcolor}>
<TD>{$partner_plans_commissions[comm].item_id}</TD>
<TD class="ItemsList">
<INPUT type="checkbox" name="categoryid[]" value="{$partner_plans_commissions[comm].item_id}">
<A href="category_modify.php?cat={$partner_plans_commissions[comm].item_id}">{$partner_plans_commissions[comm].category}</A></TD>
<TD><INPUT type="text" name="categories[{$partner_plans_commissions[comm].item_id}][commission]" size="10" maxlength="13" value="{$partner_plans_commissions[comm].commission}"></TD>
<TD><SELECT name="categories[{$partner_plans_commissions[comm].item_id}][commission_type]">
<OPTION value="%"{if $partner_plans_commissions[comm].commission_type eq "%"} selected{/if}>%</OPTION>
<OPTION value="$"{if $partner_plans_commissions[comm].commission_type eq "$"} selected{/if}>$</OPTION>
</SELECT>
</TD>
</TR>
{assign var="iteration" value="1"}
{/if}
{/section}
{if $count > 0}
<TR> 
    <TD colspan="4"><INPUT type="button" value="{$lng.lbl_delete_selected}" onclick="document.categorycommissionsform.mode.value='delete_rate';document.categorycommissionsform.submit();"></TD>
</TR>
{/if}
{/capture}
{if $smarty.capture.products_commissions}
{$smarty.capture.products_commissions}
{else}
<TR>
<TD colspan="4" align="center">{$lng.txt_no_categories_commissions}</TD>
</TR>
{/if}

<TR><TD colspan="4">&nbsp;</TD></TR>

<TR>
<TD colspan="2">
<SELECT name="new_categoryid">
<OPTION value="">{$lng.lbl_please_select_category}</OPTION>
{section name=cat_num loop=$allcategories}
<OPTION value="{$allcategories[cat_num].categoryid}">{$allcategories[cat_num].category_path}</OPTION>
{/section}
</SELECT>
</TD>
<TD><INPUT type="text" name="new_product_commission" size="10" maxlength="13" value="0.00"></TD>
<TD><SELECT name="new_product_commission_type">
<OPTION value="%" selected>%</OPTION>
<OPTION value="$">$</OPTION>
</SELECT>
</TD>
</TR>


<TR>
<TD colspan="4">
<INPUT type="submit" value="{$lng.lbl_update}">
</TD>
</TR>

</FORM>

<FORM action="partner_plans.php" name="generalcommissionsform" method="POST">
<INPUT type="hidden" name="form" value="general">
<INPUT type="hidden" name="mode" value="modify">
<INPUT type="hidden" name="planid" value="{$smarty.get.planid|escape:"html"}">

<TR><TD colspan="4"><BR><BR></TD></TR>

<TR>
<TD colspan="4" class="TopLabel">
<TABLE border="0" cellpadding="0" cellspacing="0" width="100%">
<TR><TD colspan="2">
<A name="general"><B><FONT class="ProductDetailsTitle">{$lng.lbl_aff_plans_general_settings}</FONT></B>
</TD></TR>
<TR><TD class="Line" height="1" colspan="2"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" border="0"></TD></TR>
<TR><TD colspan="2">&nbsp;</TD></TR>
</TABLE>
</TD>
</TR>


<TR>
<TD colspan="2" align="right">{$lng.lbl_basic_commission_rate}:</TD>
<TD><INPUT type="text" name="basic_commission" size="10" maxlength="13" value="{$general_commission.commission|default:"0.00"}"></TD>
<TD><SELECT name="basic_commission_type">
<OPTION value="%"{if $general_commission.commission_type eq "%"} selected{/if}>%</OPTION>
<OPTION value="$"{if $general_commission.commission_type eq "$"} selected{/if}>$</OPTION>
</SELECT>
</TD>
</TR>

<TR>
<TD colspan="2" align="right">{$lng.lbl_minimum_commission_payment}:</TD>
<TD><INPUT type="text" name="min_paid" size="10" maxlength="13" value="{$partner_plan_info.min_paid|default:"0.00"}"></TD>
</TR>


<TR>
<TD colspan="4"><BR>
<INPUT type="submit" value="{$lng.lbl_update}">
</TD>
</TR>


</FORM>
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_affiliate_plan_management extra="width=100%"}
