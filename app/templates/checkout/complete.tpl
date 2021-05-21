{extends "checkout/base.tpl"}

{block "breadcrumbs"}
    {set $breadcrumbs = $.getCartBreadcrumbs}
    {if $breadcrumbs}
        {if $checkoutType === 'new'}
            <div class="row cart-steps-container show-for-large">
                {if !$breadcrumbs->isFirstStage()}
                    <a class="columns shrink cart-steps-back hide-for-large"
                       href="{$breadcrumbs->getPrevStage().url}">
                            <span class="img">
                                <img src="{$uri}/static/frontend/dist/images/icons/cart/arrow_left_shop_more.svg" alt="">
                            </span>
                        <span class="text">{t 'BACK'}</span>
                    </a>
                {/if}
                <section class="padding-0 overflow-hidden columns">
                    <ul class="checkout-steps-list no-bullet">
                        <li class="checkout-step checkout-step_one-page checkout-step_inactive show-for-medium">
                            <span class="checkout-step-link checkout-step-link_active">
                                <span class="step-label">Shopping cart</span>
                            </span>
                            <div class="checkout-arrow-right checkout-arrow-right_active"></div>
                        </li>
                        <li class="checkout-step checkout-step_one-page checkout-step_inactive">
                            <span class="checkout-step-link checkout-step-link_active">
                                <span class="step-label">Checkout</span>
                            </span>
                        </li>
                    </ul>
                </section>

            </div>
        {else}
            <div class="row cart-steps-container show-for-large order-confirmation-breadcrumbs">
                <section class="cart-steps-section columns">
                    <ul class="cart-steps-items no-bullet">
                        {foreach $breadcrumbs as $key => $item}
                            <li class="cart-step inactive">
                                <span class="step-link">
                                    <span class="step-number">{$key+1}</span>
                                    <span class="step-label">{$item['label']}</span>
                                </span>
                                <div class="arrow-right"></div>
                            </li>
                        {/foreach}
                    </ul>
                </section>
            </div>
        {/if}
    {/if}
    <div class="row cart-steps-container hide-for-large">
        <section class="cart-steps-section columns">
            <ul class="cart-steps-items no-bullet">
                <li class="cart-step active">
                        <span class="step-link">
                            <span class="step-label">{t 'Order confirmation'}</span>
                        </span>
                    <div class="arrow-right"></div>
                </li>
            </ul>
        </section>
    </div>
{/block}

{block 'content'}
    <div class="order-confirmation-container">

        {set $extra = $order->extra_model}

        <section class="order-confirmation">
            <div class="row align-center">
                <div class="column text-align--center">
                    <div class="green-border">
                        <div class="title show-for-large">{t 'Order Confirmation' }</div>
                        <div>{t 'Your order has been successfuly placed. An order confirmation email has been sent to your email address.' }</div>
                        <div>{t 'Thank you for shopping with S3 Stores, Inc. We appreciate your business!' }</div>
                    </div>
                </div>
            </div>
        </section>
        <section class="buttons-top show-for-large">
            {include 'checkout/_order_buttons.tpl'}
        </section>
        <section class="order-info">
            <div class="row align-center">
                <div class="column text-align--center">
                    <div class="title">
                        {t 'Order #' } {$order->getOrderNumber()}
                    </div>
                    {if $order->payment_method == 'Purchase Order'}
                        <div class="purchase-order-title hide-for-medium">
                            {t 'Purchase order #' } {$extra->purchase_order['po_number']}
                        </div>
                    {/if}
                </div>
            </div>

            <div class="row">
                <div class="column small-12 line-wrapper">
                    <div class="row shop-info align-spaced line">
                        <div class="columns small-12 ml-6 large-4">
                            <div class="row">
                                <div class="column small-5 medium-4 ml-5 large-6">
                                    <img src="/static/frontend/dist/images/logos/s3stores_logo.svg"
                                         alt="{t 'S3 Stores, Inc.' }" class="logo-big">
                                    <div class="place-for-witter"></div>
                                </div>
                                <div class="column">
                                    <div class="text-item group-items-title">
                                        {t 'S3 Stores, Inc.' }
                                    </div>
                                    <div class="text-item">
                                        {t '27 Joseph St.' }
                                    </div>
                                    <div class="text-item">
                                        {t 'Chatham, Ontario, N7L 3G5' }
                                    </div>
                                    <div class="text-item">
                                        {t 'Canada' }
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="columns small-12 ml-6 large-4 contact-info">
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Toll Free' }:</div>
                                <div class="column">{$config.cidev_top_header_code}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Local Phone' }:</div>
                                <div class="column">{$config.local_phone}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Fax' }:</div>
                                <div class="column">{$config.fax_number}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Email' }:</div>
                                <div class="column">{t 'orders@s3stores.com' }</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row ordering-info-content">
                <div class="column small-12 line-wrapper">
                    <div class="row ordering-info align-spaced line line-1">
                        <div class="columns small-12 ml-6 large-4">
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Date' }:</div>
                                <div class="column">{$order->date|date_format:'%d-%b-%Y'}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Order status' }:</div>
                                <div class="column">{t 'please see below' }</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Payment method' }:</div>
                                <div class="column">{$order->payment_method}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Delivery methods' }:</div>
                                <div class="column">
                                    {foreach $order->groups as $group}
                                        {set $s_model = $group->shippingModel}
                                        {if $s_model}
                                        <div class="delivery-method">
                                            {$s_model->getFrontendName()}
                                        </div>
                                        {/if}
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                        <div class="columns small-12 ml-6 large-4">
                            <div class="row text-item">
                                <div class="column small-12 label group-items-title">{t 'Contact information' }</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Full name' }:</div>
                                <div class="column">{$order->firstname}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Phone' }:</div>
                                <div class="column">{$order->phone}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Email' }:</div>
                                <div class="column">{$order->email}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row ordering-info align-spaced line line-2">
                        <div class="columns small-12 ml-6 large-4 contact-info">
                            <div class="row text-item">
                                <div class="column small-12 label group-items-title">{t 'Shipping address' }</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Full name' }:</div>
                                <div class="column">{$shipping_info['firstname']}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Company' }:</div>
                                <div class="column">{$shipping_info['company']}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Address' }:</div>
                                <div class="column">{$shipping_info['address'][0]} {if $shipping_info['address'][1]}
                                        <br/>
                                        {$shipping_info['address'][1]}{/if}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'City' }:</div>
                                <div class="column">{$shipping_info['city']}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'State/Province' }:</div>
                                <div class="column">{$shipping_info['state']}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Country' }:</div>
                                <div class="column">{$shipping_info['country']}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Zip/Postal code' }:</div>
                                <div class="column">{$shipping_info['zipcode']}</div>
                            </div>
                        </div>
                        <div class="columns small-12 ml-6 large-4 contact-info">
                            <div class="row text-item">
                                <div class="column small-12 label group-items-title">{t 'Billing address' }</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Full name' }:</div>
                                <div class="column">{$billing_info['firstname']}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Company' }:</div>
                                <div class="column">{$billing_info['company']}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Address' }:</div>
                                <div class="column">{$billing_info['address'][0]} {if $billing_info['address'][1]}
                                        <br/>
                                        {$billing_info['address'][1]}{/if}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'City' }:</div>
                                <div class="column">{$billing_info['city']}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'State/Province' }:</div>
                                <div class="column">{$billing_info['state']}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Country' }:</div>
                                <div class="column">{$billing_info['country']}</div>
                            </div>
                            <div class="row text-item">
                                <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Zip/Postal code' }:</div>
                                <div class="column">{$billing_info['zipcode']}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {if $order->payment_method == 'Purchase Order'}
                <div class="row">
                    <div class="column small-12 line-wrapper">


                        <div class="row ordering-info align-spaced line-3 line">
                            <div class="columns small-12 ml-6 large-4 contact-info">
                                <div class="row text-item">
                                    <div class="column small-12 label group-items-title">{t 'Purchase order information' }</div>
                                </div>
                                <div class="row text-item">
                                    <div class="column small-5 medium-4 ml-5 large-6 label">{t 'PO number' }:</div>
                                    <div class="column">{$extra->purchase_order['po_number']}</div>
                                </div>
                                <div class="row text-item">
                                    <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Company name' }:</div>
                                    <div class="column">{$extra->purchase_order['company_name']}</div>
                                </div>
                            </div>
                            <div class="columns small-12 ml-6 large-4"></div>
                        </div>

                        <div class="row ordering-info align-spaced line-3 line">
                            <div class="columns small-12 ml-6 large-4 contact-info">
                                <div class="row text-item">
                                    <div class="column small-12 label group-items-title">{t 'Purchase manager' }</div>
                                </div>
                                <div class="row text-item">
                                    <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Full name' }:</div>
                                    <div class="column">{$extra->purchase_order['name_of_purchaser']}</div>
                                </div>
                                <div class="row text-item">
                                    <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Phone' }:</div>
                                    <div class="column">{$extra->purchase_order['purchase_manager_phone']}</div>
                                </div>
                                <div class="row text-item">
                                    <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Fax' }:</div>
                                    <div class="column">{$extra->purchase_order['purchase_manager_fax']}</div>
                                </div>
                                <div class="row text-item">
                                    <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Email' }:</div>
                                    <div class="column">{$extra->purchase_order['purchase_manager_email']}</div>
                                </div>
                            </div>
                            <div class="columns small-12 ml-6 large-4 contact-info">
                                <div class="row text-item">
                                    <div class="column small-12 label group-items-title">{t 'Accounts payable' }</div>
                                </div>
                                <div class="row text-item">
                                    <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Full name' }:</div>
                                    <div class="column">{$extra->purchase_order['accounts_payable_full_name']}</div>
                                </div>
                                <div class="row text-item">
                                    <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Phone' }:</div>
                                    <div class="column">{$extra->purchase_order['accounts_payable_phone']}</div>
                                </div>
                                <div class="row text-item">
                                    <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Fax' }:</div>
                                    <div class="column">{$extra->purchase_order['purchase_manager_fax']}</div>
                                </div>
                                <div class="row text-item">
                                    <div class="column small-5 medium-4 ml-5 large-6 label">{t 'Email' }:</div>
                                    <div class="column">{$extra->purchase_order['accounts_payable_email']}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            {/if}
            {if $order->non_us_confirmation}
                <div class="row">
                    <div class="column small-12">
                        <div class="non-us-disclaimer">
                            <label>
                                {*<input type="checkbox" checked value="Y" name="non_us_confirmation" required />*}
                                {t 'I agree to be responsible for custom duties, CODs, and other charges associated with bringing goods to Canada.'}
                            </label>
                        </div>
                    </div>
                </div>
            {/if}


        </section>

        <section class="order-products">
            {include 'checkout/_complete_order_review.tpl' order_groups = $order->groups}
        </section>
        <section class="buttons-bottom">
            {include 'checkout/_order_buttons.tpl'}
        </section>
    </div>
{/block}

{block 'js'}

    <!-- Bing Code for Conversion Tracking: Order Conversion Page -->
    <noscript><img src="//bat.bing.com/action/0?ti=5024901&Ver=2" height="0" width="0"
                   style="display:none; visibility: hidden;"/></noscript>
    <script>
        {ignore}
        (function (w, d, t, r, u) {
            var f, n, i;
            w[u] = w[u] || [], f = function () {
                var o = {ti: "5024901"};
                o.q = w[u], w[u] = new UET(o), w[u].push("pageLoad")
            }, n = d.createElement(t), n.src = r, n.async = 1, n.onload = n.onreadystatechange = function () {
                var s = this.readyState;
                s && s !== "loaded" && s !== "complete" || (f(), n.onload = n.onreadystatechange = null)
            }, i = d.getElementsByTagName(t)[0], i.parentNode.insertBefore(n, i)
        })(window, document, "script", "//bat.bing.com/bat.js", "uetq");
        {/ignore}
        var revenue = {$order->total};
        {ignore}
        window.uetq = window.uetq || [];
        window.uetq.push({'gv': revenue});
        {/ignore}
    </script>
    <!-- Bing Code for Conversion Tracking: Order Conversion Page -->
{/block}

{block 'head'}
    <script>
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ ecommerce: null });
        window.dataLayer.push({
            'ecommerce': {
                'purchase': {
                    'actionField': {
                        'id': '{$order->getOrderNumber()}', // Transaction ID. Required for purchases and refunds.
                        'affiliation': '{$.getSite->domain}',
                        'revenue': '{$order->total|number_format:2:'.':''}', // Total transaction value (incl. tax and shipping)
                        'tax':0,
                        'shipping': '{$order->shipping_cost|number_format:2:'.':''}',
                    },
                    'products': [
                        {foreach $order->detail_models as $detail}
                        {set $product = $detail->product_model}
                        {set $category = $product->getMainCategory()}
                        {if $product}
                        {
                            'id': '{$product->productid}',
                            'name': "{$product->getFrontendName()|escape}",
                            'sku': '{$product->productcode}',
                            'brand': "{$product->brand->brand|escape}",
                            'category': "{$category->category|escape}",
                            'price': '{$detail->price|number_format:2:'.':''}',
                            'quantity': '{$detail->amount}'
                        },
                        {/if}
                        {/foreach}
                    ]
                }
            }
        });
    </script>
    {parent}
{/block}