{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}


{block 'schema_page_type'}itemtype="http://schema.org/CollectionPage"{/block}
{block 'noindex'}
    <meta name="robots" content="noindex">
{/block}


{block "header"}
    <header class="checkout-hat" itemscope itemtype="http://schema.org/WPHeader">
        <div class="row checkout-hat-wrapper">
            <div class="columns large-4 medium-6 checkout-hat-logo">
                    <a href="/">
                        <img src="{$uri}/static/frontend/dist/images/logos/sites/{$.getSite->code|lower}/logo.svg"
                             alt="{$.getSiteConfig->company_name->value}"
                             class="show-for-large logo-big checkout-hat-logo-image"
                        >

                        <img src="{$uri}/static/frontend/dist/images/logos/sites/{$.getSite->code|lower}/logo-small.svg"
                             alt="{$.getSiteConfig->company_name->value}"
                             class="show-for-small hide-for-large logo-small checkout-hat-logo-image">
                    </a>
                </div>

            <div class="columns large-4 medium-6 checkout-hat-logo checkout-hat-logo-company">
                    <img src="/static/frontend/images/logos/s3stores_footer.svg"
                         alt="s3stores"
                         class="s3-logo-big checkout-hat-logo-image">
                </div>

            <div class="columns contacts-logo-block hide-for-small show-for-large text-align--right">
                {if $.workingDayTimeNow}
                    <div class="working-hours inline-block">
                        <div class="text-order-online">
                            <span class="green-circle-icon"></span>
                            <span class="working-hours-label">{t 'Order online or call us. Operators are standing by!'}</span>
                        </div>

                        <div class="checkout-hat-phone-group">
                            <span class="checkout-hat-phone-number">{$config.local_phone}</span>
                            <span class="checkout-hat-phone-number">{$config.cidev_top_header_code}</span>
                        </div>
                    </div>
                {else}
                    <div class="after-hours">
                        <div class="text-order-online">
                            <img src="{$uri}/static/frontend/images/icons/cart/place_order_online_icon.svg"
                                 class="clock-icon"
                            >
                            <span>{t 'Place order online 24/7 or'}</span>
                        </div>

                        <div>
                            {if $config.cidev_top_header_code}
                                {t 'Call us toll free'}
                                <span class="checkout-hat-phone-number">{$config.cidev_top_header_code}</span>
                            {/if}
                        </div>
                    </div>
                {/if}
            </div>
        </div>
    </header>
    <script>
      dataProvider.set( 'stripe', {
        publicKey: "{$checkout_form->public_key}",
        paymentIntent: "{$checkout_form->stripe_payment_intent}",
        fieldId: "CheckoutForm_pbc_card_details",
      } );
    </script>
{/block}

{block "content-wrapper"}
    <div data-component="checkout"></div>
    <div class="cart_shipping-page default-content-page">
        {block "content"}{/block}
    </div>
{/block}


{block 'offcanvas-menu-left'}{/block}
{block 'search-menu'}{/block}