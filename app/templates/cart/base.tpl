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
                <div class="columns s3-logo-block shrink">
                    <img src="/static/frontend/dist/images/logos/sites/{$.getSite->code|lower}/big_s3_logo.svg"
                         alt="s3stores" class="show-for-large s3-logo-big">
                    <img src="/static/frontend/dist/images/logos/sites/{$.getSite->code|lower}/verified_secured_logo.svg"
                         alt="verified&secured" class="show-for-large secured-logo-big">
                    <img src="/static/frontend/dist/images/logos/sites/{$.getSite->code|lower}/small_s3_logo.svg"
                         alt="s3stores" class="show-for-small hide-for-large s3-logo-small">
                </div>
                <div class="columns contacts-logo-block hide-for-small show-for-large">
                    <div class="working-hours">
                        <div class="text-order-online">
                            <span class="green-circle-icon">
                                <img src="/static/frontend/dist/images/icons/cart/white_check_mark_icon.svg" alt=""
                                     class="white-check-mark">
                            </span>
                            <span>Order online or call us. Operators are standing by!</span>
                        </div>
                        <div class="phone">
                            <span class="phone-number">(616) 259-5711</span>
                            <span class="phone-number">1-800-929-2431</span>
                        </div>
                    </div>
                    <div class="after-hours">
                        <div class="text-order-online">
                            <img src="/static/frontend/dist/images/icons/cart/place_order_online_icon.svg" alt=""
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

        <div class="row cart-steps-container">
            <a class="columns shrink cart-steps-back">
                <img src="/static/frontend/dist/images/icons/cart/cart_small_arrow_back.png" alt="">
                <span>BACK</span>
            </a>

            <section class="cart-steps-section columns">
                <ul class="cart-steps-items no-bullet">
                    <li class="cart-step">
                        <a href="" class="step-link">
                            <span class="step-number">1.</span>
                            <span class="step-label">Shopping cart</span>
                        </a>
                    </li>
                    <li class="cart-step">
                        <a href="" class="step-link">
                            <span class="step-number">2.</span>
                            <span class="step-label">Shipping Address</span>
                        </a>
                    </li>
                    <li class="cart-step">
                        <a href="" class="step-link">
                            <span class="step-number">3.</span>
                            <span class="step-label">Shipping & payment options</span>
                        </a>
                    </li>
                    <li class="cart-step">
                        <a href="" class="step-link">
                            <span class="step-number">4.</span>
                            <span class="step-label">order review</span>
                        </a>
                    </li>
                    <li class="cart-step">
                        <a href="" class="step-link">
                            <span class="step-number">5.</span>
                            <span class="step-label">Payment</span>
                        </a>
                    </li>
                </ul>
            </section>


        </div>


    </header>
{/block}