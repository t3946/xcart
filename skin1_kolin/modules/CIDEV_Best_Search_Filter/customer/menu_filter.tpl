{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

{if $active_modules.CIDEV_Best_Search_Filter ne ""}

{if $main eq "catalog" and $current_category.categoryid ne ""}
	{assign var="cidev_product_filter_action" value="home.php?cat=`$current_category.categoryid`"}
{elseif $main eq "search"}
	{assign var="cidev_product_filter_action" value="search.php?mode=search"}
{elseif $main eq "manufacturer_products"}
	{assign var="cidev_product_filter_action" value="manufacturers.php?manufacturerid=`$manufacturer.manufacturerid`"}
{/if}

{if $cidev_product_filter_action ne "" && (($cidev_filters_tree ne "") || ($config.CIDEV_Best_Search_Filter.cidev_disable_manufacturers ne "Y" && $cidev_manufacturers ne "") )}

<script type="text/javascript" src="{$SkinDir}/modules/CIDEV_Best_Search_Filter/js/cidev_ajax.js"></script>
<script type="text/javascript" src="{$SkinDir}/modules/CIDEV_Best_Search_Filter/js/cidev_filter.js"></script>

{capture name=menu}

<form name="cidev_product_filter_form" method="post" action="{$cidev_product_filter_action}&cidev_main_value={$main}{if $smarty.get.sort ne ""}&sort={$smarty.get.sort}{/if}{if $smarty.get.sort_direction ne ""}&sort_direction={$smarty.get.sort_direction}{/if}">

{if $main eq "manufacturer_products" && $manufacturer.manufacturerid ne ""}
<input type="hidden" id="cidev_manufacturerid" name="cidev_manufacturerid" value="{$manufacturer.manufacturerid}"> 
{/if}

<div id="cidev_filter_menu">
{include file="modules/CIDEV_Best_Search_Filter/customer/menu_filter_values.tpl"}
</div>

{if $config.CIDEV_Best_Search_Filter.cidev_reload_on_action eq "button"}
 <div align="center">
  <br />
  {include file="customer/buttons/button.tpl" button_title=$lng.lbl_cidev_narrow_search href="javascript: cidev_send_filter_values();" }
 </div>
{/if}

</form>
{/capture}
{include file="modules/CIDEV_Best_Search_Filter/customer/menu_dialog.tpl" title=$lng.lbl_cidev_narrow_search_results content=$smarty.capture.menu}
{/if}
{/if}
