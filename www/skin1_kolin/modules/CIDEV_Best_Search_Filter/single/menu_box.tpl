{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

{if (!$xcartApp->user->hasRoles(['vrs','vrv']))}
<a class="VertMenuItems" href="{$catalogs.admin}/cidev_admin_filters.php">{$lng.lbl_cidev_best_search_filter} (SF)</a>
{/if}
