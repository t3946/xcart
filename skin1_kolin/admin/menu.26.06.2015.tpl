{* $Id: menu.tpl,v 1.68.2.3 2006/07/19 10:19:35 max Exp $ *}
{capture name=menu}

{if $allowed_elements.LeftLink_Users eq "Y"}
{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/users.php" class="VertMenuItems">{$lng.lbl_users}</a><br />
{/if}
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
<a href="{$catalogs.admin}/categories.php" class="VertMenuItems">{$lng.lbl_categories}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a><br />
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
<a href="{$catalogs.admin}/empty_categories.php" class="VertMenuItems">Empty categories{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a><br />
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
{if $current_membership_flag ne 'FS'}
    <a href="{$catalogs.admin}/seed_categories.php" class="VertMenuItems">{$lng.lbl_seed_categories}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a><br />
{/if}
{/if}


{if $active_modules.Manufacturers}
<a href="{$catalogs.admin}/manufacturers.php?word=num" class="VertMenuItems">{$lng.lbl_manufacturers}</a><br />
{/if}
{if $active_modules.Brands}
<a href="{$catalogs.admin}/brands.php?word=num" class="VertMenuItems">{$lng.lbl_brands}</a><br />
{/if}
{if $active_modules.Wishlist}
<a href="{$catalogs.admin}/wishlists.php" class="VertMenuItems">{$lng.lbl_wish_lists}</a><br />
{/if}
{if $active_modules.Simple_Mode eq ""}<a href="{$catalogs.admin}/search.php" class="VertMenuItems">{$lng.lbl_admin_menu_products}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a><br />{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
<a href="{$catalogs.admin}/grandfathered_products.php" class="VertMenuItems">{$lng.lbl_grandfathered_products}</a><br />
{/if}

{if $allowed_elements.LeftLink_Reports eq "Y"}
{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/reports.php" class="VertMenuItems">Reports</a><br />
{/if}
{/if}

{*
{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
<a href="{$catalogs.admin}/product_reports.php" class="VertMenuItems">{$lng.lbl_product_reports}</a><br />
{/if}
*}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/orders.php?page_name=dashboard" class="VertMenuItems">Order dashboard</a><br />
<a href="{$catalogs.admin}/orders.php?page_name=search" class="VertMenuItems">Order search</a><br />
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER")}
{*
<a href="{$catalogs.admin}/order_reports.php" class="VertMenuItems">{$lng.lbl_order_reports}</a><br />
*}

<a href="{$catalogs.admin}/shipping_quotes_log.php?mode=search" class="VertMenuItems">Shipping quotes log</a><br />
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER" || $membership_code eq "ADMIN_TRACKING_NUMBER_ENTRY_OPERATOR")}
{*
<a href="{$catalogs.admin}/order_statuses.php" class="VertMenuItems">Order statuses</a><br />
*}

<a href="{$catalogs.admin}/product_question_search.php" class="VertMenuItems">Product questions</a><br />

<a href="{$catalogs.admin}/reconciliation.php" class="VertMenuItems">Reconciliation</a><br />

<a href="{$catalogs.admin}/classification.php" class="VertMenuItems">Classification</a><br />

<a href="{$catalogs.admin}/ab_testing.php" class="VertMenuItems">A/B testing</a><br />
{*
<a href="{$catalogs.admin}/google_content_api_test.php" class="VertMenuItems">Google Content API: test</a><br />
*}
<a href="{$catalogs.admin}/backprocess_logs.php" class="VertMenuItems">Backprocess logs</a><br />

<a href="{$catalogs.admin}/order_status_notifications.php" class="VertMenuItems">{$lng.lbl_order_status_notifications}</a><br />
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
{if $active_modules.News_Management}
<a href="{$catalogs.admin}/news.php" class="VertMenuItems">{$lng.lbl_news_management}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a><br />
{/if}
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
{if $active_modules.Mailchimp_Subscription}
<a href="{$catalogs.admin}/mailchimp_news.php" class="VertMenuItems">{$lng.lbl_mailchimp_news_management}</a><br />
{/if}
{/if}

<a href="{$catalogs.admin}/statistics.php" class="VertMenuItems">{$lng.lbl_statistics}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a><br />

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/shipping.php" class="VertMenuItems">{$lng.lbl_shipping_methods}</a><br />
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/track_links.php" class="VertMenuItems">{$lng.lbl_tracking_links}</a><br />
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/taxes.php" class="VertMenuItems">{$lng.lbl_taxing_system}</a><br />
{/if}

{if $active_modules.Customer_Reviews ne ""}
<a href="{$catalogs.admin}/ratings_edit.php" class="VertMenuItems">{$lng.lbl_edit_ratings}</a><br />
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/countries.php" class="VertMenuItems">{$lng.lbl_countries}</a><br />
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/states.php" class="VertMenuItems">{$lng.lbl_states}</a><br />
{/if}

{if $active_modules.Stop_List ne ""}
{include file="modules/Stop_List/stop_list_menu.tpl"}<br />
{/if}
{if $active_modules.Benchmark ne ""}
{include file="modules/Benchmark/menu.tpl"}<br />
{/if}
{if $active_modules.Feature_Comparison ne ""}
{include file="modules/Feature_Comparison/admin_menu.tpl"}
{/if}
{if $active_modules.RMA ne ""}
{include file="modules/RMA/admin_menu.tpl"}<br />
{/if}
{if $active_modules.Gift_Certificates ne ""}
{include file="modules/Gift_Certificates/gc_admin_menu.tpl"}<br />
{/if}
{if $active_modules.Subscriptions ne ""}
{include file="modules/Subscriptions/subscriptions_menu.tpl"}<br />
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
{if $active_modules.UPS_OnLine_Tools ne ""}
<a href="{$catalogs.admin}/ups.php" class="VertMenuItems">{$lng.lbl_ups_online_tools}</a><br />
{/if}
{/if}
{if $active_modules.Survey ne ""}
{include file="modules/Survey/admin_menu.tpl"}
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
<a href="{$catalogs.admin}/inv_update_ex.php" class="VertMenuItems">{$lng.lbl_update_inventory}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a><br />
{/if}

{if $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER" || $login eq "michael2"}
<a href="{$catalogs.admin}/bpu.php" class="VertMenuItems">BPU{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a><br />
{/if}

{/capture}
{ include file="menu.tpl" dingbats="dingbats_categorie.gif" menu_title=$lng.lbl_management menu_content=$smarty.capture.menu }
