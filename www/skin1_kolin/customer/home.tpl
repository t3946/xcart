{if $printable ne ''}
    {include file="customer/home_printable.tpl"}
{else}
<!DOCTYPE html>
    <html lang="en-US">
        <head>
            {if $config.SEO.clean_urls_enabled eq "Y"}
                <base href="{$xcartApp->request->getHostInfo()}/" />
            {/if}

            {if $current_storefront_info.storefrontid ne ""}
                <link rel="shortcut icon" href="{$xcart_web_dir}/image.php?id={$current_storefront_info.storefrontid}&amp;type=F" type="image/vnd.microsoft.icon"/>
            {else}
                <link rel="shortcut icon" href="{$xcart_web_dir}/image.php?id=0&amp;type=F" type="image/vnd.microsoft.icon"/>
            {/if}

            {config_load file="$skin_config"}

            <link rel="stylesheet" href="{$SkinDir}/lib/jqueryui/jquery.ui.theme.css" />
            <link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />
            <link rel="stylesheet" href="{$SkinDir}/jquery.tooltip.css" />
            <link rel="stylesheet" href="{$SkinDir}/US_City_List/jquery.autocomplete.css" />
            <link rel="stylesheet" href="{$SkinDir}/lib/colorbox/colorbox.css" />
            <!--[if IE]>
            <link rel="stylesheet" href="{$SkinDir}/skin1.IE.css" type="text/css" media="all" />
            <![endif]-->

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

            {if $canonical_url}
                {if $oProduct && $oProduct->isGroupChild()}
                    <link rel="canonical" href="{if $oProduct->parent}{$oProduct->parent->getUrl()}{/if}" />
                {else}
                    <link rel="canonical" href="{$xcartApp->request->getHostInfo()}/{$canonical_url}" />
                {/if}
            {/if}

            {if $main eq "catalog" && $current_category.category eq "" && $clean_url_data.resource_type ne "K"}
                <link rel="canonical" href="{$xcartApp->request->getHostInfo()}/"/>
            {/if}

            {if $main eq 'product' && $oProduct}
                <link rel="amphtml" href="{$oProduct->getAmpAbsoluteUrl(true)}">
            {/if}

        </head>

        <body{$reading_direction_tag}{if $body_onload ne ''} onload="javascript: {$body_onload}"{/if}>

                {$xcartApp->template->render('inSmarty/raw_flash.tpl')}

                {if !empty($config.Appearance.Facebook_pixel_code)}
                    {$config.Appearance.Facebook_pixel_code}
                {/if}

                {if $main eq "product" || $main eq "catalog"}

                    <script type="text/javascript">
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

                                if (obj !== undefined) {
                                    $.each(obj.items, function () {
                                        if (this.clean_url != '') {
                                            a_href = this.clean_url;
                                        } else {
                                            a_href = 'product.php?productid=' + this.productid;
                                        }
                                        ga_page_name = this.ga_param;
                                        html += '<li class="google_impression_object" data-product-id="'+this.productid+'" data-name="'+this.product+'" data-category="'+this.category+'" data-brand="'+this.brand+'" data-list="'+ga_page_name+'" data-price="'+this.price.toFixed(2)+'" data-position="'+this.N_key+'" class="active">' +
                                            '<div style="text-align: center;"><div style="width:150px;height:150px; margin:0 auto;">' +
                                            '<a href="' + a_href + '" onclick="onProductClick(\'' + this.productid + '\',\'' + this.product + '\',\'' + this.category + '\',\'' + this.brand + '\',\'' + this.N_key + '\',\'' + ga_page_name + '\',\'' + this.price + '\'); return !ga.loaded;">';
                                        if (this.thumb && this.thumb.length) {
                                            html += this.thumb;
                                        }
                                        html += '</a></div>' +
                                            '<br />' + '<a href="' + a_href + '" onclick="onProductClick(\'' + this.productid + '\',\'' + this.product + '\',\'' + this.category + '\',\'' + this.brand + '\',\'' + this.N_key + '\',\'' + ga_page_name + '\',\'' + this.price.toFixed(2) + '\'); return !ga.loaded;">' + this.product + '</a>';
                                        if (this.is_group === true) {
                                            if (this.price > 0) {
                                                var range = '';
                                                if (this.price !== this.price_2) {
                                                    range = ' - US$ ' + this.price_2.toFixed(2);
                                                }
                                                html += '<br /> <span class="ProductPrice">US$ ' + this.price.toFixed(2) + range + '</span>';
                                            }
                                        } else
                                        {
                                            html += '<br /> <span class="ProductPrice">US$ ' + this.price.toFixed(2) + '</span>';
                                        }
                                        html += '</div>' +
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
                    </script>
                {/if}


                {if $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}
                    <div style="display: none;" id="load_next_productids" data-value="{include file="customer/main/infinite_products_load_next_productids.tpl"}"></div>
                    <div style="display: none;" id="LN_total_items" data-value="{$total_items}"></div>
                {/if}

                {include file="cidev_tracking_code.tpl" }

                {if !($usertype eq "A" || $usertype eq "P")}
                    <script type="text/javascript">
                        ga('send', 'pageview');
                    </script>
                {/if}


                {include file="head.tpl" }

                {include file="rectangle_top.tpl" }

                {if $active_modules.SnS_connector}
                    {include file="modules/SnS_connector/header.tpl"}
                {/if}


                {if !($main eq "catalog" && $current_category.category eq "" && $current_seed_category eq '') && $main ne "order_message"}
                    <div style="margin-left: 10px;">{include file="location.tpl"}</div>
                {/if}

                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        {if $active_modules.CIDEV_Best_Search_Filter eq "" || $main eq "catalog" || $main eq "brand_products" }
                            <td class="VertMenuLeftColumn">
                                <br>
                                {if $categories ne "" and ($active_modules.Fancy_Categories ne "" or $config.General.root_categories eq "Y" or $subcategories ne "")}
                                    {include file="customer/categories.tpl" }
                                {/if}

                                {if $active_modules.Bestsellers ne "" and $config.Bestsellers.bestsellers_menu eq "Y"}
                                    {include file="modules/Bestsellers/menu_bestsellers.tpl" }
                                {/if}

                                {if $active_modules.Survey && $menu_surveys}
                                    {foreach from=$menu_surveys item=menu_survey}
                                        {include file="modules/Survey/menu_survey.tpl"}
                                        <br />
                                    {/foreach}
                                {/if}

                                {if $variant_id_for_point5 ne "" && $variant_id_for_point5 eq "0" && !($main eq "catalog" && $current_category.category eq "")}
                                    <br />
                                    {assign var="social_buttons_data_services" value=$config.Appearance.social_buttons_data_services}
                                    {$config.Appearance.social_buttons_script_code|replace:"[data-services]":"$social_buttons_data_services"|replace:"[size]":"medium"}
                                {/if}

                                <img src="{$ImagesDir}/spacer.gif" width="156" height="1" alt="" />
                            </td>
                        {/if}

                        <td valign="top">
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
                                <meta itemscope="" itemtype="http://schema.org/Product" itemref="{if !$oProduct->isGroupRoot()}so_image so_gtin so_weight{/if} so_category so_name so_url so_description so_brand so_manuf so_sku so_mpn so_model so_offer"/>
                            {/if}

                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td colspan=3 height="10">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff" colspan=3>
                                        <div id="google_search_result_block">
                                            {$config.Search_products.search_products_result_code}
                                        </div>
                                        <div id="main">
                                            {include file="customer/home_main.tpl"}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </td>

                    </tr>
                </table>

            {if $main eq "catalog"}
                <br />
                <br />

                <div id="recently_viewed_products" style="display: none;">{include file="customer/main/ajax_carousel_products.tpl" section_name="recently_viewed_products" section_title=$lng.lbl_recently_viewed_products}</div>

                <script type="text/javascript">
                    func_load_ALL_ajax_carousels("recently_viewed_products", 0);
                </script>
            {/if}

            {if $active_modules.Brands ne "" and $config.Brands.brands_menu eq "Y" and $main ne "sitemap_customer"  }
                <div class="bottom-block" style="margin: 9px 10px 0px 10px; padding: 8px;">{include file="modules/Brands/menu_brands_footer.tpl"}</div>
            {/if}

            {if $main eq "catalog" && $current_category.category eq "" && $keyphrase eq ''}
                <div style="margin: 9px 10px 0px 10px; padding: 8px;" class="bottom-block">
                    <div class="ship_cities_link"><a href="#" style="margin-left: 13px;">{$lng.lbl_seo_cities_anchor}</a></div>
                    <div id="ship_cities_text">
                        {$lng.lbl_seo_cities}
                    </div>
                </div>
            {/if}

            {if ($active_modules.Multiple_Storefronts ne "" and $sf_links ne '' and $main ne "sitemap_customer") && ($main eq "catalog" && $current_category.category eq "") && $area_selector != "keyword"}
                <div class="bottom-block inter-sf" style="margin: 10px 10px 0px 10px; padding: 8px;">{include file="modules/Multiple_Storefronts/menu_storefronts_footer.tpl"}</div>
            {/if}

            {include file="rectangle_bottom.tpl" }

            {if $main eq "product"}
                <script type="text/javascript">
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

            {if !($main eq "fast_lane_checkout")}
                <script async src="{$SkinDir}/check_email_script.js" type="text/javascript"></script>
            {/if}

            {if !($main eq "product" || $main eq "fast_lane_checkout")}
                <script async src="{$SkinDir}/common.js" type="text/javascript"></script>
            {/if}

            {if !($main eq "product")}

                <script async src="{$SkinDir}/jquery.tooltip.js" type="text/javascript"></script>
                <script async src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js" type="text/javascript"></script>
            {/if}

            {if $main eq "product"}
                <script async src="{$SkinDir}/main/popup_image.js" type="text/javascript"></script>
            {/if}

            <script async src="{$SkinDir}/ajax_notify_by_email.js" type="text/javascript"></script>
            <script async src="{$SkinDir}/ajax_home_page.js" type="text/javascript"></script>

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

            {$xcartApp->template->render('inSmarty/raw_static_notifications.tpl')}

            {if ($main eq "product")}

                <script defer src="{$SkinDir}/jquery.tooltip.js" type="text/javascript"></script>
                <script defer type="text/javascript" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
                <script async type="text/javascript" src="{$SkinDir}/js/spinner.js"></script>
                <script async type="text/javascript" src="{$SkinDir}/js/group.js"></script>
            {/if}

            <script async type="text/javascript" src="{$SkinDir}/js/jquery.visible.min.js"></script>
            <script async src="{$SkinDir}/js/google_analytics_impressions.js" type="text/javascript"></script>


            <script async src="{$SkinDir}/js/infinite_scroll.js" type="text/javascript"></script>
            <script async src="{$SkinDir}/js/sly.min.js" type="text/javascript"></script>

            {include file="main/include_js.tpl" src="ajax_add_to_cart.js"}

            {if $main eq "product" || $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}
                <script async src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>
            {/if}

        </body>
    </html>
{/if}
