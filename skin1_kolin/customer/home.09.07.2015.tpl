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
{if $brand.title ne "" && $main eq "brand_products"}
{$brand.title}
{else}
{if $main eq "product" && $product.title_tag ne ""}
{$product.title_tag}
{else}
{if $clean_url_data.resource_type eq "K" && $e_search_data.substring ne ""}
{$e_search_data.substring|stripslashes|escape} at&nbsp;
{/if}
{if $config.Appearance.config_title_meta_tag ne "" && (($main eq "catalog" && $current_category.category eq ""))}
{$config.Appearance.config_title_meta_tag}
{elseif $current_category.title_tag ne "" && $main eq "catalog"}
{$current_category.title_tag} {*| {$location[0].0*}
{else}
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
{/if}
{/if}
{/if}
{/strip}</title>
{include file="meta.tpl" }
{*
<script src="{$SkinDir}/jquery-1.4.3.min.js" type="text/javascript"></script>
*}

{if ($main eq "product")}
{* igor_async *}
<script src="{$SkinDir}/jquery.tooltip.js" type="text/javascript"></script>
{/if}

{if ($main eq "product")}
{* igor_async *}
<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
{/if}

{* {include file="jquery_ui.tpl"} *}

<link rel="stylesheet" href="{$SkinDir}/lib/jqueryui/jquery.ui.theme.css" />

<link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />
<link rel="stylesheet" href="{$SkinDir}/jquery.tooltip.css" />

<link rel="stylesheet" href="{$SkinDir}/US_City_List/jquery.autocomplete.css" />

<link rel="stylesheet" href="{$SkinDir}/lib/colorbox/colorbox.css" />

{*
  <link rel="stylesheet" href="http://mehamalina.ru/css/style.css?v=1421903756">
 *}

<!-- igor_async <script src="{$SkinDir}/lib/colorbox/jquery.colorbox-min.js" type="text/javascript"></script> -->


<!--[if IE]>
	<link rel="stylesheet" href="{$SkinDir}/skin1.IE.css" type="text/css" media="all" />
<![endif]-->

{if $canonical_url}
  <link rel="canonical" href="http://{if $cidev_store_domain ne ""}{$cidev_store_domain|lower}{else}www.artistsupplysource.com{/if}/{$canonical_url}" />
{/if}
{if $main eq "catalog" && $current_category.category eq "" && $clean_url_data.resource_type ne "K"}
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

{if $main eq "product" || $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}
<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>
{/if}

{if $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}

<script type="text/javascript">
//<![CDATA[
{literal}
        function func_load_more_products(ajax_navigation_page_next){

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

				var current_storefront = '{/literal}{$current_storefront}{literal}';
				var e_products_found = '{/literal}{if $e_products_found eq "Y"}Y{/if}{literal}';
				var cidev_filter_mode = 'load_more_products';
				var additional_params = '';
	
				if (e_products_found == "Y"){
					cidev_filter_mode = 'load_more_e_products';

					if (current_storefront == "41"){
						additional_params = '&products_template=products_new_style'
					}
				}
				
				var cat = {/literal}{if $cat ne ""}{$cat}{else}{literal}''{/literal}{/if}{literal};

                                var cidev_parameters = 'cidev_filter_mode='+cidev_filter_mode+'&ajax_navigation_page_next='+ajax_navigation_page_next+'&cat='+cat+additional_params;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("show_next_products_block_"+ajax_navigation_page_next).innerHTML=cidev_xmlHttp.responseText;

                                                }else{
                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','infinite_products.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('func_load_more_products()', 1000);
                        }
        }
{/literal}
//]]>
</script>
{/if}


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

{if $variant_id_for_point5 ne "" && $variant_id_for_point5 eq "0" && !($main eq "catalog" && $current_category.category eq "")}
<br />
{assign var="social_buttons_data_services" value=$config.Appearance.social_buttons_data_services}
{$config.Appearance.social_buttons_script_code|replace:"[data-services]":"$social_buttons_data_services"|replace:"[size]":"medium"}
{/if}

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

{* ------------- *}
{if $main eq "catalog" && $current_category.category eq "" && $keyphrase eq ''}
<div style="margin: 9px 10px 0px 10px; padding: 8px;" class="bottom-block">
 <div class="ship_cities_link"><a href="#" style="margin-left: 13px;">{$lng.lbl_seo_cities_anchor}</a></div>
 <div id="ship_cities_text">
 {$lng.lbl_seo_cities}
 </div>
</div>
{/if}
{* ------------- *}

{if ($active_modules.Multiple_Storefronts ne "" and $sf_links ne '' and $main ne "sitemap_customer") && ($main eq "catalog" && $current_category.category eq "") && $area_selector != "keyword"}
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

{if $main eq "product"}
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
{/if}

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


{if $GTS_badge_code ne ""}
	{$GTS_badge_code}
{/if}
{if $GTS_order_confirmation_module_code ne ""}
	{$GTS_order_confirmation_module_code}
{/if}


{* async javascripts to eliminate blocking of render page *}
{literal}
<script type="text/javascript">

function downloadJSAtOnload() 
{
{/literal}
{if !($main eq "fast_lane_checkout")}
{literal}
var element = document.createElement("script");
element.src = "{/literal}{$SkinDir}{literal}/check_email_script.js";
document.body.appendChild(element);
{/literal}
{/if}
{literal}

var element2 = document.createElement("script");
element2.src = "{/literal}{$SkinDir}{literal}/ajax_add_to_cart.js";
document.body.appendChild(element2);

var element3 = document.createElement("script");
element3.src = "{/literal}{$SkinDir}{literal}/customer/popup_open.js";
document.body.appendChild(element3);

var element4 = document.createElement("script");
element4.src = "{/literal}{$SkinDir}{literal}/lib/colorbox/jquery.colorbox-min.js";
document.body.appendChild(element4);

{/literal}
{if !($main eq "product" || $main eq "fast_lane_checkout")}
{literal}
var element5 = document.createElement("script");
element5.src = "{/literal}{$SkinDir}{literal}/common.js";
document.body.appendChild(element5);
{/literal}
{/if}
{literal}

var element6 = document.createElement("script");
element6.src = "{/literal}{$SkinDir}{literal}/browser_identificator.js";
document.body.appendChild(element6);

/*
{/literal}
{if !($main eq "product")}
{literal}
var element7 = document.createElement("script");
element7.src = "{/literal}{$SkinDir}{literal}/jquery.min.1.7.1.js";
document.body.appendChild(element7);
{/literal}
{/if}
{literal}
*/

{/literal}
{if !($main eq "product")}
{literal}
var element8 = document.createElement("script");
element8.src = "{/literal}{$SkinDir}{literal}/jquery.tooltip.js";
document.body.appendChild(element8);
{/literal}
{/if}
{literal}

{/literal}
{if !($main eq "product")}
{literal}
var element9 = document.createElement("script");
element9.src = "{/literal}{$SkinDir}{literal}/lib/jqueryui/jquery-ui.custom.min.js";
document.body.appendChild(element9);
{/literal}
{/if}
{literal}

{/literal}
{if $main eq "product"}
{literal}
var element10 = document.createElement("script");
element10.src = "{/literal}{$SkinDir}{literal}/main/popup_image.js";
document.body.appendChild(element10);
{/literal}
{/if}
{literal}

{/literal}
/*
{if $main eq "product" || $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}
{literal}
var element11 = document.createElement("script");
element11.src = "{/literal}{$SkinDir}{literal}/check_zipcode.js";
document.body.appendChild(element11);

var element12 = document.createElement("script");
element12.src = "{/literal}{$SkinDir}{literal}/cidev_ajax.js";
document.body.appendChild(element12);

{/literal}
{/if}
{literal}
*/

var element13 = document.createElement("script");
element13.src = "//www.googleadservices.com/pagead/conversion.js";
document.body.appendChild(element13);

}
if (window.addEventListener)
    window.addEventListener("load", downloadJSAtOnload, false);
else if (window.attachEvent)
    window.attachEvent("onload", downloadJSAtOnload);
else 
    window.onload = downloadJSAtOnload;
</script>
{/literal}
{********************************************}


<script src="{$SkinDir}/ajax_home_page.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
{literal}

    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = 's3stores';
    
    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement('script'); s.async = true;
        s.type = 'text/javascript';
        s.src = '//' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
{/literal}
//]]>
</script>

</body>
</html>
{/if}
