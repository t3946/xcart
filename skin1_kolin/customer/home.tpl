{* $Id: home.tpl,v 1.88.2.4 2006/10/13 10:41:21 svowl Exp $ *}
<!DOCTYPE html>
<html lang="en-US">
<head>
{if $config.SEO.clean_urls_enabled eq "Y"}
<base href="{$catalogs.customer}/" />
{/if}
<title>{strip}
{if $brand.title ne "" && $main eq "brand_products"}
{$brand.title}
{else}
{if $main eq "product" && $product.title_tag ne ""}
{$product.title_tag}
{else}
{if $clean_url_data.resource_type eq "K" && $e_search_data.substring ne ""}
{if $e_search_data.orig_substring ne ""}{$e_search_data.orig_substring|stripslashes|escape}{else}{$e_search_data.substring|stripslashes|escape}{/if} at&nbsp;
{/if}
{if $config.Company.config_title_meta_tag ne "" && (($main eq "catalog" && $current_category.category eq ""))}
{$config.Company.config_title_meta_tag}
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
    {if $current_storefront_info.storefrontid ne ""}
<link rel="shortcut icon" href="{$xcart_web_dir}/image.php?id={$current_storefront_info.storefrontid}&amp;type=F" type="image/vnd.microsoft.icon"/>
    {else}
<link rel="shortcut icon" href="{$xcart_web_dir}/image.php?id=0&amp;type=F" type="image/vnd.microsoft.icon"/>
    {/if}
    {if $printable ne ''}
    {include file="customer/home_printable.tpl"}
    {else}
    {config_load file="$skin_config"}

    <link rel="stylesheet" href="{$SkinDir}/lib/jqueryui/jquery.ui.theme.css" />
    <link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />
    <link rel="stylesheet" href="{$SkinDir}/jquery.tooltip.css" />
    <link rel="stylesheet" href="{$SkinDir}/US_City_List/jquery.autocomplete.css" />
    <link rel="stylesheet" href="{$SkinDir}/lib/colorbox/colorbox.css" />
    <!--[if IE]>
    <link rel="stylesheet" href="{$SkinDir}/skin1.IE.css" type="text/css" media="all" />
    <![endif]-->
    {if $canonical_url}
        <link rel="canonical" href="http://{$site_domain|lower}/{$canonical_url}" />
    {/if}
    {if $main eq "catalog" && $current_category.category eq "" && $clean_url_data.resource_type ne "K"}
        <link rel="canonical" href="http://{$site_domain|lower}/"/>
    {/if}
    {if $config.Product_Page.map_bridge_text_background ne ''}
    {literal}
        <style>
            #tooltip, .tooltip_helper {
            background-color: #{/literal}{$config.Product_Page.map_bridge_text_background};{literal}
            }
        </style>
    {/literal}
    {/if}
    {literal}
    <style>
        #tooltip {
            max-width: {/literal}{$config.Product_Page.max_width_map_text}{literal}px;
        }
    </style>
    {/literal}

    {include file="meta.tpl" }

{if ($main eq "product")}
{* igor_async *}
<script src="{$SkinDir}/jquery.tooltip.js" type="text/javascript"></script>

{/if}
<script src="{$SkinDir}/js/sly.min.js" type="text/javascript"></script>
{if ($main eq "product")}
{* igor_async *}
<script type="text/javascript" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
{/if}
<script type="text/javascript" src="{$SkinDir}/js/jquery.visible.min.js"></script>
<script src="{$SkinDir}/js/google_analytics_impressions.js" type="text/javascript"></script>
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
<script src="{$SkinDir}/js/infinite_scroll.js" type="text/javascript"></script>
{include file="main/include_js.tpl" src="ajax_add_to_cart.js"}
</head>
<body{$reading_direction_tag}{if $body_onload ne ''} onload="javascript: {$body_onload}"{/if}>
{if !empty($config.Appearance.Facebook_pixel_code)}
    {$config.Appearance.Facebook_pixel_code}
{/if}
{if $main eq "product" || $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}
<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>
{/if}


{* ------------------------- *}
{if $main eq "product" || $main eq "catalog"}

<script src="{$SkinDir}/jquery.jcarousel.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
{literal}

    function func_load_ALL_ajax_carousels(load_ajax_sections, ajax_counter)
    {
        var load_ajax_sections_arr = load_ajax_sections.split(',');

        load_ajax_sections_arr.forEach(function (section_name, i, load_ajax_sections_arr) {
            setTimeout(function(){section_name.trim(); func_load_ajax_carousel_products(section_name)}.bind(section_name), 1400);
        });

    }

function func_load_ajax_carousel_products(section_name)
{
    var tmp_rand = Math.random();
    var options = {'section_name': section_name};
    {/literal}
    {if $product.productid ne ""}
        {literal}options['productid'] = {/literal}{$product.productid}{literal};{/literal}
    {/if}
    {literal}

    $.post('cidev_ajax_suggestions.php?rand=' + tmp_rand,options, function(data)
    {
        if (data)
        {
            var obj = $.parseJSON(data);
            var html = '<ul>';
            var a_href = '';

            var ga_page_name = '{/literal}{$ga_page_name}{literal}';

            if (obj) {
                $.each(obj.items, function () {
                    if (this.clean_url != '') {
                        a_href = this.clean_url + '/';
                    } else {
                        a_href = 'product.php?productid=' + this.productid;
                    }
                    ga_page_name = this.ga_param;
                    html += '<li class="google_impression_object" data-productid="'+this.productid+'" data-name="'+this.product+'" data-category="'+this.category+'" data-brand="'+this.brand+'" data-list="'+ga_page_name+'" data-price="'+this.price+'" data-position="'+this.N_key+'" class="active">' +
                        '<div style="text-align: center;">' +
                        '<a href="' + a_href + '" onclick="onProductClick(\'' + this.productid + '\',\'' + this.product + '\',\'' + this.category + '\',\'' + this.brand + '\',\'' + this.N_key + '\',\'' + ga_page_name + '\',\'' + this.price + '\'); return !ga.loaded;"><img src="' + this.src + '" alt="' + this.product + '"></a>' +
                        '<br />' + '<a href="' + a_href + '" onclick="onProductClick(\'' + this.productid + '\',\'' + this.product + '\',\'' + this.category + '\',\'' + this.brand + '\',\'' + this.N_key + '\',\'' + ga_page_name + '\',\'' + this.price + '\'); return !ga.loaded;">' + this.title + '</a>' +
                        '<br /> <font class="ProductPrice">Our Price: US$ ' + this.price + '</font>' +
                        '</div>' +
                        '</li>';
                });
            }

            html += '</ul>';

            if (data != "") {
                $("#" + section_name).show();
            }

            $('#jcarousel_' + section_name).html(html).parent().after('<ul class="pages"></ul>');
            jQuery(function ($) {
                'use strict';

                // -------------------------------------------------------------
                //   Basic Navigation
                // -------------------------------------------------------------
                (function () {
                    var $frame = $('#jcarousel_' + section_name);
                    var $wrap = $frame.parent().parent();

                    var options = {
                        horizontal: 1,
                        itemNav: 'basic',
                        smart: 1,
                        activateOn: 'click',
                        mouseDragging: 1,
                        touchDragging: 1,
                        releaseSwing: 1,
                        startAt: 0,
                        scrollBar: $wrap.find('.scrollbar'),
                        scrollBy: 0,
                        pagesBar: $wrap.find('.pages'),
                        activatePageOn: 'click',
                        speed: 300,
                        elasticBounds: 1,
                        easing: 'easeOutExpo',
                        dragHandle: 1,
                        dynamicHandle: 1,
                        clickBar: 1,

                        // Buttons
                        prevPage: $wrap.find('.jcarousel-control-prev'),
                        nextPage: $wrap.find('.jcarousel-control-next')
                    };
                    try {
                        var frame = new Sly('#jcarousel_' + section_name, options, {
                            load: checkCarouselsVisibility,
                            moveEnd: checkCarouselsVisibility
                        }).init();
                    }
                    catch(err) {
                    }


                }());

            });
        }
    });
}
{/literal}
//]]>
</script>
{/if}
{* ------------------------- *}


{if $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}



<div style="display: none;" id="load_next_productids" data-value="{include file="customer/main/infinite_products_load_next_productids.tpl"}"></div>
<div style="display: none;" id="LN_total_items" data-value="{$total_items}"></div>

{/if}


{* ------------------- *}
{include file="cidev_tracking_code.tpl" }
{* ------------------- *}

{include file="head.tpl" }
{include file="rectangle_top.tpl" }

{if $active_modules.SnS_connector}
{include file="modules/SnS_connector/header.tpl"}
{/if}
<!-- main area -->
{*
{include file="customer/search.tpl"}
*}
{* Start *}
{if ($main eq "catalog" && $current_category.category ne "") || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}
<script type="text/javascript">
//<![CDATA[
//func_load_more_next_productids('','Y');
//]]>
</script>
{/if}
{* End *}


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
<link id="pm_1" itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#PaymentMethodCreditCard" />
<link id="pm_2" itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#VISA" />
<link id="pm_3" itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#MasterCard" />
<link id="pm_4" itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#PayPal" />
{/if}

{if $use_schema_org eq "Y" && $main eq "product"}
<meta itemscope="" itemtype="http://schema.org/Product" itemref="so_image so_category so_name so_url so_description so_gtin so_weight so_brand so_manuf so_sku so_mpn so_model so_offer"/>
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

<!-- /central space -->
</td>

</tr>
</table>



{* ------------------------- *}
{if $main eq "catalog"}
<br />
<br />

<div id="recently_viewed_products" style="display: none;">{include file="customer/main/ajax_carousel_products.tpl" section_name="recently_viewed_products" section_title=$lng.lbl_recently_viewed_products}</div>

<script type="text/javascript">
    func_load_ALL_ajax_carousels("recently_viewed_products", 0);
</script>
{/if}
{* ------------------------- *}




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
    $(document).scroll(function () {
        clearTimeout($.data(this, 'scrollTimer'));
        $.data(this, 'scrollTimer', setTimeout(function() {
            checkCarouselsVisibility();
        }, 700));
    });
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
	{assign var="ecomm_totalvalue_replacement" value="ecomm_totalvalue: `$current_price`"}
{elseif $main eq "order_message"}
        {assign var="ecomm_prodid_replacement" value="ecomm_prodid: `$productids_in_cart_imploded`"}
        {assign var="ecomm_pagetype_replacement" value="ecomm_pagetype: 'purchase'"}
        {assign var="ecomm_totalvalue_replacement" value="ecomm_totalvalue: `$order_data_subtotal`"}
{/if}

	{$config.Company.cidev_google_adwords|replace:"ecomm_prodid: ''":"`$ecomm_prodid_replacement`"|replace:"ecomm_pagetype: 'siteview'":"`$ecomm_pagetype_replacement`"|replace:"ecomm_totalvalue: ''":"`$ecomm_totalvalue_replacement`"}
{/if}


{if $GTS_badge_code ne ""}
	{$GTS_badge_code}
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

/*
var element2 = document.createElement("script");
element2.src = "{/literal}{$SkinDir}{literal}/ajax_add_to_cart.js";
document.body.appendChild(element2);
*/

/*var element3 = document.createElement("script");
element3.src = "{/literal}{$SkinDir}{literal}/customer/popup_open.js";
document.body.appendChild(element3);

var element4 = document.createElement("script");
element4.src = "{/literal}{$SkinDir}{literal}/lib/colorbox/jquery.colorbox-min.js";
document.body.appendChild(element4);
*/
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

{if $main eq "product" || $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}
{literal}
/*
var element11 = document.createElement("script");
element11.src = "{/literal}{$SkinDir}{literal}/check_zipcode.js";
document.body.appendChild(element11);

var element12 = document.createElement("script");
element12.src = "{/literal}{$SkinDir}{literal}/cidev_ajax.js";
document.body.appendChild(element12);
*/
{/literal}
{/if}

{if $main eq "product" || $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search" || 1==1}
{literal}
    var element14 = document.createElement("script");
    element14.src = "{/literal}{$SkinDir}{literal}/ajax_notify_by_email.js";
    document.body.appendChild(element14);
{/literal}
{/if}
{literal}
}

$(document).ready(function(){
    downloadJSAtOnload();
});
</script>
{/literal}
{********************************************}

<script src="{$SkinDir}/ajax_home_page.js" type="text/javascript"></script>

{* --- viralmarketingbomb --- *}
{if $viralmarketingbomb_shown ne "Y" && $config.Company.pop_up_in && $config.Company.pop_up_code ne ""}
<script src="{$SkinDir}/jquery.bpopup.min.js" type="text/javascript"></script>
<script type="text/javascript">
{literal}
var t_openPopUp_seconds = {/literal}{$config.Company.pop_up_in}{literal};
t_openPopUp_seconds = parseInt(t_openPopUp_seconds);
t_openPopUp_seconds = t_openPopUp_seconds*1000;
var t_openPopUp=setTimeout(openPopUp,t_openPopUp_seconds);
function openPopUp()
{
 $('#element_to_pop_up').bPopup({
            contentContainer:'.content_pop_up',
            loadUrl: 'pop_up_viralmarketingbomb.php' //Uses jQuery.load()
 });
}
{/literal}
</script>
<div id="element_to_pop_up" style="display:none;">
<span class="button_pop_up b-close"><span>X</span></span>
{$config.Company.pop_up_code}
<span class="content_pop_up"></span>
</div>
{/if}
{* --- viralmarketingbomb --- *}

{if (($main eq "product") && $config.Appearance.Enable_desktop_notifications_on_product_page eq "Y")}
    <script type="text/javascript">
        {literal}
        var counter = 0;
        var notifyTimeOut = {/literal}{$config.Appearance.Desktop_notification_timeout}{literal} * 1000;
        var sOriginalTitle = document.title;
        var fireTitleChange = function(param){
            switch (counter) {
                case 0: document.title = "{/literal}{$lng.lb_suggest_notification_for_product_page}{literal}";
                    break;
                case 1: document.title = sOriginalTitle;
                    break;
            }
            counter++;
            if (counter > 1) counter = 0;
        };
        var interval_id;
        var timer_id;
        var notifytimer;
        var notificationEnable = true;
        var fireDeskTopNotify = function () {
            Notification.requestPermission( newMessage );

            function DisableNotification () {notificationEnable = false; }
            function newMessage(permission) {

                if( permission != "granted" || notificationEnable == false) return false;
                var notify = new Notification("{/literal}{$lng.lb_suggest_notification_for_product_page}{literal}", {
                    tag: "attention-notify",
                    body: "Look at the suggested similar products",
                    icon: "{/literal}{$product.tmbn_url}{literal}"
                });
                notify.onclick = function(event) { DisableNotification(); window.focus(); this.close(); };
                notify.onclose = function(event) { DisableNotification(); };
            }
        };

        $(window).on("blur focus", function(e) {
            var prevType = $(this).data("prevType");

            if (prevType != e.type) {   //  reduce double fire issues
                switch (e.type) {
                    case "blur":
                        if (!interval_id) {
                            timer_id = setTimeout(function () {
                                interval_id = setInterval(fireTitleChange, 3000);
                                var el = $("#products_also_bought_with_this_product, #related_products, #similar_products, #recently_viewed_products").filter(':visible:first');
                                var elOffset = el.offset().top;
                                var elHeight = el.height();
                                var windowHeight = $(window).height();
                                var offset;
                                if (elHeight < windowHeight) {
                                    offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
                                }
                                else {
                                    offset = elOffset;
                                }

                                $('html,body').animate({
                                            scrollTop: offset},
                                        'fast');

                                notifytimer = setTimeout(fireDeskTopNotify, 1000);


                            },notifyTimeOut);
                        }
                        break;
                    case "focus":
                        clearInterval(interval_id);
                        clearTimeout(timer_id);
                        clearTimeout(notifytimer);
                        interval_id = 0;
                        timer_id = 0;
                        document.title = sOriginalTitle;
                        break;
                }
            }

            $(this).data("prevType", e.type);
        });
        {/literal}

    </script>
{/if}

</body>
</html>
{/if}
