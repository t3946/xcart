<footer itemscope itemtype="http://schema.org/WPFooter">

    {insert "_parts/_bottom_menu.tpl"}

    <div class="footer-content">

        <div class="row contacts-presentations">
            <div class="column small-12 medium-7 large-7 left-side">

                <div class="present-icons show-for-medium">
                    <div class="row">
                        <div class="columns small-12">

                            {if !$.workingDayTimeNow}
                                <div class="s3stores-logo">
                                    <img src="" alt="S3 Stores, Inc." class="s3logo lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/logos/s3stores.svg">
                                </div>

                                <div class="all-times">
                                    <img src="" alt="Web order 24/7" class="lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/icons/footer/web_order.svg">
                                    <div class="content">
                                        <div class="title">
                                            Web Orders
                                        </div>
                                        <div>
                                            24 hours a day, 7 days a week
                                        </div>
                                    </div>
                                </div>
                            {/if}

                            <ul class="no-bullet menu-list show-for-large email-support">
                                <li class="title-menu">
                                    Email Support
                                </li>
                                <li><a href="/contactus/">Contact Us</a></li>
                            </ul>

                        </div>
                    </div>
                </div>

                <div class="contacts">
                    <div class="row">
                        <div class="column small-12 ">

                            <ul class="no-bullet menu-list">
                                <li class="title-menu">
                                    Telephone Customer Service
                                </li>
                                <li>Mon-Fri: 9 a.m. to 5 p.m. EST</li>
                                <li class="toll-free">Toll Free: <span class="number">1-800-929-2431</span></li>
                                <li>Tel: (616) 259-5711</li>
                                <li>Fax: (813) 944-4516</li>
                            </ul>

                            <ul class="no-bullet menu-list show-for-medium-only">
                                <li class="title-menu">
                                    Contact Us
                                </li>
                                <li><a href="/contactus/#form">Web Form</a></li>
                                <li><a href="/contactus/#email">Email</a></li>
                                <li><a href="/contactus/#address">USA address</a></li>
                                <li><a href="/contactus/#address">Canadian address</a></li>
                            </ul>

                            <ul class="no-bullet menu-list show-for-large">
                                <li class="title-menu">
                                    USA Address
                                </li>
                                <li>S3 Stores, Inc.</li>
                                <li>2885 Sanford Ave SW #12717</li>
                                <li>Grandville, MI 49418</li>
                                <li>USA</li>
                            </ul>

                            <ul class="no-bullet menu-list show-for-large">
                                <li class="title-menu">
                                    Canadian Address
                                </li>
                                <li>S3 Stores, Inc.</li>
                                <li>27 Joseph St.</li>
                                <li>Chatham, Ontario N7L3G4</li>
                                <li>Canada</li>
                            </ul>

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
                    {*<a href="https://www.instagram.com/s3stores/" target="_blank" ""class="instagram"></a>*}
                    <a href="https://plus.google.com/118379608603424325840" target="_blank" rel="nofollow" class="googleplus"></a>
                    {*<a href="https://www.bbb.org/western-ontario/business-reviews/online-retailer/s3-stores-in-chatham-on-1054268" target="_blank" class="bbb"></a>*}
                </div>

                <ul class="no-bullet menu-list">
                    <li class="title-menu">
                        Join our newsletter
                    </li>

                    <li>
                        <form class="email-subscription" action="{url 'subscribe:send_message'}">
                            <input type="email" name="subscribe[email]" required placeholder="Your Email Address" value="">
                            <button class="waves waves-dark">
                                Send
                            </button>
                        </form>
                    </li>
                </ul>

            </div>
        </div>

        <div class="row show-for-medium">
            <div class="column small-12">
                <div class="confirmations">
                    <div class="row">

                        <div class="column small-12 medium-7 large-7">

                            <ul class="no-bullet menu-list">
                                <li class="title-menu">
                                    Payment Methods
                                </li>
                                <li class="payment-methods">
                                    <img src="" class="lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/icons/p_methods/visa.png" alt="Visa icon">
                                    <img src="" class="lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/icons/p_methods/mastercard.png" alt="MasterCard icon">
                                    <img src="" class="lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/icons/p_methods/amex.png" alt="AmericanExpress icon">
                                    <img src="" class="lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/icons/p_methods/discover_network.png" alt="Discover Network icon">
                                    <img src="" class="lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/icons/p_methods/visadebit.png" alt="Visa Debit icon">
                                    <img src="" class="lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/icons/p_methods/paypal.png" alt="PayPal icon">
                                    <img src="" class="lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/icons/p_methods/echeck.png" alt="eCheck icon">
                                    <img src="" class="lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/icons/p_methods/check.png" alt="Check icon">
                                    <img src="" class="lazy-img" data-src="{$site->getHttpOrHttps() ~ $config.CDN_domain}/static/frontend/dist/images/icons/p_methods/po.png" alt="Purchase Order request icon">
                                </li>
                                <li class="fraud-orders">
                                    <a href="/ecomerce-fraud">Combating eCommerce Fraud</a>
                                    <a href="/purchase-orders">Purchase Orders</a>
                                </li>
                            </ul>

                        </div>

                        <div class="column small-12 medium-5 large-5 ">

                            <ul class="no-bullet menu-list">
                                <li class="title-menu">
                                    Shop with Confidence
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
                Copyright © 2005-{date_add()|date:"Y"} S3 Stores Holdings, Inc. All Rights Reserved.
            </div>
        </div>
    </div>
</footer>