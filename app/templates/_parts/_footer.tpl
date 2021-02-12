<footer itemscope itemtype="http://schema.org/WPFooter">

    {insert "_parts/_bottom_menu.tpl"}

    <div class="footer-content">

        <div class="row contacts-presentations">
            <div class="column small-12 medium-7 large-7 left-side">

                <div class="present-icons show-for-medium">
                    <div class="row">
                        <div class="columns small-12">

                            {if true || !$.workingDayTimeNow}
                                <div class="s3stores-logo">
                                    <img src="" alt="S3 Stores, Inc." class="s3logo lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/logos/s3stores.svg">
                                </div>
                                <div class="all-times">
                                    <img src="" alt="{t 'Web order 24/7'}" class="lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/icons/footer/web_order.svg">
                                    <div class="content">
                                        <div class="title">
                                            {t 'Web Orders'}
                                        </div>
                                        <div>
                                            {t '24 hours a day, 7 days a week'}
                                        </div>
                                    </div>
                                </div>
                            {/if}

                            <ul class="no-bullet menu-list show-for-large email-support">
                                <li class="title-menu">
                                    {t 'Email Support'}
                                </li>
                                <li><a href="/contactus/">{t 'Contact Us'}</a></li>
                            </ul>

                        </div>
                    </div>
                </div>

                <div class="contacts">
                    <div class="row">
                        <div class="column small-12 ">
                            {raw $config.cidev_footer_code}
                        </div>
                    </div>
                </div>


            </div>

            <div class="column small-12 medium-5 large-5 right-side">
                <div class="socials show-for-medium">
                    <a href="https://www.facebook.com/s3stores/" rel="nofollow" target="_blank" class="facebook"></a>
                    <a href="https://www.twitter.com/s3stores/" target="_blank" rel="nofollow" class="twitter"></a>
                    <a href="https://www.youtube.com/channel/UCjE6xR1TriWo-hCDsbpvMKg" rel="nofollow" target="_blank" class="youtube"></a>
                    <a href="https://www.pinterest.com/s3storesinc/" target="_blank" rel="nofollow" class="pinterest"></a>
                    <a href="https://plus.google.com/118379608603424325840" target="_blank" rel="nofollow" class="googleplus"></a>
                </div>
                <ul class="no-bullet menu-list">
                    <li class="title-menu">
                        {t 'Join our newsletter'}
                    </li>

                    <li>
                        <form class="email-subscription" action="{url 'subscribe:send_message'}">
                            <input type="email" name="subscribe[email]" required placeholder="{t 'Your Email Address'}" value="">
                            <button class="waves waves-dark">
                                {t 'Send'}
                            </button>
                        </form>
                    </li>
                </ul>

                <div class="column small-12">
                    <button class="footer-scroll-up-button footer__scroll-up-button" type="button">
                        <svg class="footer-scroll-up-icon">
                            <use xlink:href="/static/frontend/dist/images/icons/sprite.svg#corner-white"></use>
                        </svg>
                        <span class="footer-scroll-up-title">{t 'up'}</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="row show-for-medium">
            <div class="column small-12">
                <div class="confirmations">
                    <div class="row">

                        <div class="column small-12 medium-7 large-7">

                            <ul class="no-bullet menu-list">
                                <li class="title-menu">
                                    {t 'Payment Methods'}
                                </li>
                                <li class="payment-methods">
                                    {set $payment_methods = $site->payment_methods->filter(['is_active' => 1])->order(['position'])->all()}
                                    {if $payment_methods }
                                        {foreach $payment_methods as $key => $method }
                                            <img src="" width="54" height="36" class="lazy-img" data-src="/{$method->logo}" alt="{$method->name}">
                                        {/foreach}
                                    {else}
                                        {foreach Modules\Sites\Models\PaymentMethodModel::active() as $key => $method}
                                            <img src="" width="54" height="36" class="lazy-img" data-src="/{$method->logo}" alt="{$method->name}">
                                        {/foreach}
                                    {/if}
                                </li>
                                <li class="fraud-orders">
                                    <a href="/ecomerce-fraud">{t 'Combating eCommerce Fraud'}</a>
                                    <a class="purchase-order" href="/purchase-orders">{t 'Purchase Orders'}</a>
                                </li>
                            </ul>

                        </div>

                        <div class="column small-12 medium-5 large-5 ">

                            <ul class="no-bullet menu-list">
                                <li class="title-menu">
                                    {t 'Shop with Confidence'}
                                </li>
                                <li class="confidence">
                                    <span id="bbb">
                                        <a rel="nofollow" target="_blank" href="https://www.bbb.org/western-ontario/business-reviews/online-retailer/s3-stores-in-chatham-on-1054268#bbbseal" title="S3 Stores, Inc., Online Retailer, Chatham, ON">
                                            <img class="lazy-img bbb-logo show-for-large" data-src="https://seal-london.bbb.org/logo/erhzbum/s3-stores-1054268.png" alt="S3 Stores, Inc., Online Retailer, Chatham, ON">
                                        </a>
                                    </span>

                                    <span id="g_review"></span>

                                    <span id="siteseal"></span>

                                </li>

                            </ul>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-menu">
        <div class="row">
            <div class="column small-12 medium-5 medium-order-2">
                <ul class="no-bullet">
                    {get_menu code='footer-menu'}
                </ul>
            </div>
            <div class="column small-12 medium-7 medium-order-1 copyright">
                {t 'Copyright ©'} {$config.start_year}-{date_add()|date:"Y"} {$gConfig.holding_company_name} {t 'All Rights Reserved.'}
            </div>
        </div>
    </div>
</footer>