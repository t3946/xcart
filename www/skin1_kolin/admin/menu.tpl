
{$xcartApp->template->render('base/old_admin_menu.tpl')}

{capture name=menu}

    {if $allowed_elements.LeftLink_Users eq "Y"}
        {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
            <a href="{$catalogs.admin}/users.php" class="VertMenuItems">{$lng.lbl_users}</a>
        {/if}
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
        <a href="{$catalogs.admin}/categories.php"
           class="VertMenuItems">{$lng.lbl_categories}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER" || $membership_code eq "ADMIN_TRACKING_NUMBER_ENTRY_OPERATOR")}
        <a href="{$catalogs.admin}/classification.php" class="VertMenuItems">Classification</a>
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
        <a href="{$catalogs.admin}/empty_categories.php" class="VertMenuItems">Empty
            categories{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
        {if $current_membership_flag ne 'FS'}
            <a href="{$catalogs.admin}/seed_categories.php"
               class="VertMenuItems">{$lng.lbl_seed_categories}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>
        {/if}
    {/if}


    {if $active_modules.Manufacturers}
        <a href="{$catalogs.admin}/manufacturers.php?word=num" class="VertMenuItems">{$lng.lbl_manufacturers}</a>
    {/if}
    {if $active_modules.Brands}
        <a href="{$catalogs.admin}/brands.php?word=num" class="VertMenuItems">{$lng.lbl_brands}</a>
    {/if}
    {if $active_modules.Wishlist}
        <a href="{$catalogs.admin}/wishlists.php" class="VertMenuItems">{$lng.lbl_wish_lists}</a>
    {/if}
    {if $active_modules.Simple_Mode eq ""}<a href="{$catalogs.admin}/search.php"
                                             class="VertMenuItems">{$lng.lbl_admin_menu_products}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>{/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
        <a href="{$catalogs.admin}/grandfathered_products.php"
           class="VertMenuItems">{$lng.lbl_grandfathered_products}</a>
    {/if}
    <a href="{$catalogs.admin}/az_operators.php" class="VertMenuItems">Amazon verification</a>
    {if !($membership_code eq "ADMIN_PRODUCT_MANAGER")}
        <a href="{$catalogs.admin}/checks_deposited.php" class="VertMenuItems">Checks deposited</a>
    {/if}

    {if !($membership_code eq "ADMIN_PRODUCT_MANAGER")}
        <a href="{$catalogs.admin}/order_reports.php" class="VertMenuItems">{$lng.lbl_order_reports}</a>
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER" || $membership_code eq "ADMIN_TRACKING_NUMBER_ENTRY_OPERATOR")}
        <a href="{$catalogs.admin}/order_status_notifications.php"
           class="VertMenuItems">{$lng.lbl_order_status_notifications}</a>
    {/if}

    {if !($membership_code eq "ADMIN_PRODUCT_MANAGER")}
        <a href="{$catalogs.admin}/shipping_quotes_log.php?mode=search" class="VertMenuItems">Shipping quotes log</a>
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER" || $membership_code eq "ADMIN_TRACKING_NUMBER_ENTRY_OPERATOR")}
        <a href="{$catalogs.admin}/product_question_search.php?mode=search&status=all&from_dashboard=Y"
           class="VertMenuItems">Product questions</a>
        <a href="{$catalogs.admin}/reconciliation.php" class="VertMenuItems">Reconciliation</a>
        {if $allowed_elements.LeftLink_Reports eq "Y"}
            {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER")}
                <a href="{$catalogs.admin}/reports.php" class="VertMenuItems">Reports</a>
            {/if}
        {/if}
        <a href="{$catalogs.admin}/ab_testing.php" class="VertMenuItems">A/B testing</a>
        <a href="{$catalogs.admin}/backprocess_logs.php" class="VertMenuItems">Backprocess logs</a>
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
        {if $active_modules.News_Management}
            <a href="{$catalogs.admin}/news.php"
               class="VertMenuItems">{$lng.lbl_news_management}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>
        {/if}
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
        {if $active_modules.Mailchimp_Subscription}
            <a href="{$catalogs.admin}/mailchimp_news.php"
               class="VertMenuItems">{$lng.lbl_mailchimp_news_management}</a>
        {/if}
    {/if}
    <a href="{$catalogs.admin}/statistics.php"
       class="VertMenuItems">{$lng.lbl_statistics}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>
    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
        <a href="{$catalogs.admin}/shipping.php" class="VertMenuItems">{$lng.lbl_shipping_methods}</a>
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
        <a href="{$catalogs.admin}/track_links.php" class="VertMenuItems">{$lng.lbl_tracking_links}</a>
    {/if}

    {if $active_modules.Customer_Reviews ne ""}
        <a href="{$catalogs.admin}/ratings_edit.php" class="VertMenuItems">{$lng.lbl_edit_ratings}</a>
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
        <a href="{$catalogs.admin}/countries.php" class="VertMenuItems">{$lng.lbl_countries}</a>
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
        <a href="{$catalogs.admin}/states.php" class="VertMenuItems">{$lng.lbl_states}</a>
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
        <a href="{$catalogs.admin}/taxes.php" class="VertMenuItems">{$lng.lbl_taxing_system}</a>
    {/if}

    {if $active_modules.Stop_List ne ""}
        {include file="modules/Stop_List/stop_list_menu.tpl"}
    {/if}
    {if $active_modules.Benchmark ne ""}
        {include file="modules/Benchmark/menu.tpl"}
    {/if}
    {if $active_modules.Feature_Comparison ne ""}
        {include file="modules/Feature_Comparison/admin_menu.tpl"}
    {/if}
    {if $active_modules.RMA ne ""}
        {include file="modules/RMA/admin_menu.tpl"}
    {/if}
    {if $active_modules.Gift_Certificates ne ""}
        {include file="modules/Gift_Certificates/gc_admin_menu.tpl"}
    {/if}
    {if $active_modules.Subscriptions ne ""}
        {include file="modules/Subscriptions/subscriptions_menu.tpl"}
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER")}
        {if $active_modules.UPS_OnLine_Tools ne ""}
            <a href="{$catalogs.admin}/ups.php" class="VertMenuItems">{$lng.lbl_ups_online_tools}</a>
        {/if}
    {/if}
    {if $active_modules.Survey ne ""}
        {include file="modules/Survey/admin_menu.tpl"}
    {/if}

    {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
        <a href="{$catalogs.admin}/inv_update_ex.php"
           class="VertMenuItems">{$lng.lbl_update_inventory}{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>
    {/if}

    {if $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER" || $login eq "michael2"}
        <a href="{$catalogs.admin}/bpu.php"
           class="VertMenuItems">BPU{if $active_modules.Multiple_Storefronts} {$lng.lbl_sf}{/if}</a>
    {/if}

    {if $active_modules.Discount_Coupons ne ""}
        <a href="{$catalogs.admin}/coupons.php" class="VertMenuItems">{$lng.lbl_coupons}</a>
    {/if}

{/capture}

{ include file="menu_admin.tpl" dingbats="dingbats_categorie.gif" menu_title=$lng.lbl_management menu_content=$smarty.capture.menu }
