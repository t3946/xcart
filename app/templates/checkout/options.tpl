{extends "checkout/base.tpl"}

{block 'content'}


    <form data-abide action="{url 'checkout:options'}" method="POST" class="checkout-options-form">
        <section class="checkout-options">
            <div class="row">
                <div class="columns small-12">
                    <h1>{t 'Shipping & Payment Options' dict='order'}</h1>
                </div>
            </div>
            <div class="row">
                <div class="columns small-5">
                    <div class="options">
                        {include 'checkout/_address_view.tpl' header=$.t('Shipping Address','order') uri='checkout:shipping'}
                    </div>
                </div>
                <div class="columns small-7">
                    <div class="row">
                        <div class="columns small-12">
                            <h2>{t 'Delivery Methods' dict='order'}</h2>
                        </div>
                    </div>
                    {foreach $.app->cart->getItemsGroupedBy() as $gi => $group}
                        {set $warehouse = $.get_warehouse($gi)}
                        {set $shipping  = $.get_shipping($gi, $order, $group) }
                        <div class="row">
                            <div class="columns small-12">
                                <h3 class="shipped-from">
                                    {t 'Delivery methods for' dict='order'} <a>{t 'the items' dict='order'}</a> {t 'shipped from warehouse in' dict='order'} {$warehouse->m_city}, {$warehouse->m_state}, {$warehouse->m_country}
                                </h3>
                            </div>
                        </div>
                        {if $shipping}
                            <div class="shipping-methods">
                                {foreach $shipping as $quote}
                                    {set $shipping_model = $quote->shipping}
                                    <div class="row">
                                        <div class="columns small-9">
                                            <input id="shipping_{$gi}" type="radio" name="shipping_rates[{$gi}]" value="{$quote->rateid}"/>
                                            <label for="shipping_{$gi}">
                                                <span class="name">{$shipping_model->getName()}</span> {$shipping_model->shipping_time}
                                            </label>
                                        </div>
                                        <div class="columns small-3">
                                            <span class="cost">US$ {$quote->getShippingCharge()|number_format:2}</span>
                                        </div>
                                    </div>
                                {/foreach}
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
                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <div class="hr"></div>
                </div>
            </div>

            <div class="row">
                <div class="columns small-5">
                </div>
                <div class="columns small-7">
                    <div class="row">
                        <div class="columns small-12">
                            <h2>{t 'Payment Methods' dict='order'}</h2>
                        </div>
                    </div>
                    {if $payment_methods}
                        <div class="payment-methods">
                            {foreach $payment_methods as $method}
                                <div class="row align-center-middle {cycle ["odd", ""]}">
                                    <div class="columns small-4">
                                        <input id="payment_{$method->paymentid}" type="radio" name="payment_method" value="{$method->paymentid}"/>
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
                <div class="columns small-5">
                </div>
                <div class="columns small-7">
                    <div class="row">
                        <div class="columns small-12">
                            <h2>{t 'Is Billing Address the same as Shipping Address?' dict='order'}</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12">
                            <input id="biiling_yes" type="radio" name="billing_address"/>
                            <label for="biiling_yes">
                                <span class="name">{t 'Yes' dict='order'}</span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12">
                            <input id="biiling_no" type="radio" name="billing_address"/>
                            <label for="biiling_no">
                                <span class="name">{t 'No' dict='order'}</span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column small-12">
                            <div class="buttons">
                                <button type="submit" class="button yellow waves waves-orange waves-effect">{t 'Continue' dict='order'}</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column small-12">
                            <div class="continue-notes">
                                {t 'Continue to the order review page' dict='order'}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </form>
{/block}