{extends "checkout/base.tpl"}

{block 'js'}
    <script>
        var bill_addres_el = document.querySelector(".billing_address_form");
        var elements = bill_addres_el.querySelectorAll("input.required, select.required");

        let form = document.querySelector('.checkout-options-form');

        function hideForm() {
            bill_addres_el.classList.add("hide");
            form.setAttribute('data-validate', 'false');
            removeRequired();
        }

        function showForm() {
            bill_addres_el.classList.remove("hide");
            form.setAttribute('data-validate', 'true');
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
    {*<form data-abide action="{url 'checkout:options'}" method="POST" class="checkout-options-form">*}
        {raw $billingForm->renderBegin([
            'action' => $.app->router->url('checkout:options'),
            'method' => 'POST',
            'class' => 'checkout-options-form',
            'validate' => 'false'
        ])}
        <section class="checkout-options">
            <div class="row">
                <div class="columns large-4 show-for-large">
                    <div class="options">
                        {set $lbl}{t 'Shipping Address'}{/set}
                        {include 'checkout/_address_view.tpl' info=$shipping_address header=$lbl uri='checkout:shipping'}
                    </div>
                </div>
                <div class="columns small-12 large-8">
                    <div class="row">
                        <div class="columns small-12">
                            <h2 class="default-form-header">{t 'Delivery Methods' }</h2>
                        </div>
                    </div>
                    {foreach $.app->cart->getItemsGroupedBy() as $gi => $group}
                        {set $warehouse = $.get_warehouse($gi)}
                        {set $shipping = $shipping_rates[$gi]}
                        {set $order_group = $order->groups->get(['manufacturerid' => $gi])}

                        <div class="product-group-shipping">
                            <div class="row">
                                <div class="columns small-12">

                                    <h3 class="shipped-from">
                                        {t 'Delivery methods for' }
                                        <a class="dashed" data-toggle="product-group-{$gi}">
                                            <span>{t 'the items' }</span>
                                        </a>
                                        {t 'shipped from warehouse in' } {$warehouse->m_city},
                                        {if $config.show_full_state_country}{$warehouse->state_model}{else}{$warehouse->m_state}{/if},
                                        {if $config.show_full_state_country}{$warehouse->country_model->countryNameBySite()}{else}{$warehouse->m_country}{/if}
                                    </h3>

                                    {include 'checkout/_product_group_list.tpl' items=$group.items gi=$gi}
                                </div>
                            </div>

                            {if $shipping}
                                <div class="row">
                                    <div class="columns small-12">
                                        <div class="shipping-methods methods-table">
                                            {foreach $shipping as $quote first=$first}
                                                {set $shipping_model = $quote->shipping}
                                                {if $shipping_model}
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
                                                            <span class="cost">{$site_currency->symbol_prefix}{$site_currency} {$site_currency->getCurrencyFormat($quote->getShippingCharge())}</span>
                                                        </div>

                                                    </div>
                                                    <div class="methods-row-space"></div>
                                                {/if}
                                            {/foreach}
                                        </div>
                                    </div>
                                </div>
                            {else}
                                {add $phone_order_only = true}
                                <div class="row">
                                    <div class="columns small-12">
                                        <div class="no-quotes">
                                            {t "Our shipping server couldn't provide us with an accurate shipping quote. This sometimes occurs"}<br/>
                                            {t '- when the product is oversized or somehow irregular in shape or weight'}<br/>
                                            {t '- for overseas shipments'}<br/>
                                            <br/>
                                            <b>{t 'Please go ahead and place your order.'}</b><br/>
                                            {t 'We will determine an accurate shipping charge manually and send you an updated invoice.'}<br/>
                                            {t "At this point we won't collect your payment information."}
                                        </div>
                                    </div>
                                </div>
                            {/if}
                        </div>
                    {/foreach}
                    {if $order->isCanadianShipping()}
                        <div class="row">
                            <div class="columns small-12">
                                <div class="non-us-disclaimer checkbox-container">
                                    <input id="non-us-disclaimer-checkbox" type="checkbox" {if $order->non_us_confirmation}checked{/if} value="Y" name="non_us_confirmation"/>
                                    <label for="non-us-disclaimer-checkbox">
                                        {t 'By checking this box I agree to be responsible for custom duties, CODs, and other charges associated with bringing goods to Canada. All prices are in '} {$site_currency->currency_code}.
                                    </label>
                                </div>
                            </div>
                        </div>
                    {/if}
                </div>
            </div>

            <div class="row show-for-large">
                <div class="small-12 columns">
                    <div class="hr"></div>
                </div>
            </div>

            <div class="row payment-methods-container">
                <div class="columns large-4 show-for-large"></div>
                <div class="columns large-8">
                    <div class="row">
                        <div class="columns small-12">
                            <h2 class="default-form-header">{t 'Payment Methods' }</h2>
                        </div>
                    </div>
                    {if $payment_methods}
                        <div class="payment-methods radio-list-table">
                            {foreach $payment_methods as $method first=$first}
                                {if in_array($site->code, ['RD', 'AT']) || !$phone_order_only || ($phone_order_only && $method->payment_method === 'Phone Order')}
                                    <div class="table-row {cycle ["odd", ""]}">
                                        <div class="table-cell payment-method">
                                            <input {if $first || $phone_order_only || ($method->paymentid == $order->paymentid)}checked{/if}
                                                   id="payment_{$method->paymentid}" type="radio" name="payment_method" value="{$method->paymentid}"/>
                                            <label for="payment_{$method->paymentid}">
                                                <span class="name">{$method->payment_method}</span>
                                            </label>
                                        </div>
                                        <div class="table-cell payment-description">
                                            <span class="details">{$method->payment_details}</span>
                                        </div>
                                    </div>
                                {/if}
                            {/foreach}
                        </div>
                    {/if}
                </div>
            </div>
            {*<div class="row show-for-large">*}
                {*<div class="small-12 columns">*}
                    {*<div class="hr"></div>*}
                {*</div>*}
            {*</div>*}
            <div class="row">
                <div class="columns large-4 show-for-large"></div>
                <div id="billing-address" class="columns large-8 billing_same_shipping">
                    {set $billing_diff = $.app->request->post->get('billing_same') === '0' || $order->isBillingAddressDiff() || $.app->request->get->get('modify')}
                    <div class="row">
                        <div class="columns small-12">
                            <h2 class="default-form-header">{t 'Is Billing Address the same as Shipping Address?' }</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12">
                            <input id="biiling_yes" type="radio" onclick="hideForm()" {if !$billing_diff}checked{/if} name="billing_same" value="1"/>
                            <label for="biiling_yes">
                                {t 'Yes' }
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12">
                            <input id="biiling_no" type="radio" onclick="showForm()" {if $billing_diff}checked{/if} name="billing_same" value="0"/>
                            <label for="biiling_no">
                                {t 'No' }
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="columns large-4 show-for-large"></div>
                <div class="columns small-12 large-8 billing_address_form {if !$billing_diff && !$billingForm->hasErrors()}hide{/if}">
                    <div class="billing_address_form_container">
                        {raw $billingForm->render()}
                    </div>
                </div>
                {*<div class="columns large-2 show-for-large"></div>*}
            </div>

            <div class="row billing-form-submit">
                <div class="columns large-4 show-for-large"></div>
                <div class="columns small-12 large-8">
                    <div class="row">
                        <div class="column small-12 align-center">
                            <div class="buttons">
                                <button type="submit" class="button submit yellow waves waves-orange waves-effect">
                                    {t 'Continue' }
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column small-12 align-center">
                            <div class="submit-notes hint">
                                {t 'Continue to the order review page' }
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    {raw $billingForm->renderEnd()}
{/block}