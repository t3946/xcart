{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

<h1>{$lng.lbl_cidev_best_search_filter}</h1>

{if $cidev_filters ne ""}
	{include file="modules/CIDEV_Best_Search_Filter/admin/cidev_admin_filters_list.tpl"}
<br />
{/if}

{include file="modules/CIDEV_Best_Search_Filter/admin/cidev_admin_filter_add.tpl"}
