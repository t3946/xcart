{* $Id: home.tpl,v 1.88.2.4 2006/10/13 10:41:21 svowl Exp $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{if $current_storefront_info.storefrontid ne ""}
<link rel="shortcut icon" href="{$xcart_web_dir}/image.php?id={$current_storefront_info.storefrontid}&amp;type=F" type="image/vnd.microsoft.icon" />
{else}
{* <link rel="shortcut icon" href="{$ImagesDir}/favicon.ico" type="image/vnd.microsoft.icon" /> *}
<link rel="shortcut icon" href="{$xcart_web_dir}/image.php?id=0&amp;type=F" type="image/vnd.microsoft.icon" />
{/if}
{if $printable ne ''}
{include file="customer/home_printable.tpl"}
{else}
{config_load file="$skin_config"}
<html>
<head>
<title>{strip}
{capture name=title}
{if $config.SEO.page_title_format eq "A"}
{section name=position loop=$location}
{if not %position.first%}&nbsp;::&nbsp;{/if}
{$location[position].0|strip_tags|escape}
{/section}
{else}
{section name=position loop=$location step=-1}
{if not %position.first%}&nbsp;::&nbsp;{/if}
{$location[position].0|strip_tags|escape}
{/section}
{/if}
{/capture}
{if $config.SEO.page_title_limit <= 0}
{$smarty.capture.title|replace:"&amp;":"&"}
{else}
{$smarty.capture.title|replace:"&nbsp;":" "|truncate:$config.SEO.page_title_limit|replace:" ":"&nbsp;"}
{/if}
{/strip}</title>
{include file="meta.tpl" }
<script src="{$SkinDir}/jquery-1.4.3.min.js" type="text/javascript"></script>
<script src="{$SkinDir}/jquery.tooltip.js" type="text/javascript"></script>

{include file="jquery_ui.tpl"}

<link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />
<link rel="stylesheet" href="{$SkinDir}/jquery.tooltip.css" />

<link rel="stylesheet" href="{$SkinDir}/US_City_List/jquery.autocomplete.css" />

<link rel="stylesheet" href="{$SkinDir}/lib/colorbox/colorbox.css" />
<script src="{$SkinDir}/lib/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>


<!--[if IE]>
	<link rel="stylesheet" href="{$SkinDir}/skin1.IE.css" type="text/css" media="all" />
<![endif]-->

{if $canonical_url}
  <link rel="canonical" href="http://{if $cidev_store_domain ne ""}{$cidev_store_domain|lower}{else}www.artistsupplysource.com{/if}/{$canonical_url}" />
{/if}
{if $main eq "catalog" && $current_category.category eq ""}
  <link rel="canonical" href="http://{if $cidev_store_domain ne ""}{$cidev_store_domain}{else}www.artistsupplysource.com{/if}/"/>
{/if}


<script type="text/javascript">
//<![CDATA[

{if $config.SEO.clean_urls_enabled eq "Y"}
{literal}
//  Fix a.href if base url is defined for page
function anchor_fix() {
var links = document.getElementsByTagName('A');
var m;
var _rg = new RegExp("(^|" + self.location.host + xcart_web_dir + "/)#([\\w\\d_]+)$")
for (var i = 0; i < links.length; i++) {
  if (links[i].href && (m = links[i].href.match(_rg))) {
    links[i].href = 'javascript:void(self.location.hash = "' + m[2] + '");';
  }
}
}

if (window.addEventListener)
window.addEventListener("load", anchor_fix, false);

else if (window.attachEvent)
window.attachEvent("onload", anchor_fix);
{/literal}
{/if}

//]]>
</script>

{if $config.SEO.clean_urls_enabled eq "Y"}
  <base href="{$catalogs.customer}/" />
{/if}


</head>
<body{$reading_direction_tag}{if $body_onload ne ''} onload="javascript: {$body_onload}"{/if}>


{* ------------------- *}
{include file="cidev_tracking_code.tpl" }
{* ------------------- *}


{literal}
<style>
#tooltip {
    max-width: {/literal}{$config.Product_Page.max_width_map_text}{literal}px;
}
</style>
{/literal}
{include file="rectangle_top.tpl" }
{include file="head.tpl" }
{if $active_modules.SnS_connector}
{include file="modules/SnS_connector/header.tpl"}
{/if}
<!-- main area -->
{*
{include file="customer/search.tpl"}
*}

{if !($main eq "catalog" && $current_category.category eq "" && $current_seed_category eq '') && $main ne "order_message"}
<div style="margin-left: 10px;">{include file="location.tpl"}</div>
{/if}
{* <br> *}


<table width="100%" cellpadding="0" cellspacing="0">
<tr>

{if $active_modules.CIDEV_Best_Search_Filter eq "" || $main eq "catalog" || $main eq "brand_products" }

<td class="VertMenuLeftColumn">
<br>
{if $categories ne "" and ($active_modules.Fancy_Categories ne "" or $config.General.root_categories eq "Y" or $subcategories ne "")}
{include file="customer/categories.tpl" }
{* <br /> *}
{/if}
{if $active_modules.Bestsellers ne "" and $config.Bestsellers.bestsellers_menu eq "Y"}
{include file="modules/Bestsellers/menu_bestsellers.tpl" }
{/if}
{*if $active_modules.Brands ne "" and $config.Brands.brands_menu eq "Y"}
{include file="modules/Brands/menu_brands.tpl" }
{elseif $active_modules.Manufacturers ne "" and $config.Manufacturers.manufacturers_menu eq "Y"}
{include file="modules/Manufacturers/menu_manufacturers.tpl" }
{/if*}
{*include file="customer/special.tpl"*}
{if $active_modules.Survey && $menu_surveys}
{foreach from=$menu_surveys item=menu_survey}
{include file="modules/Survey/menu_survey.tpl"}
<br />
{/foreach}
{/if}
{*include file="help.tpl" *}
{*include file="customer/search.tpl"*}
<img src="{$ImagesDir}/spacer.gif" width="156" height="1" alt="" />
</td>

{/if}

<td valign="top">
<!-- central space -->
{*
{if !($main eq "catalog" && $current_category.category eq "" && $current_seed_category eq '')}
    {include file="location.tpl"}
{/if}
<br>
*}

{if $gcheckout_enabled and $main ne "cart" and $main ne "checkout" and $main ne "anonymous_checkout" and $main ne "order_message"}
<div align="right">{include file="modules/Google_Checkout/gcheckout_button.tpl"}</div>
{/if}

{include file="dialog_message.tpl"}

{if $active_modules.Special_Offers ne ""}
{include file="modules/Special_Offers/customer/new_offers_message.tpl"}
{/if}

{if $main eq "product" || $main eq "catalog" || $main eq "brands_list" || $main eq "brand_products"}
<link itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#PaymentMethodCreditCard" />
<link itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#VISA" />
<link itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#MasterCard" />
<link itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#PayPal" />
{/if}

{if $use_schema_org eq "Y" && $main eq "product"}
{* <div itemscope itemtype="http://schema.org/Thing"> *}
<div itemprop="name" itemscope itemtype="http://schema.org/Product">
{/if}
<table cellpadding="0" cellspacing="0" width="100%">
<tr><td colspan=3 height="10">&nbsp;</td></tr>
<tr>
<td bgcolor="#ffffff" colspan=3 {* style="padding-left: 10px; padding-right: 10px;" *} >
<div id="google_search_result_block">
{$config.Search_products.search_products_result_code}
</div>
<div id="main">
{include file="customer/home_main.tpl"}
</div>
</td>
</tr>
</table>
{if $use_schema_org eq "Y" && $main eq "product"}
</div>
{/if}

{*include file="customer/home_main.tpl"*}


<!-- /central space -->
</td>
{*
<td class="VertMenuRightColumn">
<br>
{if $active_modules.SnS_connector && $config.SnS_connector.sns_display_button eq 'Y' && $sns_collector_path_url ne ''}
{include file="modules/SnS_connector/button.tpl"}
<br />
{/if}
{if $active_modules.Feature_Comparison ne "" && $comparison_products ne ''}
{include file="modules/Feature_Comparison/product_list.tpl" }
<br />
{/if}
{include file="customer/menu_cart.tpl" }
{if $login eq "" }
{include file="auth.tpl" }
{else}
{include file="authbox.tpl" }
{/if}
{if $active_modules.XAffiliate ne ""}
<br />
{include file="partner/menu_affiliate.tpl" }
{/if}
{if $active_modules.Interneka ne ""}
<br />
{include file="modules/Interneka/menu_interneka.tpl" }
{/if}
<!--br /-->
{include file="poweredby.tpl" }
<br />
{include file="help.tpl"}
<br />
{include file="customer/special.tpl"}
<br />
<br />
<div style="padding-left: 8px"><a href="{$xcart_web_dir}/home.php?cat=248"><img src="{$ImagesDir}/Art-Brushes-Ad.jpg" alt="" /></a></div>
<br />
{if $active_modules.Mailchimp_Subscription}
{include file="modules/Mailchimp_Subscription/news.tpl" }
{else}
{include file="news.tpl" }
{/if}
<br>
<img src="{$ImagesDir}/spacer.gif" width="150" height="1" alt="" />
</td>
*}
{*
<td>
<img src="{$ImagesDir}/spacer.gif" width="9" height="1" alt="" />
</td>
*}
</tr>
</table>
{if $active_modules.Brands ne "" and $config.Brands.brands_menu eq "Y" and $main ne "sitemap_customer"  }
<div class="bottom-block" style="margin: 9px 10px 0px 10px; padding: 8px;">{include file="modules/Brands/menu_brands_footer.tpl"}</div>
{/if}
{if ($active_modules.Multiple_Storefronts ne "" and $sf_links ne '' and $main ne "sitemap_customer") && ($main eq "catalog" && $current_category.category eq "")}
<div class="bottom-block inter-sf" style="margin: 10px 10px 0px 10px; padding: 8px;">{include file="modules/Multiple_Storefronts/menu_storefronts_footer.tpl"}</div>
{/if}
{include file="rectangle_bottom.tpl" }
{include file="ga_code.tpl" }
{if $config.Product_Page.map_bridge_text_background ne ''}
{literal}
<style>
#tooltip, .tooltip_helper {
	background-color: #{/literal}{$config.Product_Page.map_bridge_text_background};{literal}
}
</style>
{/literal}
{/if}
<script type="text/javascript">
//<![CDATA[
var txt_tooltip_helper = '{$map_bridge_mouseover_text|escape:javascript}';
{literal}
$(document).ready(function() {
	$('.map_price_help').tooltip({
		delay: 0,
		showURL: false,
		track: false,
		bodyHandler: function() {
			return txt_tooltip_helper;
	}});
});
{/literal}
//]]>
</script>

{if $config.Company.cidev_google_adwords ne "" }

{assign var="ecomm_prodid_replacement" value="ecomm_prodid: ''"}
{assign var="ecomm_pagetype_replacement" value="ecomm_pagetype: 'siteview'"}
{assign var="ecomm_totalvalue_replacement" value="ecomm_totalvalue: ''"}

{if $main eq "catalog" && $current_category.category eq ""}
	{assign var="ecomm_pagetype_replacement" value="ecomm_pagetype: 'home'"}
{elseif $main eq "catalog" && $current_category.category ne ""}
	{assign var="ecomm_pagetype_replacement" value="ecomm_pagetype: 'category'"}
{elseif $main eq "product"}
	{assign var="ecomm_prodid_replacement" value="ecomm_prodid: '`$product.productid`'"}
	{assign var="ecomm_pagetype_replacement" value="ecomm_pagetype: 'product'"}

	{if $product.map_price gt $product.taxed_price}
		{assign var="current_price" value=$product.map_price}
	{else}
		{assign var="current_price" value=$product.taxed_price}
	{/if}
	{assign var="ecomm_totalvalue_replacement" value="ecomm_totalvalue: '`$current_price`'"}
{elseif $main eq "order_message"}
        {assign var="ecomm_prodid_replacement" value="ecomm_prodid: `$productids_in_cart_imploded`"}
        {assign var="ecomm_pagetype_replacement" value="ecomm_pagetype: 'purchase'"}
        {assign var="ecomm_totalvalue_replacement" value="ecomm_totalvalue: '`$order_data_subtotal`'"}
{/if}

	{$config.Company.cidev_google_adwords|replace:"ecomm_prodid: ''":"`$ecomm_prodid_replacement`"|replace:"ecomm_pagetype: 'siteview'":"`$ecomm_pagetype_replacement`"|replace:"ecomm_totalvalue: ''":"`$ecomm_totalvalue_replacement`"}
{/if}


</body>
</html>
{/if}
