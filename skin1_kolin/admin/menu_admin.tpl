{* $Id: menu_admin.tpl,v 1.34 2005/11/17 06:55:36 max Exp $ *}
{capture name=menu}

{* start_modification_CIDEV -> CIDEV_Best_Search_Filter *}
{*{if $active_modules.CIDEV_Best_Search_Filter ne ""}*}
{include file="modules/CIDEV_Best_Search_Filter/single/menu_box.tpl"}
{*{/if}*}
{* end_modification_CIDEV -> CIDEV_Best_Search_Filter *}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/general.php" class="VertMenuItems">{$lng.lbl_summary}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/db_backup.php" class="VertMenuItems">{$lng.lbl_db_backup_restore}</a>
{/if}

<a href="{$catalogs.admin}/import.php" class="VertMenuItems">{$lng.lbl_import_export}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/geo_import.php" class="VertMenuItems">GEO import</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/memberships.php" class="VertMenuItems">{$lng.lbl_membership_levels}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/card_types.php" class="VertMenuItems">{$lng.lbl_credit_card_types}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/titles.php" class="VertMenuItems">{$lng.lbl_titles}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/file_edit.php" class="VertMenuItems">{$lng.lbl_edit_templates}</a>
{/if}

<a href="{$catalogs.admin}/file_manage.php" class="VertMenuItems">{$lng.lbl_files}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/configuration.php" class="VertMenuItems">{$lng.lbl_general_settings}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/images_location.php" class="VertMenuItems">{$lng.lbl_images_location}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/languages.php" class="VertMenuItems">{$lng.lbl_languages}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/editor_mode.php" class="VertMenuItems">{$lng.lbl_webmaster_mode}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/modules.php" class="VertMenuItems">{$lng.lbl_modules}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/payment_methods.php" class="VertMenuItems">{$lng.lbl_payment_methods}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/patch.php" class="VertMenuItems">{$lng.lbl_patch_upgrade}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/html_catalog.php" class="VertMenuItems">{$lng.lbl_html_catalog}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/pages.php" class="VertMenuItems">{$lng.lbl_static_pages}</a>
{/if}

<a href="{$catalogs.admin}/categories.php?mode=info" class="VertMenuItems">Articles{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/speed_bar.php" class="VertMenuItems">{$lng.lbl_speed_bar}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
{if $active_modules.Multiple_Storefronts}
	<a href="{$catalogs.admin}/multiple_storefronts.php" class="VertMenuItems">{$lng.lbl_multiple_storefronts}</a>
{/if}
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/search_all_website.php" class="VertMenuItems">{$lng.lbl_search_all_website}</a>
{/if}

{/capture}
{ include file="menu_admin.tpl" dingbats="dingbats_categorie.gif" menu_title=$lng.lbl_administration menu_content=$smarty.capture.menu }
