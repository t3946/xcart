{* $Id: home.tpl,v 1.80.2.1 2006/11/08 14:38:27 svowl Exp $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{ config_load file="$skin_config" }
<html>
<head>
<title>{if $login ne ""}{if $current_storefront_info.prefix eq "MAIN_SF_PREFIX"}AR-{else}{$current_storefront_info.prefix}{/if}{$login}{else}{$lng.txt_site_title}{/if}</title>
{ include file="meta.tpl" }
<link rel="stylesheet" href="{$SkinDir}/skin1_admin.css" />
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
{ include file="provider/menu.tpl" }
{* <br /> *}
{* include file="provider/menu_orders.tpl" *}
<!--br /-->
{*
{ include file="menu_profile.tpl" }
*}
{/if}
<br />
{ include file="help.tpl" }
<img src="{$ImagesDir}/spacer.gif" width="150" height="1" alt="" />
</td>
<td valign="top">
<!-- central space -->
{include file="location.tpl"}

{include file="dialog_message.tpl"}

{if $smarty.get.mode eq "subscribed"}
{include file="main/subscribe_confirmation.tpl"}

{elseif $smarty.get.mode eq "unsubscribed"}
{include file="main/unsubscribe_confirmation.tpl"}

{elseif $main eq "ups_import"}
{include file="modules/Order_Tracking/ups_import.tpl"}

{elseif $main eq "import_export"}
{include file="main/import_export.tpl"}

{elseif $main eq "froogle_export"}
{include file="modules/Froogle/froogle.tpl"}

{elseif $main eq "register"}
{include file="provider/main/register.tpl"}

{elseif $main eq "search"}
{include file="main/search_result.tpl"}

{elseif $main eq "brands"}
{include file="modules/Brands/brands.tpl"}

{elseif $main eq "manufacturers"}
{include file="modules/Manufacturers/manufacturers.tpl"}

{elseif $main eq "discounts"}
{include file="provider/main/discounts.tpl"}

{elseif $main eq "categories"}
{include file="provider/main/categories.tpl"}

{elseif $main eq "category_modify"}
{include file="provider/main/category_modify.tpl"}

{elseif $main eq "category_products"}
{include file="provider/main/category_products.tpl"}

{elseif $main eq "category_delete_confirmation"}
{include file="provider/main/category_del_confirmation.tpl"}

{elseif $main eq "coupons"}
{include file="modules/Discount_Coupons/coupons.tpl"}

{elseif $main eq "extra_fields"}
{if $active_modules.Extra_Fields ne ""}
{include file="modules/Extra_Fields/extra_fields.tpl"}
{/if}

{elseif $main eq "wishlists"}
{include file="modules/Wishlist/wishlists.tpl"}

{elseif $main eq "wishlist"}
{include file="modules/Wishlist/display_wishlist.tpl"}

{elseif $main eq "taxes"}
{include file="provider/main/taxes.tpl"}

{elseif $main eq "tax_edit"}
{include file="provider/main/tax_edit.tpl"}

{elseif $main eq "shipping_rates"}
{include file="provider/main/shipping_rates.tpl"}

{elseif $main eq "zones"}
{include file="provider/main/zones.tpl"}

{elseif $main eq "zone_edit"}
{include file="provider/main/zone_edit.tpl"}

{elseif $main eq "product"}
{include file="main/product.tpl"}

{elseif $main eq "product_links"}
{include file="admin/main/product_links.tpl"}

{elseif $main eq "home" and $login eq ""}
{include file="provider/main/welcome.tpl"}

{elseif $main eq "home"}
{include file="provider/main/promotions.tpl"}

{elseif $main eq "inv_update"}
{include file="provider/main/inv_update.tpl"}

{elseif $main eq "inv_updated"}
{include file="main/inv_updated.tpl"}

{elseif $main eq "error_inv_update"}
{include file="main/error_inv_update.tpl"}

{elseif $main eq "product_configurator"}
{include file="modules/Product_Configurator/pconf_common.tpl"}

{elseif $main eq "general_info"}
{include file="provider/main/general.tpl"}

{elseif $main eq "secure_login_form"}
{include file="provider/main/secure_login_form.tpl"}

{elseif $main eq "change_password"}
{include file="main/change_password.tpl"}

{elseif $main eq "special_offers"}
{include file="modules/Special_Offers/common.tpl"}

{elseif $main eq "bulk_manage"}
{include file="main/bulk_management.tpl"}

{elseif $main eq "bulk_review"}
{include file="main/bulk_review.tpl"}

{elseif $main eq "grandfathered_products"}
{include file="main/grandfathered_products.tpl"}

{else}
{include file="common_templates.tpl"}
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
