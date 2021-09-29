<footer itemscope itemtype="http://schema.org/WPFooter">
    {insert "_parts/_bottom_menu.tpl"}

    <div class="footer-content">
        <div class="container">
            <div class="row contacts-presentations">
                <div class="col-12 col-md-8 border-right-desktop">
                    <div class="present-icons">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-12">
                                <img alt="S3 Stores, Inc." class="footer-logo lazy-img" data-src="{assets 'images/logos/s3stores_footer.svg'}">
                            </div>

                            <div class="col-lg-4 col-md-6 show-for-medium footer-schedule-column">
                                <div class="footer-schedule">
                                    <img alt="{t 'Web order 24/7'}" class="footer-schedule-icon lazy-img" data-src="{assets 'images/icons/footer/web_order.svg'}">
                                    <div class="content">
                                        <div class="footer-schedule-title">{t 'Web Orders'}</div>
                                        <div class="footer-schedule-content">{t '24 hours a day'}</div>
                                        <div class="footer-schedule-content">{t '7 days a week'}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 show-for-large">
                                <ul class="no-bullet menu-list email-support margin-0">
                                    <li class="footer-info-block-title">
                                        {t 'Email Support'}
                                    </li>
                                    <li><a class="footer-link" href="/contactus/">{t 'Contact Us'}</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-20 mt-md-4">
                            <div class="col-lg-4 col-md-6 col-12">
                                <div>
                                    <div class="footer-info-block-title">Telephone Customer Service</div>
                                    <ul class="no-bullet menu-list">
                                        <li class="footer-info-block-item">{$site->customer_service_working_time}</li>
                                        <li class="footer-info-block-item">
                                            {if $site->cidev_top_header_code}
                                                {t 'Toll Free: '} <span class="footer-phone">{$site->cidev_top_header_code}</span>
                                            {/if}
                                        </li>
                                        <li class="footer-info-block-item">
                                            {if $site->local_phone}
                                                {t 'Tel:'} {$site->local_phone}
                                            {/if}
                                        </li>
                                        <li class="footer-info-block-item">
                                            {if $site->fax_number}
                                                {t 'Fax:'} {$site->fax_number}
                                            {/if}
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 hide-for-large footer-email-support-column">
                                <ul class="list-unstyled menu-list email-support m-0">
                                    <li class="footer-info-block-title">
                                        {t 'Email Support'}
                                    </li>
                                    <li><a class="footer-link" href="/contactus/">{t 'Contact Us'}</a></li>
                                </ul>
                            </div>

                            <div class="col-4 show-for-large">
                                <div>
                                    <div class="footer-info-block-title">USA Address</div>
                                    <ul class="no-bullet menu-list">
                                        <li class="footer-info-block-item">{t 'S3 Stores, Inc.'}</li>
                                        <li class="footer-info-block-item">{t '2885 Sanford Ave SW #12717'}</li>
                                        <li class="footer-info-block-item">{t 'Grandville, MI 49418'}</li>
                                        <li class="footer-info-block-item">{t 'USA'}</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-4 show-for-large">
                                <div>
                                    <div class="footer-info-block-title">Canadian Address</div>
                                    <ul class="no-bullet menu-list">
                                        <li class="footer-info-block-item">{t 'S3 Stores, Inc.'}</li>
                                        <li class="footer-info-block-item">{t '27 Joseph St.'}</li>
                                        <li class="footer-info-block-item">{t 'Chatham, Ontario N7L3G4'}</li>
                                        <li class="footer-info-block-item">{t 'Canada'}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-12 col-md-4 footer-right-column">
                    <div class="footer-socials show-for-medium footer_socials">
                        <a href="https://www.facebook.com/s3stores/" rel="nofollow noopener" target="_blank" class="facebook"></a>
                        <a href="https://www.pinterest.com/s3storesinc/" target="_blank" rel="nofollow noopener" class="pinterest"></a>
                        <a href="https://www.youtube.com/channel/UCjE6xR1TriWo-hCDsbpvMKg" rel="nofollow noopener" target="_blank" class="youtube"></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 col-12 border-right-desktop footer-payment-column">
                    <div class="confirmations footer-border-top">
                        <div class="row">
                            <div class="col-lg-8 col-md-6 col-12">
                                <div class="footer-info-block-title">{t 'Payment Methods'}</div>

                                <ul class="footer-payment-methods footer_payment-methods no-bullet menu-list">
                                    {set $payment_methods = $site->payment_methods->filter(['is_active' => 1])->order(['position'])->all()}
                                    {if !$payment_methods }
                                        {set $payment_methods = Modules\Sites\Models\PaymentMethodModel::active()}
                                    {/if}
                                    {foreach $payment_methods as $key => $method }
                                        {if $method->name === 'Po'}
                                            {set $po = $method}
                                        {else}
                                            <li class="footer-payment-method-item footer-payment-method_item">
                                                <img width="54" height="36" class="lazy-img footer-payment-method-image" data-src="/{$method->logo}" alt="{$method->name}">
                                            </li>
                                        {/if}
                                    {/foreach}
                                </ul>

                                <a class="footer-link" href="/ecomerce-fraud">{t 'Combating eCommerce Fraud'}</a>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 flex-container align-bottom footer-purchase-order-column footer_purchase-order-column">
                                {if $po }
                                    <div>
                                        <a href="/purchase-orders">
                                            <ul class="footer-payment-methods footer_payment-methods no-bullet menu-list">
                                                <li class="footer-payment-method-item footer-payment-method_item">
                                                    <img width="54" height="36" class="lazy-img footer-payment-method-image" data-src="/{$po->logo}" alt="{$po->name}">
                                                </li>
                                            </ul>
                                        </a>
                                        <a class="purchase-order footer-link" href="/purchase-orders">{t 'Purchase Orders'}</a>
                                    </div>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12 footer-confidence">
                    <div class="no-bullet menu-list shop-with-confidence footer-right-column">
                        <div class="footer-info-block-title">
                            {t 'Shop with Confidence'}
                        </div>
                        <div class="confidence">
                            <span id="bbb">
                                {ignore}
                                    <a rel="nofollow noopener" target="_blank" id="bbblink" class="rbhzbul" href="https://www.bbb.org/ca/on/chatham/profile/furniture-stores/s3-stores-inc-0187-1054268#bbbseal" title="S3 Stores, Inc., Furniture Stores, Chatham, ON" style="display: none;position: relative;overflow: hidden; width: 200px; height: 76px; margin: 0px; padding: 0px;"><img style="padding: 0px; border: none;" id="bbblinkimg" src="https://seal-london.bbb.org/logo/rbhzbul/s3-stores-1054268.png" width="400" height="76" alt="S3 Stores, Inc., Furniture Stores, Chatham, ON" /></a><script type="text/javascript">var bbbprotocol = ( ("https:" == document.location.protocol) ? "https://" : "http://" ); (function(){var s=document.createElement('script');s.src=bbbprotocol + 'seal-london.bbb.org' + unescape('%2Flogo%2Fs3-stores-1054268.js');s.type='text/javascript';s.async=true;var st=document.getElementsByTagName('script');st=st[st.length-1];var pt=st.parentNode;pt.insertBefore(s,pt.nextSibling);})();</script>
                            {/ignore}
                            </span>

                            <span id="g_review"></span>

                            <span id="siteseal"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
            <div class="col-12">
                {*button tot top*}
                <button class="footer-scroll-up-button footer__scroll-up-button" type="button">
                    <svg class="footer-scroll-up-icon">
                        <use xlink:href="/static/frontend/images/icons/sprite.svg#corner-white"></use>
                    </svg>
                    <span class="footer-scroll-up-title">{t 'up'}</span>
                </button>

                {*mobile socials*}
                <div class="footer-socials footer_socials hide-for-medium">
                    <a href="https://www.youtube.com/channel/UCjE6xR1TriWo-hCDsbpvMKg" rel="nofollow noopener" target="_blank" class="youtube"></a>
                    <a href="https://www.pinterest.com/s3storesinc/" target="_blank" rel="nofollow noopener" class="pinterest"></a>
                    <a href="https://www.facebook.com/s3stores/" rel="nofollow noopener" target="_blank" class="facebook"></a>
                </div>
            </div>
        </div>
        </div>
    </div>


    <div class="footer-menu">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8 copyright small-order-2 medium-order-1">
                    {t 'Copyright ©'} {$config.start_year}-{date_add()|date:"Y"} {$gConfig.holding_company_name} {t 'All Rights Reserved.'}
                </div>
                <div class="col-12 col-md-4 footer-right-column small-order-1 medium-order-2 footer-copyright-links-column">
                    <ul class="no-bullet">
                        {get_menu code='footer-menu'}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
