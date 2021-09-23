{* $Id: promotions.tpl,v 1.30 2006/04/11 07:29:36 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_welcome_to_the_providers_zone}

{capture name=dialog}
<h3>{$lng.txt_personal_provider_area}</h3>
<p align="justify">
{$lng.txt_provider_promotion_note}
</p>
<p />
{* <img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a href="{$catalogs.provider}/product_modify.php">{$lng.lbl_add_new_product}</a></b><br /> *}

<img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a onclick='javascript: window.open("cidev_popup_add_product.php","selectimage","width=500,height=200,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no");' href="javascript: void(0);" >{$lng.lbl_add_new_product}</a></b><br />

{$lng.txt_provider_promotion_add_new_product_note}
<p />
<img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a href="{$catalogs.provider}/search.php">{$lng.lbl_product_modify}</a></b><br />
<img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a href="{$catalogs.provider}/search.php">{$lng.lbl_delete_product}</a></b><br />
{$lng.txt_provider_promotion_modify_product_note}
<p />
<img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a href="{$catalogs.provider}/import.php">{$lng.lbl_import_products}</a></b><br />
<img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a href="{$catalogs.provider}/import.php?mode=export">{$lng.lbl_export_products}</a></b><br />
{$lng.txt_provider_promotion_ie_product_note}
{if $active_modules.Extra_Fields ne ""}
<p />
<img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a href="{$catalogs.provider}/extra_fields.php">{$lng.lbl_extra_fields}</a></b><br />
{$lng.lbl_provider_promotion_ef_note}
{/if}
{*
<p />
<img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a href="{$catalogs.provider}/shipping_rates.php">{$lng.lbl_shipping_charges}</a></b><br />
{$lng.txt_provider_promotion_sc_note}
<p />
<img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a href="{$catalogs.provider}/zones.php">{$lng.lbl_destination_zones}</a></b><br />
{$lng.txt_provider_promotion_dz_note}
<p />
<img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a href="{$catalogs.provider}/discounts.php">{$lng.lbl_discounts}</a></b><br />
{$lng.txt_provider_promotion_discounts_note}
<p />
<img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a href="{$catalogs.provider}/coupons.php">{$lng.lbl_coupons}</a></b><br />
{$lng.txt_provider_promotion_coupons_note}
<p />
<img src="{$ImagesDir}/rarrow.gif" width="9" height="9" alt="" /> <b><a href="{$catalogs.provider}/taxes.php">{$lng.lbl_tax_rates}</a></b><br />
{$lng.txt_provider_promotion_taxes_note}
*}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_provider_menu content=$smarty.capture.dialog extra='width="100%"'}
