{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}


{block 'schema_page_type'}itemtype="http://schema.org/CollectionPage"{/block}

{block "header"}
    <header class="cart-header" itemscope itemtype="http://schema.org/WPHeader">

        <section class="logo_menu">
            <div class="row align-justify">
                <div class="columns shop-logo-block">
                    <a href="/">
                        <img src="/static/frontend/dist/images/logos/sites/{$.getSite->code|lower}/logo.svg"
                             alt="{$.getSiteConfig->company_name->value}" class="show-for-large logo-big">

                        <img src="/static/frontend/dist/images/logos/sites/{$.getSite->code|lower}/logo-small.svg"
                             alt="{$.getSiteConfig->company_name->value}"
                             class="show-for-small hide-for-large logo-small">
                    </a>
                </div>
                <div class="columns verified-secured-logo-block show-for-medium hide-for-large">
                    <img src="/static/frontend/dist/images/logos/verified_secured_logo.svg"
                         alt="verified&secured"
                         class="secured-logo-big">
                </div>
                <div class="columns s3-logo-block">
                    <a href="" class="s3-logo-big-link logo-link">
                        <img src="/static/frontend/dist/images/logos/s3stores.svg"
                                    alt="s3stores"
                                    class="show-for-large s3-logo-big">
                    </a>

                    <a href="" class="secured-logo-big-link logo-link">
                        <img src="/static/frontend/dist/images/logos/verified_secured_logo.svg"
                                    alt="verified&secured"
                                    class="show-for-large secured-logo-big">
                    </a>

                    <a href="" class="s3-logo-small-link logo-link">
                        <img src="/static/frontend/dist/images/logos/s3stores_logo.svg"
                                    alt="s3stores"
                                    class="show-for-small hide-for-large s3-logo-small">
                    </a>
                </div>
                <div class="columns contacts-logo-block hide-for-small show-for-large">
                    <div class="working-hours active">
                        <div class="text-order-online">
                            <span class="green-circle-icon"></span>
                            <span class="grey-text-label">Order online or call us. Operators are standing by!</span>
                        </div>
                        <div class="phone">
                            <span class="phone-number"><span class="small-number">(616)</span> 259-5711</span>
                            <span class="phone-number">1-800-929-2431</span>
                        </div>
                    </div>
                    <div class="after-hours inactive">
                        <div class="text-order-online">
                            <img src="/static/frontend/images/icons/cart/place_order_online_icon.svg"
                                 alt=""
                                 class="clock-icon">
                            <span>Place order online 24/7</span>
                        </div>
                        <div class="phone">
                            <span class="phone-label">Call to free</span>
                            <span class="phone-number">1-800-929-2431</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {set $breadcrumbs = $.getCartBreadcrumbs}
        {if $breadcrumbs}

        <div class="row cart-steps-container">

            {if !$breadcrumbs->isFirstStage()}
            <a class="columns shrink cart-steps-back hide-for-large" href="{$breadcrumbs->getPrevStage().url}">
                <span class="img">
                    <img src="/static/frontend/dist/images/icons/cart/arrow_left_shop_more.svg" alt="">
                </span>
                <span class="text">BACK</span>
            </a>
            {/if}

            <section class="cart-steps-section columns">
                <ul class="cart-steps-items no-bullet">
                    {foreach $breadcrumbs as $key => $item}
                    <li class="cart-step{if $breadcrumbs->isStagePassed($key)} inactive{/if} {if $breadcrumbs->getActive() == $key} active{/if}">
                        {if !$item.url || $breadcrumbs->getActive() == $key}
                            <span class="step-link">
                                <span class="step-number">{$key+1}</span>
                                <span class="step-label">{$item['label']}</span>
                            </span>
                        {else}
                            <a href="{$item['url']}" class="step-link">
                                <span class="step-number">{$key+1}</span>
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


    </header>
{/block}

{block "content-wrapper"}
    <div class="cart_shipping-page default-form">
        {block "content"}{/block}
    </div>
{/block}