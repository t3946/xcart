{extends "checkout/base.tpl"}

{block "breadcrumbs"}
    {set $breadcrumbs = $.getCartBreadcrumbs}
    {if $breadcrumbs}
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
<div class="order-confirmation-container"">

    {set $extra = $order->extra_model}

    <section class="order-confirmation">
        <div class="row align-center">
            <div class="column text-align--center">
                <div class="green-border">
                    <div class="title show-for-large">{t 'Order Confirmation' dict='order'}</div>
                    <div>{t 'Your order has been successfuly placed. An order confirmation email has been sent to your email address.' dict='order'}</div>
                    <div>{t 'Thank you for shopping with S3 Stores, Inc. We appreciate your business!' dict='order'}</div>
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
                <div class="title">{t 'Order #' dict='order'} {$order->getOrderNumber()}</div>
                {*{if $order->payment_method->payment_method == 'Purchase Order'}*}
                    <div class="purchase-order-title hide-for-medium">
                        {*{t 'Purchase order #' dict='order'} {$extra->purchase_order['po_number']}*}
                        {t 'Purchase order #' dict='order'} 2322423
                    </div>
                {*{/if}*}
            </div>
        </div>
        <div class="row shop-info">
            <div class="columns small-5 medium-4">
                <img src="/static/frontend/dist/images/logos/s3stores_logo.svg" alt="{t 'S3 Stores, Inc.' dict='order'}" class="logo-big">
                <div class="place-for-witter"></div>
            </div>
            <div class="columns small-7 medium-8">
                <div class="row text-item">
                    <div class="column shop-name">{t 'S3 Stores, Inc.' dict='order'}</div>
                </div>
                <div class="row text-item">
                    <div class="column">{t '27 Joseph St.' dict='order'}</div>
                </div>
                <div class="row text-item">
                    <div class="column">{t 'Chatham, Ontario, N7L 3G4' dict='order'}</div>
                </div>
                <div class="row text-item">
                    <div class="column">{t 'Canada' dict='order'}</div>
                </div>
            </div>

            <div class="columns small-12 contact-info">
                <div class="row text-item">
                    <div class="column small-5 label">{t 'Toll free:' dict='order'}</div>
                    <div class="column">{t '1-800-929-2431' dict='order'}</div>
                </div>
                <div class="row text-item">
                    <div class="column small-5 label">{t 'Local phone:' dict='order'}</div>
                    <div class="column">{t '(616) 259-5711' dict='order'}</div>
                </div>
                <div class="row text-item">
                    <div class="column small-5 label">{t 'Fax:' dict='order'}</div>
                    <div class="column">{t '(813) 944-4516' dict='order'}</div>
                </div>
                <div class="row text-item">
                    <div class="column small-5 label">{t 'Email:' dict='order'}</div>
                    <div class="column">{t 'orders@s3stores.com' dict='order'}</div>
                </div>
            </div>
        </div>

        <div class="ordering-info">
        <div class="row show-for-large">
            <div class="small-12 columns">
                <div class="hr"></div>
            </div>
        </div>
        <div class="row">
            <div class="columns small-3">{t 'Date:' dict='order'}</div>
            <div class="columns small-3">{$order->date|date_format:'%d-%b-%Y'}</div>
            <div class="columns small-3">{t 'Contact information' dict='order'}</div>
            <div class="columns small-3"></div>
        </div>
        <div class="row">
            <div class="columns small-3">{t 'Order status:' dict='order'}</div>
            <div class="columns small-3">{t 'please see below' dict='order'}</div>
            <div class="columns small-3">{t 'Full name:' dict='order'}</div>
            <div class="columns small-3">{$order->firstname}
            </div>
        </div>
        <div class="row">
            <div class="columns small-3">{t 'Payment method:' dict='order'}</div>
            <div class="columns small-3">{$order->payment_method->payment_method}</div>
            <div class="columns small-3">{t 'Phone:' dict='order'}</div>
            <div class="columns small-3">{$order->phone}</div>
        </div>
        <div class="row">
            <div class="columns small-3">{t 'Delivery methods:' dict='order'}</div>
            <div class="columns small-3">
                {foreach $order->groups as $group}
                    <div class="delivery-method">
                        {$group->shippingModel->getFrontendName()}
                    </div>
                {/foreach}
            </div>
            <div class="columns small-3">{t 'Email:' dict='order'}</div>
            <div class="columns small-3">{$order->email}</div>
        </div>

        <div class="row show-for-large">
            <div class="small-12 columns">
                <div class="hr"></div>
            </div>
        </div>
        </div>


        <div class="row">
            <div class="columns small-6">{t 'Shipping address' dict='order'}</div>
            <div class="columns small-6">{t 'Billing address' dict='order'}</div>
        </div>
        <div class="row">
            <div class="columns small-3">{t 'Full name:' dict='order'}</div>
            <div class="columns small-3">{$shipping_info['firstname']}</div>
            <div class="columns small-3">{t 'Full name:' dict='order'}</div>
            <div class="columns small-3">{$billing_info['firstname']}</div>
        </div>
        <div class="row">
            <div class="columns small-3">{t 'Company:' dict='order'}</div>
            <div class="columns small-3">{$shipping_info['company']}</div>
            <div class="columns small-3">{t 'Company:' dict='order'}</div>
            <div class="columns small-3">{$billing_info['company']}</div>
        </div>
        <div class="row">
            <div class="columns small-3">{t 'Address:' dict='order'}</div>
            <div class="columns small-3">{$shipping_info['address'][0]} {if $shipping_info['address'][1]}<br/>{$shipping_info['address'][1]}{/if}</div>
            <div class="columns small-3">{t 'Address:' dict='order'}</div>
            <div class="columns small-3">{$billing_info['address'][0]} {if $billing_info['address'][1]}<br/>{$billing_info['address'][1]}{/if}</div>
        </div>
        <div class="row">
            <div class="columns small-3">{t 'City:' dict='order'}</div>
            <div class="columns small-3">{$shipping_info['city']}</div>
            <div class="columns small-3">{t 'City:' dict='order'}</div>
            <div class="columns small-3">{$billing_info['city']}</div>
        </div>
        <div class="row">
            <div class="columns small-3">{t 'State/Province:' dict='order'}</div>
            <div class="columns small-3">{$shipping_info['state']}</div>
            <div class="columns small-3">{t 'State/Province:' dict='order'}</div>
            <div class="columns small-3">{$billing_info['state']}</div>
        </div>
        <div class="row">
            <div class="columns small-3">{t 'Country:' dict='order'}</div>
            <div class="columns small-3">{$shipping_info['country']}</div>
            <div class="columns small-3">{t 'Country:' dict='order'}</div>
            <div class="columns small-3">{$billing_info['country']}</div>
        </div>
        <div class="row">
            <div class="columns small-3">{t 'Zip/Postal code:' dict='order'}</div>
            <div class="columns small-3">{$shipping_info['zipcode']}</div>
            <div class="columns small-3">{t 'Zip/Postal code:' dict='order'}</div>
            <div class="columns small-3">{$billing_info['zipcode']}</div>
        </div>

        {if $order->payment_method->payment_method == 'Purchase Order'}
            <div class="purchase-order-info">
                <div class="row">
                    <div class="columns">{t 'Purchase order information' dict='order'}</div>
                </div>
                <div class="row">
                    <div class="columns small-3">{t 'PO number:' dict='order'}</div>
                    <div class="columns small-3">{$extra->purchase_order['po_number']}</div>
                    <div class="columns small-3"></div>
                    <div class="columns small-3"></div>
                </div>
                <div class="row">
                    <div class="columns small-3">{t 'Company name:' dict='order'}</div>
                    <div class="columns small-3">{$extra->purchase_order['company_name']}</div>
                    <div class="columns small-3"></div>
                    <div class="columns small-3"></div>
                </div>
                <div class="row">
                    <div class="columns small-6">{t 'Purchase manager' dict='order'}</div>
                    <div class="columns small-6">{t 'Accounts payable' dict='order'}</div>
                </div>
                <div class="row">
                    <div class="columns small-3">{t 'Full name:' dict='order'}</div>
                    <div class="columns small-3">{$extra->purchase_order['name_of_purchaser']}</div>
                    <div class="columns small-3">{t 'Full name:' dict='order'}</div>
                    <div class="columns small-3">{$extra->purchase_order['accounts_payable_full_name']}</div>
                </div>
                <div class="row">
                    <div class="columns small-3">{t 'Phone:' dict='order'}</div>
                    <div class="columns small-3">{$extra->purchase_order['purchase_manager_phone']}</div>
                    <div class="columns small-3">{t 'Phone:' dict='order'}</div>
                    <div class="columns small-3">{$extra->purchase_order['accounts_payable_phone']}</div>
                </div>
                <div class="row">
                    <div class="columns small-3">{t 'Fax:' dict='order'}</div>
                    <div class="columns small-3">{$extra->purchase_order['purchase_manager_fax']}</div>
                    <div class="columns small-3">{t 'Fax:' dict='order'}</div>
                    <div class="columns small-3">{$extra->purchase_order['purchase_manager_fax']}</div>
                </div>
                <div class="row">
                    <div class="columns small-3">{t 'Email:' dict='order'}</div>
                    <div class="columns small-3">{$extra->purchase_order['purchase_manager_email']}</div>
                    <div class="columns small-3">{t 'Email:' dict='order'}</div>
                    <div class="columns small-3">{$extra->purchase_order['accounts_payable_email']}</div>
                </div>
            </div>
        {/if}
    </section>
    <section class="order-products">
        <div class="row align-center">
            <div class="column text-align--center">
                <div class="title">{t 'Products Ordered' dict='order'}</div>
            </div>
        </div>
        {foreach $order->groups as $group}
            {set $warehouse = $.get_warehouse($group->manufacturerid)}
            <div class="row align-center notes">
                <div class="column text-align--center">
                    {t 'The item below will be shipped from warehouse in' dict='order'} {$warehouse->m_city}, {$warehouse->m_state}, {$warehouse->m_country}
                </div>
            </div>
            <div class="order-product-table">
                <div class="row order-table-head">
                    <div class="columns small-2 text-align--center sku">
                        {t 'SKU' dict='order'}
                    </div>
                    <div class="columns small-4 text-align--center item-name">
                        {t 'Item name' dict='order'}
                    </div>
                    <div class="columns text-align--center price">
                        {t 'Price' dict='order'}
                    </div>
                    <div class="columns text-align--center quantity">
                        {t 'Qty ordered' dict='order'}
                    </div>
                    <div class="columns extended">
                        {t 'Extended' dict='order'}
                    </div>
                </div>

                {set $items = $group->detail_models}

                {foreach $items as $item}
                    <div class="row order-table-body">
                        <div class="columns small-2 text-align--center sku">{$item->productcode}</div>

                        <div class="columns small-4 item-name">
                            {*options*}
                        </div>

                        <div class="columns text-align--center price">US$ <span class="price">{$item->price|number_format:2}</span></div>

                        <div class="columns text-align--center quantity">{$item->amount}</div>
                        <div class="columns extended">
                            {set $extended = $item->amount * $item->price}
                            US$ <span class="price">{$extended|number_format:2}</span>
                        </div>
                    </div>
                {/foreach}
            </div>
            <div class="order-group-shipping">
                <div class="row">
                    <div class="columns small-10 text-align-right">
                        {t 'Delivery from'}  {$warehouse->m_city}, {$warehouse->m_state}, {$warehouse->m_country} {t 'by'} {$group->shippingModel->getFrontendName()} :
                    </div>
                    <div class="columns small-2">
                        US$ <span class="price">{$group->shipping_gross|number_format:2}</span>
                    </div>
                </div>
            </div>
        {/foreach}
        <div class="row">
            <div class="small-12 columns">
                <div class="hr"></div>
            </div>
        </div>
        <div class="total">
            <div class="row">
                <div class="columns small-10 text-align--right">{t 'Total' dict='order'}</div>
                <div class="columns small-2">US$ <span class="price">{$order->subtotal|number_format:2}</span></div>
            </div>
            <div class="row">
                <div class="columns small-10 text-align--right">{t 'Total Shipping Cost' dict='order'}</div>
                <div class="columns small-2">US$ <span class="price">{$order->shipping_cost|number_format:2}</span></div>
            </div>
        </div>
        <div class="grand-total">
            <div class="row">
                <div class="columns small-10 text-align--right">{t 'Grand Total' dict='order'}</div>
                <div class="columns small-2">US$ <span class="price">{$order->total|number_format:2}</span></div>
            </div>
            {if $hst}
                <div class="row">
                    <div class="columns small-10 text-align--right">{t 'Including 13% HST' dict='order'}</div>
                    <div class="columns small-2">US$ <span class="price">{$order->tax|number_format:2}</span></div>
                </div>
            {/if}
        </div>
        {if $order->customer_notes}
            <div class="row">
                <div class="columns small-4 text-align--center title">{t 'Customer notes' dict='order'}</div>
                <div class="columns small-8 customer-notes">
                    {$order->customer_notes}
                </div>
            </div>
        {/if}
    </section>
    <section class="buttons-bottom">
        {include 'checkout/_order_buttons.tpl'}
    </section>
</div>
{/block}

{block 'js'}
    <!-- Google Code for Conversion Tracking: Order Conversion Page -->
    <script type="text/javascript">
        var google_conversion_id = 1072406910;
        var google_conversion_language = "en";
        var google_conversion_format = "3";
        var google_conversion_color = "ffffff";
        var google_conversion_label = "9T_YCJXjmXMQ_sKu_wM";
        var google_conversion_value = {$order->total};
        var google_conversion_order_id = {$order->orderid};
        var google_conversion_currency = "USD";
        var google_remarketing_only = false;
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
    <noscript>
        <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1072406910/?value={$order->total}&amp;currency_code=USD&amp;label=9T_YCJXjmXMQ_sKu_wM&amp;guid=ON&amp;script=0"/>
        </div>
    </noscript>
    <!-- Google Code for Conversion Tracking: Order Conversion Page -->

    <!-- Bing Code for Conversion Tracking: Order Conversion Page -->
    <noscript><img src="//bat.bing.com/action/0?ti=5024901&Ver=2" height="0" width="0" style="display:none; visibility: hidden;"/></noscript>

    <script type="text/javascript">
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