{extends "checkout/base.tpl"}

{block 'js'}
    <script type="text/javascript">
        var bill_addres_el = document.querySelector(".billing_address_form");
        var elements = bill_addres_el.querySelectorAll("input.required, select.required");

        function hideForm() {
            bill_addres_el.classList.add("hide");
            removeRequired();
        }

        function showForm() {
            bill_addres_el.classList.remove("hide");
            addRequired();
        }
        
        function setOrRemoveRequired() {
            if( (document.querySelector("input#biiling_yes")).hasAttribute("checked") ) {
                removeRequired();
            } else {
                addRequired();
            }
        }

        function addRequired() {
            elements.forEach(function (item, i, elements) {
                item.setAttribute("required", "required");
            })
        }

        function removeRequired() {
            elements.forEach(function (item, i, elements) {
                item.removeAttribute("required");
            })
        }
        
        setOrRemoveRequired();

    </script>
{/block}

{block 'content'}
    <form data-abide action="{url 'checkout:options'}" method="POST" class="checkout-options-form">
        <section class="checkout-options">
            <div class="row show-for-large">
                <div class="columns small-12">
                    <h1>{t 'Shipping & Payment Options' dict='order'}</h1>
                </div>
            </div>
            <div class="row">
                <div class="columns large-5 show-for-large">
                    <div class="options">
                        {include 'checkout/_address_view.tpl' info=$shipping_address header=$.t('Shipping Address','order') uri='checkout:shipping'}
                    </div>
                </div>
                <div class="columns small-12 large-7">
                    <div class="row">
                        <div class="columns small-12">
                            <h2>{t 'Delivery Methods' dict='order'}</h2>
                        </div>
                    </div>
                    {foreach $.app->cart->getItemsGroupedBy() as $gi => $group}
                        {set $warehouse = $.get_warehouse($gi)}
                        {set $shipping  = $.get_shipping($gi, $order, $group) }
                        {set $order_group = $order->groups->get(['manufacturerid' => $gi])}
                        <div class="row">
                            <div class="columns small-12">
                                <h3 class="shipped-from">
                                    {t 'Delivery methods for' dict='order'} {t 'the items' dict='order'} {t 'shipped from warehouse in' dict='order'} {$warehouse->m_city}, {$warehouse->m_state}, {$warehouse->m_country}
                                </h3>
                            </div>
                        </div>
                        {if $shipping}
                            <div class="row">
                                <div class="columns small-12">
                                    <div class="shipping-methods methods-table">
                                        {foreach $shipping as $quote first=$first}
                                            {set $shipping_model = $quote->shipping}
                                            <div class="methods-row">

                                                <div class="methods-cell delivery-item-label">
                                                    <input {if $first}required{/if} {if ($first) || ($order_group && $order_group->shippingid == $shipping_model->shippingid)}checked{/if}
                                                           id="shipping_{$quote->rateid}" type="radio"
                                                           name="shipping_rates[{$gi}]" value="{$quote->rateid}"/>

                                                    <label class="methods-label" for="shipping_{$quote->rateid}">
                                                        <span class="methods-text">
                                                            <span class="name">{$shipping_model->getFrontendName()}</span>
                                                            <span class="comment">{$shipping_model->shipping_time}</span>
                                                        </span>
                                                    </label>
                                                </div>

                                                <div class="methods-cell delivery-item-price">
                                                    <span class="cost">US$ {$quote->getShippingCharge()|number_format:2}</span>
                                                </div>

                                            </div>
                                            <div class="methods-row-space"></div>
                                        {/foreach}
                                    </div>
                                </div>
                            </div>

                        {else}
                            <div class="row">
                                <div class="columns small-12">
                                    <div class="no-quotes">
                                        The UPS server could not provide us with a shipping quote.
                                        When placing the order, please choose "Phone Ordering" as your payment method.
                                        We will determine an accurate shipping charge manually and send you an updated invoice.
                                    </div>
                                </div>
                            </div>
                        {/if}
                    {/foreach}
                    {*{if $order->isCanadianShipping()}*}
                        <div class="row">
                            <div class="column small-1">
                            </div>
                            <div class="column">
                                <div class="non-us-disclaimer">
                                    <label>
                                        <input type="checkbox" {if $order->non_us_confirmation}checked{/if} value="Y" name="non_us_confirmation" required />
                                        By checking this box I agree to be responsible for custom duties, CODs, and other charges associated with bringing goods to Canada. All prices are in USD.
                                    </label>
                                </div>
                            </div>
                        </div>
                    {*{/if}*}
                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <div class="hr"></div>
                </div>
            </div>

            <div class="row">
                <div class="columns large-5 show-for-large"></div>
                <div class="columns large-7">
                    <div class="row">
                        <div class="columns small-12">
                            <h2>{t 'Payment Methods' dict='order'}</h2>
                        </div>
                    </div>
                    {if $payment_methods}
                        <div class="payment-methods">
                            {foreach $payment_methods as $method first=$first}
                                <div class="row align-center-middle {cycle ["odd", ""]}">
                                    <div class="columns small-4">
                                        <input {if ($first) || ($method->paymentid == $order->paymentid)}checked{/if} id="payment_{$method->paymentid}" type="radio" name="payment_method" value="{$method->paymentid}"/>
                                        <label for="payment_{$method->paymentid}">
                                            <span class="name">{$method->payment_method}</span>
                                        </label>
                                    </div>
                                    <div class="columns small-8">
                                        <span class="details">{$method->payment_details}</span>
                                    </div>
                                </div>
                            {/foreach}
                        </div>
                    {/if}
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    <div class="hr"></div>
                </div>
            </div>
            <div class="row">
                <div class="columns large-5 show-for-large"></div>
                <div class="columns large-7">
                    {set $billing_diff = $order->isBillingAddressDiff()}
                    <div class="row">
                        <div class="columns small-12">
                            <h2>{t 'Is Billing Address the same as Shipping Address?' dict='order'}</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12">
                            <input id="biiling_yes" type="radio" onclick="hideForm()" {if !$billing_diff}checked{/if} name="billing_same" value="1"/>
                            <label for="biiling_yes">
                                {t 'Yes' dict='order'}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12">
                            <input id="biiling_no" type="radio" onclick="showForm()" {if $billing_diff}checked{/if} name="billing_same" value="0"/>
                            <label for="biiling_no">
                                {t 'No' dict='order'}
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="columns small-12 large-10 billing_address_form {if !$billing_diff}hide{/if}">

                    {include 'checkout/_form_row.tpl' field=$billingForm->getField('firstname')}
                    {include 'checkout/_form_row.tpl' field=$billingForm->getField('company')}
                    {include 'checkout/_form_row.tpl' field=$billingForm->getField('address')}
                    {include 'checkout/_form_row.tpl' field=$billingForm->getField('address_2')}
                    {include 'checkout/_form_row.tpl' field=$billingForm->getField('country')}
                    {include 'checkout/_form_row.tpl' field=$billingForm->getField('zipcode')}
                    {include 'checkout/_form_row.tpl' field=$billingForm->getField('state')}
                    {include 'checkout/_form_row.tpl' field=$billingForm->getField('city')}

                </div>
                <div class="columns large-2 show-for-large"></div>
            </div>

            <div class="row">
                <div class="column small-12 align-center text-center">
                    <div class="buttons">
                        <button type="submit" class="button submit yellow waves waves-orange waves-effect">
                            {t 'Continue' dict='order'}
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="column small-12 align-center text-center">
                    <div class="submit-notes hint">
                        {t 'Continue to the order review page' dict='order'}
                    </div>
                </div>
            </div>

        </section>
    </form>
{/block}