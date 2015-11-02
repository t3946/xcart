{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

<h1>{$lng.lbl_cidev_filter_name}: {$f_name}</h1>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td>
<a class="NavigationPath" title="{$lng.lbl_cidev_back_to_filters_list}" href="cidev_admin_filters.php">{$lng.lbl_cidev_back_to_filters_list}</a>
<br />
<br />
</td>

{if $cidev_filter_values ne ""}
<td align="right">
{* {include file="buttons/button.tpl" button_title=$lng.lbl_cidev_multiple_filter_values_add href="cidev_admin_add_filter_to_products.php?f_id=`$f_id`"} *}

<a class="NavigationPath" title="{$lng.lbl_cidev_multiple_filter_values_add}" href="cidev_admin_add_filter_to_products.php">{$lng.lbl_cidev_multiple_filter_values_add}</a>
</td>
{/if}

</tr>
</table>

{if $cidev_filter_values ne ""}
        {include file="modules/CIDEV_Best_Search_Filter/admin/cidev_admin_filter_values_list.tpl"}
	<br />
{/if}

{include file="modules/CIDEV_Best_Search_Filter/admin/cidev_admin_filter_value_add.tpl"}
