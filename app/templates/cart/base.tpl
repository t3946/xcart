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
                        <img src="{$uri}/static/frontend/dist/images/logos/sites/{$site->code|lower}/logo.svg"
                             alt="{$site->company_name}" class="show-for-large logo-big">

                        <img src="{$uri}/static/frontend/dist/images/logos/sites/{$site->code|lower}/logo-small.svg"
                             alt="{$site->company_name}"
                             class="show-for-small hide-for-large logo-small">
                    </a>
                </div>
                {*<div class="columns verified-secured-logo-block show-for-medium hide-for-large">
                    <img src="/static/frontend/dist/images/logos/verified_secured_logo.svg"
                         alt="verified&secured"
                         class="secured-logo-big">
                </div>*}
                {if ($site->lang->lang_code !== 'ru')}
                    <div class="columns s3-logo-block">
                        <div class="s3-logo-big-link logo-link">
                            <div id="calculate-shipping-target" data-uri="{$uri}"></div>
                        </div>

                        <div href="" class="s3-logo-small-link logo-link">
                            <img src="{$uri}/static/frontend/dist/images/logos/s3stores_logo.svg"
                                 alt="s3stores"
                                 class="show-for-small hide-for-large s3-logo-small">
                        </div>
                    </div>
                {/if}
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

                    {if !$breadcrumbs->isFirstStage()}
                        <a class="columns shrink cart-steps-back hide-for-large"
                           href="{$breadcrumbs->getPrevStage().url}">
                            <span class="img">
                                <img src="{$uri}/static/frontend/dist/images/icons/cart/arrow_left_shop_more.svg"
                                     alt="">
                            </span>
                            <span class="text">{t 'BACK'}</span>
                        </a>
                    {/if}

                    <section class="cart-steps-section columns">
                        <ul class="cart-steps-items no-bullet">
                            {foreach $breadcrumbs as $key => $item}
                                <li class="cart-step{if $breadcrumbs->isStagePassed($key)} inactive{/if} {if $breadcrumbs->getActive() == $key} active{/if}">
                                    {if !$item.url || $breadcrumbs->getActive() == $key}
                                        <span class="step-link">
                                            <span class="step-label">{$item['label']}</span>
                                        </span>
                                    {else}
                                        <a href="{$item['url']}" class="step-link" rel="nofollow">
                                            <span class="step-label">{$item['label']}</span>
                                        </a>
                                    {/if}
                                    <div class="arrow-right"></div>
                                </li>
                            {/foreach}
                        </ul>
                    </section>
                </div>
            {/if}
        {/block}


    </header>
{/block}

{block "content-wrapper"}
    <div class="cart_shipping-page default-content-page default-form">
        {block "content"}{/block}
    </div>
{/block}


{block 'offcanvas-menu-left'}{/block}
{block 'search-menu'}{/block}