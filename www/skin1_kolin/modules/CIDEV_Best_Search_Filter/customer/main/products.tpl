{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

<form name="cidev_selected_fv_str_form" method="post" action="">
 <input type="hidden" id="cidev_selected_fv_str" name="cidev_selected_fv_str" value="{$cidev_selected_fv_str}">			{* for filter menu *}
 {if $config.CIDEV_Best_Search_Filter.cidev_disable_manufacturers ne "Y"}
 <input type="hidden" id="cidev_selected_manuf_str" name="cidev_selected_manuf_str" value="{$cidev_selected_manuf_str}">	{* for filter menu *}
 {/if}
 <input type="hidden" id="cidev_search_result_total_items" name="cidev_search_result_total_items" value="{$total_items}">	{* for recalc total items *}
 <input type="hidden" id="cidev_search_result_first_item" name="cidev_search_result_first_item" value="{$first_item}">		{* for recalc total items *}
 <input type="hidden" id="cidev_search_result_last_item" name="cidev_search_result_last_item" value="{$last_item}">		{* for recalc total items *}
</form>

{if $products ne ""}
 {include file="customer/main/navigation.tpl"}
 {include file="customer/main/products.tpl" products=$products}
 {include file="customer/main/navigation.tpl"}
{else}
 {$lng.lbl_cidev_no_products}
{/if}
