<footer itemscope itemtype="http://schema.org/WPFooter">
    {insert "_parts/_bottom_menu.tpl"}
    {set $addresses = $site.addresses->all()}
    <div class="footer-content">
        <div class="container">
            <div class="row contacts-presentations">
                <div class="col-12 col-md-8 border-right-desktop">
                    <div class="present-icons">
                        <div class="row">
                            {block 'footer-logo'}
                                <div class="col-lg-4 col-md-6 col-12">
                                    <img alt="S3 Stores, Inc." class="footer-logo lazy-img"
                                         data-src="{assets 'images/logos/s3stores_footer.svg'}">
                                </div>
                            {/block}

                            <div class="col-lg-4 col-md-6 show-for-medium footer-schedule-column">
                                {block 'online-orders'}
                                    <div class="footer-schedule">
                                        <img alt="{t 'Web order 24/7'}" class="footer-schedule-icon lazy-img"
                                             data-src="{assets 'images/icons/footer/web_order.svg'}">
                                        <div class="content">
                                            <div class="footer-schedule-title">{t 'Web Orders'}</div>
                                            <div class="footer-schedule-content">{t '24 hours a day'}</div>
                                            <div class="footer-schedule-content">{t '7 days a week'}</div>
                                        </div>
                                    </div>
                                {/block}
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
                            {block 'support'}
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div>
                                        <div class="footer-info-block-title">{t 'Telephone Customer Service'}</div>
                                        <ul class="no-bullet menu-list">
                                            <li class="footer-info-block-item">{$site->customer_service_working_time}</li>
                                            <li class="footer-info-block-item">
                                                {if $site->cidev_top_header_code}
                                                    {t 'Toll Free: '}
                                                    <span class="footer-phone">{$site->cidev_top_header_code}</span>
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
                            {/block}

                            <div class="col-md-6 col-12 hide-for-large footer-email-support-column">
                                <ul class="list-unstyled menu-list email-support m-0">
                                    <li class="footer-info-block-title">
                                        {t 'Email Support'}
                                    </li>
                                    <li><a class="footer-link" href="/contactus/">{t 'Contact Us'}</a></li>
                                </ul>
                            </div>
                            {block 'addresses'}
                                {foreach $addresses as $address_model }
                                    <div class="col-4 show-for-large">
                                        <div>
                                            <div class="footer-info-block-title">{$address_model.address.name}</div>
                                            <ul class="no-bullet menu-list">
                                                <li class="footer-info-block-item">{$address_model.address.company}</li>
                                                <li class="footer-info-block-item">{$address_model.address.address}</li>
                                                <li class="footer-info-block-item">{$address_model.address.address_state}</li>
                                                <li class="footer-info-block-item">{$address_model.address.country}</li>
                                            </ul>
                                        </div>
                                    </div>
                                {/foreach}
                            {/block}
                        </div>
                    </div>

                </div>

                <div class="col-12 col-md-4 footer-right-column">
                    {block 'socials'}
                        <div class="footer-socials show-for-medium footer_socials">
                            {set $socials = $site.socials->filter(['is_active' => true])->order(['order_by'])->all()}
                            {foreach $socials as $social_model }
                                <a href="{$social_model->social->url}" rel="nofollow noopener" target="_blank"
                                   style="background-image: url({$social_model->social->getLogoPath()})"
                                   class="facebook"></a>
                            {/foreach}
                        </div>
                    {/block}
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 col-12 border-right-desktop pe-md-0 pe-lg-12">
                    <div class="confirmations footer-border-top">
                        <div class="row">
                            <div class="col-lg-8 col-md-6 col-12">
                                <div class="footer-info-block-title">{t 'Payment Methods'}</div>

                                {set $payment_methods = $site.payment_methods->asArray()->filter(['is_active' => 1])->order(['position'])->all()}
                                <div class="footer-payment-methods-container" data-payment-methods='{json_encode($payment_methods)}'></div>

                                {if !in_array($site->code, ['RD'])}<a class="footer-link"
                                                                      href="/ecomerce-fraud">{t 'Combating eCommerce Fraud'}</a>{/if}
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 flex-container align-bottom footer-purchase-order-column footer_purchase-order-column">
                                {if $po}
                                    <div>
                                        <a href="/purchase-orders">
                                            <ul class="footer-payment-methods footer_payment-methods no-bullet menu-list">
                                                <li class="footer-payment-method-item footer-payment-method_item">
                                                    <img width="54" height="36" class="lazy-img footer-payment-method-image"
                                                         data-src="/{$po->logo}" alt="{$po->name}">
                                                </li>
                                            </ul>
                                        </a>
                                        <a class="purchase-order footer-link"
                                           href="/purchase-orders">{t 'Purchase Orders'}</a>
                                    </div>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
                {if !in_array($site->code, ['RD'])}
                    <div class="col-md-4 col-12 footer-confidence">
                        <div class="no-bullet menu-list shop-with-confidence footer-right-column">
                            <div class="footer-info-block-title">
                                {t 'Shop with Confidence'}
                            </div>
                            <div class="confidence">
                            <span id="bbb">
                                {ignore}
                                    <a rel="nofollow noopener" target="_blank" id="bbblink" class="rbhzbul"
                                       href="https://www.bbb.org/ca/on/chatham/profile/furniture-stores/s3-stores-inc-0187-1054268#bbbseal"
                                       title="S3 Stores, Inc., Furniture Stores, Chatham, ON"
                                       style="display: none;position: relative;overflow: hidden; width: 200px; height: 76px; margin: 0px; padding: 0px;"><img
                                                style="padding: 0px; border: none;" id="bbblinkimg"
                                                src="https://seal-london.bbb.org/logo/rbhzbul/s3-stores-1054268.png"
                                                width="400" height="76"
                                                alt="S3 Stores, Inc., Furniture Stores, Chatham, ON"/></a>
                                    <script type="text/javascript">var bbbprotocol = (("https:" == document.location.protocol) ? "https://" : "http://");
                                        (function (){var s=document.createElement('script');s.src=bbbprotocol + 'seal-london.bbb.org' + unescape('%2Flogo%2Fs3-stores-1054268.js');s.type='text/javascript';s.async=true;var st=document.getElementsByTagName('script');st=st[st.length-1];var pt=st.parentNode;pt.insertBefore(s,pt.nextSibling);})();</script>
                                {/ignore}
                            </span>

                                <span id="g_review"></span>

                                <span id="siteseal"></span>
                            </div>
                        </div>
                    </div>
                {/if}
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
                {block 'mobile-socials'}
                    <div class="footer-socials footer_socials hide-for-medium">
                        {foreach $socials as $social_model }
                            <a href="{$social_model->social->url}" rel="nofollow noopener"
                               target="_blank" class="youtube"
                               style="background-image: url({$social_model->social->getLogoPath()})"></a>
                        {/foreach}
                    </div>
                {/block}
            </div>
        </div>
        </div>
    </div>


    <div class="footer-menu">
        <div class="container">
            <div class="row">
            <div class="col-12 col-md-8 copyright small-order-2 medium-order-1">
                {switch $site->lang->lang_code}
                    {case 'ru'}
                        {$site.start_year}-{time()|date_format:'%Y'} {$site.corporation.name}.
                        {$site.corporation.federal_tax_id_name}:{$site.corporation.federal_tax_id}, ОГРН: {$site.corporation.registration_number}. {t 'All Rights Reserved.'}
                    {case 'en'}
                        {t 'Copyright ©'} {$site.start_year}
                            -{time()|date_format:'%Y'} {$gConfig.holding_company_name} {t 'All Rights Reserved.'}
                {/switch}
            </div>
            <div class="col-12 col-md-4 footer-right-column small-order-1 medium-order-2 footer-copyright-links-column">
                <ul class="no-bullet">
                    {get_menu code='Footer'}
                </ul>
            </div>
        </div>
        </div>
    </div>
</footer>