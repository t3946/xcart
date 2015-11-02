{* $Id: home.tpl,v 1.123.2.2 2006/11/08 14:38:26 svowl Exp $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{ config_load file="$skin_config" }
<html>
<head>
<title>{if $login ne ""}{if $current_storefront_info.prefix eq "MAIN_SF_PREFIX"}AR-{else}{$current_storefront_info.prefix}{/if}Admin: {$cidev_firstname} ({$login}){else}{$lng.txt_site_title}{/if}</title>
{ include file="meta.tpl" }
<link rel="stylesheet" href="{$SkinDir}/skin1_admin.css" />

<link rel="stylesheet" href="{$SkinDir}/lib/jqueryui/jquery.ui.admin.css" />

</head>
<body{$reading_direction_tag}>
{ include file="rectangle_top.tpl" }
{ include file="head_admin.tpl" }
<!-- main area -->
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<tr>
<td class="VertMenuLeftColumn">
{if $login eq "" }
{*
{ include file="auth.tpl" }
*}
{else}
{ include file="admin/menu.tpl" }

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
<br />
{ include file="admin/menu_admin.tpl" }
{/if}

<br />
{if $active_modules.XAffiliate ne ''}
{ include file="admin/menu_affiliate.tpl" }
{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
{ include file="menu_profile.tpl" }
{/if}

{/if}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
<br />
{ include file="admin/help.tpl" }
{/if}

<br />
<img src="{$ImagesDir}/spacer.gif" width="150" height="1" alt="" />
</td>
<td valign="top">
<!-- central space -->
{include file="location.tpl"}

{include file="dialog_message.tpl"}

{if $smarty.get.mode eq "subscribed"}
{include file="main/subscribe_confirmation.tpl"}

{elseif $main eq "import_export"}
{include file="main/import_export.tpl"}

{elseif $main eq "ups_import"}
{include file="modules/Order_Tracking/ups_import.tpl"}

{elseif $main eq "froogle_export"}
{include file="modules/Froogle/froogle.tpl"}

{elseif $main eq "snapshots"}
{include file="admin/main/snapshots.tpl"}

{elseif $main eq "titles"}
{include file="admin/main/titles.tpl"}

{elseif $main eq "tracking_links"}
{include file="admin/main/track_links.tpl"}

{elseif $main eq "taxes"}
{include file="admin/main/taxes.tpl"}

{elseif $main eq "wishlists"}
{include file="modules/Wishlist/wishlists.tpl"}

{elseif $main eq "wishlist"}
{include file="modules/Wishlist/display_wishlist.tpl"}

{elseif $main eq "tax_edit"}
{include file="admin/main/tax_edit.tpl"}

{elseif $main eq "ups_registration"}
{include file="modules/UPS_OnLine_Tools/ups.tpl"}

{elseif $main eq "order_edit"}
{include file="modules/Advanced_Order_Management/order_edit.tpl"}

{elseif $main eq "manufacturers"}
{include file="modules/Manufacturers/manufacturers.tpl"}

{elseif $main eq "brands"}
{include file="modules/Brands/brands.tpl"}

{elseif $main eq "user_profile"}
{include file="$tpldir/main/register.tpl"}

{elseif $main eq "stop_list"}
{include file="modules/Stop_List/stop_list.tpl"}

{elseif $main eq "returns"}
{include file="modules/RMA/returns.tpl"}

{elseif $main eq "benchmark"}
{include file="modules/Benchmark/bench.tpl"}

{elseif $main eq "slg"}
{include file="modules/Shipping_Label_Generator/generator.tpl"}

{elseif $main eq "register"}
{include file="admin/main/register.tpl"}

{elseif $main eq "product_links"}
{include file="admin/main/product_links.tpl"}

{elseif $main eq "general_info"}
{include file="admin/main/general.tpl"}

{elseif $main eq "tools"}
{include file="admin/main/tools.tpl"}

{elseif $main eq "pages"}
{include file="admin/main/pages.tpl"}

{elseif $main eq "change_mpassword"}
{include file="admin/main/change_mpassword.tpl"}

{elseif $main eq "page_edit"}
{include file="admin/main/page_edit.tpl"}

{elseif $main eq "shipping_options"}
{include file="admin/main/shipping_options.tpl"}

{elseif $main eq "subscriptions"}
{include file="modules/Subscriptions/subscriptions_admin.tpl"}

{elseif $main eq "languages"}
{include file="admin/main/languages.tpl"}

{elseif $main eq "memberships"}
{include file="admin/main/memberships.tpl"}

{elseif $main eq "card_types"}
{include file="admin/main/card_types.tpl"}

{elseif $main eq "banner_info"}
{include file="main/banner_info.tpl"}

{elseif $main eq "referred_sales"}
{include file="main/referred_sales.tpl"}

{elseif $main eq "affiliates"}
{include file="main/affiliates.tpl"}

{elseif $main eq "partner_top_performers"}
{include file="admin/main/partner_top_performers.tpl"}

{elseif $main eq "partner_adv_stats"}
{include file="admin/main/partner_adv_stats.tpl"}

{elseif $main eq "partner_adv_campaigns"}
{include file="admin/main/partner_adv_campaigns.tpl"}

{elseif $main eq "partner_level_commissions"}
{include file="admin/main/partner_level_commissions.tpl"}

{elseif $main eq "partner_orders"}
{include file="admin/main/partner_orders.tpl"}

{elseif $main eq "partner_report"}
{include file="admin/main/partner_report.tpl"}

{elseif $main eq "partner_banners"}
{include file="admin/main/partner_banners.tpl"}

{elseif $main eq "partner_plans"}
{include file="admin/main/partner_plans.tpl"}

{elseif $main eq "partner_plans_edit"}
{include file="admin/main/partner_plans_edit.tpl"}

{elseif $main eq "commissions"}
{include file="admin/main/partner_commissions.tpl"}

{elseif $main eq "payment_upload"}
{include file="admin/main/payment_upload.tpl"}

{elseif $smarty.get.mode eq "unsubscribed"}
{include file="main/unsubscribe_confirmation.tpl"}

{elseif $main eq "search"}
{include file="main/search_result.tpl" products=$products}

{elseif $main eq "categories"}
{include file="admin/main/categories.tpl"}


{elseif $main eq "empty_categories"}
{include file="admin/main/empty_categories.tpl"}

{elseif $main eq "modules"}
{include file="admin/main/modules.tpl"}

{elseif $main eq "payment_methods"}
{include file="admin/main/payment_methods.tpl"}

{elseif $main eq "cc_processing"}
{include file="admin/main/cc_processing_main.tpl" processing_module=$processing_module }

{elseif $main eq "statistics"}
{include file="admin/main/statistics.tpl"}

{elseif $main eq "configuration"}
{include file="admin/main/configuration.tpl"}

{elseif $main eq "shipping"}
{include file="admin/main/shipping.tpl"}

{elseif $main eq "giftcerts"}
{include file="modules/Gift_Certificates/gc_admin.tpl"}

{elseif $main eq "db_backup"}
{include file="admin/main/db_backup.tpl"}

{elseif $main eq "states_edit"}
{include file="admin/main/states.tpl"}

{elseif $main eq "countries_edit"}
{include file="admin/main/countries.tpl"}

{elseif $main eq "counties_edit"}
{include file="admin/main/counties.tpl"}

{elseif $main eq "users"}
{include file="admin/main/users.tpl"}

{elseif $main eq "featured_products"}
{include file="admin/main/featured_products.tpl"}

{elseif $main eq "category_modify"}
{include file="admin/main/category_modify.tpl"}

{elseif $main eq "category_products"}
{include file="admin/main/category_products.tpl"}

{elseif $main eq "category_delete_confirmation"}
{include file="admin/main/category_del_confirmation.tpl"}

{elseif $main eq "user_delete_confirmation"}
{include file="admin/main/user_delete_confirmation.tpl"}

{elseif $main eq "user_add"}
{include file="$tpldir/main/register.tpl"}

{elseif $main eq "product"}
{include file="main/product.tpl" product=$product}

{elseif $main eq "top_info"}
{include file="admin/main/main.tpl"}

{elseif $main eq "promo"}
{include file="admin/main/promotions.tpl"}

{elseif $main eq "home"}
{include file="admin/main/welcome.tpl"}

{elseif $main eq "ratings_edit"}
{include file="admin/main/ratings_edit.tpl"}

{elseif $main eq "html_catalog"}
{include file="admin/main/html_catalog.tpl"}

{elseif $main eq "images_location"}
{include file="admin/main/images_location.tpl"}

{elseif $main eq "speed_bar"}
{include file="admin/main/speed_bar.tpl"}

{elseif $main eq "secure_login_form"}
{include file="admin/main/secure_login_form.tpl"}

{elseif $main eq "news_management"}
{include file="modules/News_Management/news_common.tpl"}

{elseif $main eq "mailchimp_news_management"}
{include file="modules/Mailchimp_Subscription/news_common.tpl"}

{elseif $main eq "change_password"}
{include file="main/change_password.tpl"}

{elseif $main eq "test_pgp"}
{include file="admin/main/test_pgp.tpl"}

{elseif $main eq "product_configurator"}
{include file="modules/Product_Configurator/pconf_common.tpl"}

{elseif $main eq "order_status_notifications"}
{include file="main/order_status_notifications.tpl"}

{elseif $main eq "logs"}
{include file="admin/main/logs.tpl"}

{else}

{* include file="common_templates.tpl" *}

{if $main eq "last_admin"}
{include file="main/error_last_admin.tpl"}

{elseif $main eq "info_pages"}
{include file="admin/main/info_pages.tpl"}

{elseif $main eq "info_category_modify"}
{include file="admin/main/info_category_modify.tpl"}

{elseif $main eq "info_category_products"}
{include file="admin/main/info_category_products.tpl"}

{elseif $main eq "product_disabled"}
{include file="main/error_product_disabled.tpl"}

{elseif $main eq "wrong_merchant_password"}
{include file="main/error_wrong_merchant_password.tpl"}

{elseif $main eq "product_in_cart_expired"}
{include file="main/error_product_in_cart_expired.tpl"}

{elseif $main eq "cant_open_file"}
{include file="main/error_cant_open_file.tpl"}

{elseif $main eq "profile_delete"}
{include file="main/profile_delete_confirmation.tpl"}

{elseif $main eq "profile_notdelete"}
{include file="main/profile_notdelete_message.tpl"}

{elseif $main eq "classes"}
{include file="modules/Feature_Comparison/classes.tpl"}

{elseif $main eq "help"}
{include file="help/index.tpl" section=$help_section}

{elseif $main eq "login_incorrect" or $main eq "antibot_error"}
{assign var="is_remember" value="Y"}
{include file="main/error_login_incorrect.tpl"}

{elseif $main eq "need_login"}
{assign var="is_remember" value="Y"}
{include file="main/error_login.tpl"}

{elseif $main eq "access_denied"}
{include file="main/error_access_denied.tpl"}

{elseif $main eq "cart_locked"}
{include file="main/error_cart_locked.tpl"}

{elseif $main eq "giftreg_is_private"}
{include file="main/error_giftreg_is_private.tpl"}

{elseif $main eq "page_not_found"}
{include file="main/error_page_not_found.tpl"}

{elseif $main eq "error_no_shipping"}
{include file="main/error_no_shipping.tpl"}

{elseif $main eq "permission_denied"}
{include file="main/error_permission_denied.tpl"}

{elseif $main eq "delivery_error"}
{include file="main/error_delivery.tpl"}

{elseif $main eq "subscribe_exist_email" or $main eq "subscribe_bad_email"}
{include file="main/error_subscribe.tpl"}

{elseif $main eq "error_ccprocessor_unavailable"}
{include file="main/error_ccprocessor_unavail.tpl"}

{elseif $main eq "error_cmpi_error"}
{include file="main/error_cmpi_error.tpl"}

{elseif $main eq "error_ccprocessor_error"}
{include file="main/error_ccprocessor_error.tpl"}

{elseif $main eq "error_ccprocessor_notfound"}
{include file="main/error_ccprocessor_notfound.tpl"}

{elseif $main eq "error_ccprocessor_baddata"}
{include file="main/error_ccprocessor_baddata.tpl"}

{elseif $main eq "error_giftcert_notfound"}
{include file="main/error_giftcert_notfound.tpl"}

{elseif $main eq "error_giftcert_notenough"}
{include file="main/error_giftcert_notenough.tpl"}

{elseif $main eq "import_3x_4x" && $import_pass ne ''}
{include file="modules/Import_3x_4x/import_results.tpl"}

{elseif $main eq "import_3x_4x"}
{include file="modules/Import_3x_4x/import.tpl"}

{elseif $main eq "import_error"}
{include file="main/error_import_error.tpl"}

{elseif $main eq "order_delete_confirmation"}
{include file="main/order_delete_confirmation.tpl"}

{elseif $main eq "product_delete_confirmation"}
{include file="main/product_delete_confirmation.tpl"}

{elseif $main eq "orders"}
{include file="main/orders.tpl"}

{elseif $main eq "order_reports"}
{include file="main/order_reports.tpl"}

{elseif $main eq "history_order"}
{include file="main/history_order.tpl"}

{elseif $main eq "product_modify"}
{include file="main/product_modify.tpl"}

{elseif $main eq "error_min_order"}
{include file="main/error_min_order.tpl"}

{elseif $main eq "error_max_order"}
{include file="main/error_max_order.tpl"}

{elseif $main eq "error_max_items"}
{include file="main/error_max_items.tpl"}

{elseif $main eq "error_already_voted"}
{include file="customer/main/error_already_voted.tpl"}

{elseif $main eq "error_review_exists"}
{include file="customer/main/error_review_exists.tpl"}

{elseif $main eq "edit_file"}
{include file="admin/main/edit_file.tpl"}

{elseif $main eq "edit_dir"}
{include file="admin/main/edit_dir.tpl"}

{elseif $main eq "patch"}
{include file="admin/main/patch.tpl"}

{elseif $main eq "editor_mode"}
{include file="admin/main/editor_mode.tpl"}

{elseif $main eq "insecure_login_form"}
{include file="main/insecure_login_form.tpl"}

{elseif $main eq "shipping_disabled"}
{include file="main/error_shipping_disabled.tpl"}

{elseif $main eq "realtime_shipping_disabled"}
{include file="main/error_realtime_shipping_disabled.tpl"}

{elseif $main eq "pages"}
{include file="customer/main/pages.tpl"}

{elseif $main eq "inv_update_ex"}
{include file="admin/main/inv_update_ex.tpl"}

{elseif $main eq "news_archive"}
{include file="modules/News_Management/news_archive.tpl"}

{elseif $main eq "news_lists"}
{include file="modules/News_Management/news_lists.tpl"}

{elseif $main eq "disabled_cookies"}
{include file="main/error_disabled_cookies.tpl"}

{elseif $main eq "demo_login_with_form"}
{include file="modules/Demo/login.tpl"}

{elseif $main eq "surveys"}
{include file="modules/Survey/surveys.tpl"}

{elseif $main eq "survey"}
{include file="modules/Survey/survey_modify.tpl"}

{elseif $main eq "storefronts"}
{include file="modules/Multiple_Storefronts/manage_storefronts.tpl"}

{elseif $main eq "bulk_manage"}
{include file="main/bulk_management.tpl"}

{elseif $main eq "bulk_review"}
{include file="main/bulk_review.tpl"}

{elseif $main eq "product_reports"}
{include file="main/product_reports.tpl"}

{elseif $main eq "seed_categories"}
{include file="admin/main/seed_categories.tpl"}

{elseif $main eq "grandfathered_products"}
{include file="main/grandfathered_products.tpl"}

{elseif $main eq "search_all_website"}
{include file="admin/main/search_all_website.tpl"}


{* start_modification_CIDEV -> CIDEV_Best_Search_Filter *}
{elseif $main eq "cidev_admin_filters"}
{include file="modules/CIDEV_Best_Search_Filter/admin/cidev_admin_filters.tpl"}

{elseif $main eq "cidev_admin_filter_values"}
{include file="modules/CIDEV_Best_Search_Filter/admin/cidev_admin_filter_values.tpl"}

{elseif $main eq "cidev_admin_add_filter_to_products"}
{include file="modules/CIDEV_Best_Search_Filter/admin/cidev_admin_add_filter_to_products.tpl"}

{elseif $main eq "cidev_admin_filter_category"}
{include file="modules/CIDEV_Best_Search_Filter/admin/cidev_admin_filter_category.tpl"}
{* end_modification_CIDEV -> CDEV_Best_Search_Filter *}

{*
{elseif $main eq "order_status"}
{include file="admin/main/order_status.tpl"}
*}

{elseif $main eq "geo_import"}
{include file="admin/main/geo_import.tpl"}

{elseif $main eq "compose_message"}
{include file="admin/main/compose_message.tpl"}

{elseif $main eq "fraud_page"}
{include file="admin/main/fraud_page.tpl"}

{elseif $main eq "reconciliation"}
{include file="admin/main/reconciliation.tpl"}


{else}
{include file="main/error_page_not_found.tpl"}

{/if}


{/if}

<!-- /central space -->
&nbsp;
</td>
<td>
<img src="{$ImagesDir}/spacer.gif" width="20" height="1" alt="" />
</td>
</tr>
</table>
{ include file="rectangle_bottom.tpl" }
</body>
</html>
