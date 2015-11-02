{* $Id: categories.tpl,v 1.26 2005/11/17 06:55:37 max Exp $ *}
{if $active_modules.Fancy_Categories ne ""}
{capture name=menu}
{include file="modules/Fancy_Categories/categories.tpl" cat_start=0 cat_end=500}
{assign var="fc_cellpadding" value="0"}
{/capture}
{ include file="menu.tpl" menu_title=$lng.lbl_category_title menu_content=$smarty.capture.menu cellpadding=$fc_cellpadding}
{else}

{if $config.General.root_categories eq "Y"}

  {assign var="show_category_filter" value="N"}
  {if $active_modules.CIDEV_Best_Search_Filter ne "" && (($current_category.categoryid gt 0 && $current_category.main_order_by le 500) || $brandid gt 0)}
	{assign var="show_category_filter" value="Y"}
  {/if}

  {if $show_category_filter eq "Y"}
    {if $cidev_subcategories_products_count ne ""}
	<table width="100%" cellpadding="2" cellspacing="2" style="background-color: #FFFFFF;">
	{foreach from=$subcategories item=subcat}
	 {foreach from=$cidev_subcategories_products_count item=v}
{*
	  {if ($subcat.product_count_global || $subcat.subcategory_count) && $v.categoryid eq $subcat.categoryid && $v.count_products gt 0}
*}
	  {if $v.categoryid eq $subcat.categoryid && $v.count_products gt 0}
	<tr>
	<td style="background-color: #FEF6F3; padding-left: 10px; padding-right: 10px;"><font class="CategoriesList"><a class="VertMenuItems" href="home.php?cat={ $subcat.categoryid }">{ $subcat.category|escape }</font></a> ({$v.count_products})</td>
	</tr>
	  {/if}
	 {/foreach}
	{/foreach}
	</table>
    {/if}
  {else}
	<table width="100%" cellpadding="2" cellspacing="2" style="background-color: #FFFFFF;">
	{foreach from=$categories item=c}
	{if $c.order_by ge 0 && $c.order_by le 500}
	<tr>
	<td style="background-color: #FEF6F3; padding-left: 10px; padding-right: 10px;">
	    {if $c.categoryid eq ''}
        	<font class="CategoriesList">
	            <a href="home.php?scatid={$c.scatid}&amp;keyphrase={$c.keyphrase}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a>
	        </font>
        	<br />
	    {else}
        	<font class="CategoriesList">{if $c.categoryid ne $smarty.get.cat}<a href="home.php?cat={$c.categoryid}" class="VertMenuItems">{/if}{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}{if $c.categoryid ne $smarty.get.cat}</a>{/if}</font><br />
	    {/if}
	</td>
	</tr>
	{/if}
	{/foreach}
	</table>
  {/if}

{else} {foreach from=$subcategories item=c key=catid}
{if $c.order_by ge 0 && $c.order_by le 500}
    {if $c.categoryid eq ''}
        <font class="CategoriesList">
            <a href="home.php?scatid={$c.scatid}&amp;keyphrase={$c.keyphrase}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a>
        </font>
        <br />
    {else}
        <font class="CategoriesList"><a href="home.php?cat={$catid}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a></font><br />
    {/if}
{/if}
{/foreach}
{/if}

{/if}


{* ------------------------------- Filters ------------------------------- *}


{if ($current_category.categoryid gt 0 && $current_category.main_order_by le 500) || $brandid gt 0}

{if $filter_found_fv_ids ne "" || $filter_selected_and_found_brands ne "" || $filter_prices ne ""}
{assign var="show_clear_all_button" value="N"}

<script type="text/javascript" src="{$SkinDir}/customer/popup_open.js"></script>

<form name="f_searchform" action="{$canonical_url}/" method="GET">
<input type="hidden" name="f_mode" value="f_search" id="f_mode" >

<br />
{capture name=menu_filter}
 {assign var="filter_name" value=""}
 <table border="0" cellpadding="0" cellspacing="0" width="100%">

 {if $cidev_filters_tree_sorted ne ""}
  {foreach from=$cidev_filters_tree_sorted item=v}
    {if $v.filter_values ne ""}

     {assign var="row_conter" value="0"}

     {foreach from=$v.filter_values item=tree_filter_values}

      {if $tree_filter_values.found eq 'Y' || $tree_filter_values.selected eq "Y"}

	    {if $filter_name ne $v.f_name}
		    {if $filter_name ne ""}
		        <tr><td colspan="2">&nbsp;</td><tr>
		    {/if}
        	<tr><td colspan="2"><B>{$v.f_name}:</B></td><tr>
	        {assign var="filter_name" value=$v.f_name}
	    {/if}

        {if $row_conter lt $v.show_N_fvalues}
	<tr>
	<td width="5">
		<input name="fv_ids[{$tree_filter_values.fv_id}]" id="fv_id_{$tree_filter_values.fv_id}" value="Y" type="checkbox"
		{if $tree_filter_values.selected eq 'Y'}
			checked="checked"
			{assign var="show_clear_all_button" value="Y"}
		{/if}
		>
	</td>
	<td {if $tree_filter_values.selected eq 'Y' && $tree_filter_values.selected_and_found ne "Y"}style="color: #cccccc;"{/if}>{$tree_filter_values.fv_name} {if $filter_found_fv_ids_count[$tree_filter_values.fv_id] ne ""}({$filter_found_fv_ids_count[$tree_filter_values.fv_id]}){/if}</td>
	</tr>
	{/if}

	{math equation="x+1" x=$row_conter assign="row_conter"}

      {/if}
     {/foreach}

     {if $row_conter gt $v.show_N_fvalues}
        <tr>
        <td colspan="2" align="right">

<a class="simple-button" target="_blank" title="Show more" onclick="javascript: popupOpen('cidev_show_more_filters.php?target=show_more&filter=fvalues&f_id={$v.f_id}', '{$v.f_name}'); return false;" href="cidev_show_more_filters.php?target=show_more&filter=fvalues&f_id={$v.f_id}"><span>Show more</span></a>

        </td>
        <tr>
     {/if}

    {/if}
  {/foreach}
 {/if}


 {if $filter_selected_and_found_brands ne ""}
  {if $filter_name ne ""}
  <tr><td colspan="2">&nbsp;</td><tr>
  {/if}
  <tr><td colspan="2"><B>Brand:</B></td><tr>

  {assign var="row_conter" value="0"}

  {foreach from=$filter_selected_and_found_brands item=v key=k}

   {if $row_conter lt $show_N_brands}
   <tr>
    <td width="5">
	<input name="b_ids[{$v.brandid}]" id="b_id_{$v.brandid}" value="Y" type="checkbox"
                {if $v.selected eq 'Y'}
                        checked="checked"
			{assign var="show_clear_all_button" value="Y"}
                {/if}
	>
    </td>
    <td {if $v.selected eq 'Y' && $v.selected_and_found ne "Y"}style="color: #cccccc;"{/if}>{$v.brand} ({$v.count_products})</td>
   </tr>
   {/if}

   {math equation="x+1" x=$row_conter assign="row_conter"}

  {/foreach}

  {if $row_conter gt $show_N_brands}
	<tr>
	<td colspan="2" align="right">

<a class="simple-button" target="_blank" title="Show more" onclick="javascript: popupOpen('cidev_show_more_filters.php?target=show_more&filter=brand', 'Brand'); return false;" href="cidev_show_more_filters.php?target=show_more&filter=brand"><span>Show more</span></a>

	</td>
	<tr>
  {/if}

 {/if}


 {if $filter_prices ne ""}
  <tr><td colspan="2">&nbsp;</td><tr>
  <tr><td colspan="2"><B>Price:</B></td><tr>

  {if $filter_max_price_selected gt "0"}


<script language="JavaScript" type="text/javascript">
<!--
{literal}
function uncheckAll_prices(flag, form, prefix) {
        if (!form)
                return;

        if (prefix)
                var reg = new RegExp("^"+prefix, "");
        for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].type == "checkbox" && (!prefix || form.elements[i].name.search(reg) == 0) && !form.elements[i].disabled){
                        form.elements[i].checked = false;
                }
        }
}
{/literal}
-->
</script>


	<tr>
	<td>
		<input name="price_ids_range" id="price_ids_range" value="Y" type="checkbox" checked="checked" onclick="javascript: uncheckAll_prices(true, document.f_searchform, 'p_ids');" >
		<input name="filter_min_price_selected" value="{$filter_min_price_selected}" type="hidden" >
		<input name="filter_max_price_selected" value="{$filter_max_price_selected}" type="hidden" >
	</td>
	<td>
		{$config.General.currency_symbol}{$filter_min_price_selected} - {$config.General.currency_symbol}{$filter_max_price_selected}
		{assign var="show_clear_all_button" value="Y"}
	</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td><tr>
  {/if}

  {foreach from=$filter_prices item=v key=k}
   <tr>
    <td width="5">
        <input name="p_ids[{$k}]" id="p_id_{$k}" value="Y" type="checkbox"
                {if $v.selected eq 'Y'}
                        checked="checked"
                        {assign var="show_clear_all_button" value="Y"}
                {/if}

		{if $v.count_products eq '0'}disabled="disabled"
		{else}
			{if $filter_max_price_selected gt "0"}
				onclick="javascript: document.getElementById('price_ids_range').checked=false;"
			{/if}
		{/if}
        >
    </td>
    <td {if $v.count_products eq '0'}style="color: #cccccc;"{/if *}>{$config.General.currency_symbol}{$v.min_price} - {$config.General.currency_symbol}{$v.max_price} ({$v.count_products})</td>
   </tr>
  {/foreach}
 {/if}


<tr>
<td colspan="2"><br />
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td align="left">
{if ($filter_found_fv_ids ne "" || $filter_selected_and_found_brands ne "") || $brandid gt 0}
	<input type="submit" value="Show" >
	</td>
{/if}
	<td align="right">
	{if $show_clear_all_button eq "Y"}
	<input type="submit" value="Clear All" onclick="javascript: $('#f_mode').val('clear');" >
	{/if}
	</td>
<tr>
</table>
</td>
</tr>

 </table>
{/capture}
{ include file="menu.tpl" menu_title="Shop By" menu_content=$smarty.capture.menu_filter}

</form>
{/if}
{/if}
{* ------------------------------- Filters ------------------------------- *}


{capture name=menu}
{if $active_modules.Fancy_Categories ne ""}
{include file="modules/Fancy_Categories/categories.tpl" cat_start=501 cat_end=50000}
{assign var="fc_cellpadding" value="0"}
{else}
{if $config.General.root_categories eq "Y"}
{foreach from=$categories item=c}
{if $c.order_by gt 500}
    {if $c.categoryid eq ''}
        <font class="CategoriesList">
            <a href="home.php?scatid={$c.scatid}&amp;keyphrase={$c.keyphrase}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a>
        </font>
        <br />
    {else}
        <font class="CategoriesList">{if $c.categoryid ne $smarty.get.cat}<a href="home.php?cat={$c.categoryid}" class="VertMenuItems">{/if}{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}{if $c.categoryid ne $smarty.get.cat}</a>{/if}</font><br />
    {/if}
{/if}
{/foreach}
{else} {foreach from=$subcategories item=c key=catid}
{if $c.order_by gt 500}
    {if $c.categoryid eq ''}
        <font class="CategoriesList">
            <a href="home.php?scatid={$c.scatid}&amp;keyphrase={$c.keyphrase}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a>
        </font>
        <br />
    {else}
        <font class="CategoriesList"><a href="home.php?cat={$catid}" class="VertMenuItems">{$c.category}</a></font><br />
    {/if}
{/if}
{/foreach}
{/if}
{/if}
{/capture}
<br />
{if $smarty.capture.menu ne ""}
{ include file="menu.tpl" menu_title=$lng.lbl_information menu_content=$smarty.capture.menu cellpadding=$fc_cellpadding}
{/if}
