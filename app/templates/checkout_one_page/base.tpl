{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}


{block 'schema_page_type'}itemtype="http://schema.org/CollectionPage"{/block}
{block 'noindex'}
    <meta name="robots" content="noindex">
{/block}


{block "header"}
    <header class="cart-header" itemscope itemtype="http://schema.org/WPHeader">

        <section class="logo_menu">
            <div class="row align-justify">
                <div class="columns shop-logo-block">
                    <a href="/">
                        <img src="{$uri}/static/frontend/dist/images/logos/sites/{$.getSite->code|lower}/logo.svg"
                             alt="{$.getSiteConfig->company_name->value}" class="show-for-large logo-big">

                        <img src="{$uri}/static/frontend/dist/images/logos/sites/{$.getSite->code|lower}/logo-small.svg"
                             alt="{$.getSiteConfig->company_name->value}"
                             class="show-for-small hide-for-large logo-small">
                    </a>
                </div>
                <div class="columns s3-logo-block">
                    <a href="" class="s3-logo-big-link logo-link">
                        <img src="{$uri}/static/frontend/dist/images/logos/s3stores.svg"
                             alt="s3stores"
                             class="show-for-large s3-logo-big">
                    </a>

                    <a href="" class="s3-logo-small-link logo-link">
                        <img src="{$uri}/static/frontend/dist/images/logos/s3stores_logo.svg"
                             alt="s3stores"
                             class="show-for-small hide-for-large s3-logo-small">
                    </a>
                </div>
                <div class="columns contacts-logo-block hide-for-small show-for-large">
                    <div class="working-hours {if $.workingDayTimeNow}active{else}inactive{/if}">
                        <div class="text-order-online">
                            <span class="green-circle-icon"></span>
                            <span class="grey-text-label">{t 'Order online or call us. Operators are standing by!'}</span>
                        </div>
                        <div class="phone">
                            <span class="phone-number">{$config.local_phone}</span>
                            <span class="phone-number">{$config.cidev_top_header_code}</span>
                        </div>
                    </div>
                    <div class="after-hours {if !$.workingDayTimeNow}active{else}inactive{/if}">
                        <div class="text-order-online">
                            <img src="{$uri}/static/frontend/images/icons/cart/place_order_online_icon.svg"
                                 alt=""
                                 class="clock-icon">
                            <span>{t 'Place order online 24/7 or'}</span>
                        </div>
                        <div class="phone">
                            {if $config.cidev_top_header_code}
                                <span class="phone-label">{t 'Call us toll free'}</span>
                                <span class="phone-number">{$config.cidev_top_header_code}</span>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {block "breadcrumbs"}
            {set $breadcrumbs = $.getCartBreadcrumbs}
            {if $breadcrumbs}
                <div class="row cart-steps-container">

                    <section class="padding-0 overflow-hidden columns">
                        <ul class="checkout-steps-list no-bullet">
                            <li class="checkout-step checkout-step_one-page checkout-step_inactive">
                                <a href="#" class="checkout-step-link checkout-step-link_inactive">
                                    <span class="step-label">Shopping cart</span>
                                </a>
                                <div class="checkout-arrow-right checkout-arrow-right_active"></div>
                            </li>
                            <li class="checkout-step checkout-step_one-page checkout-step_active">
                                <span class="checkout-step-link checkout-step-link_active">
                                    <span class="step-label">Checkout</span>
                                </span>
                            </li>
                        </ul>
                    </section>

                </div>
            {/if}
        {/block}


    </header>
{/block}

{block "content-wrapper"}
    <div class="cart_shipping-page default-content-page">
        {block "content"}{/block}
    </div>
{/block}


{block 'offcanvas-menu-left'}{/block}