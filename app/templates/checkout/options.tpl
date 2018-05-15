{extends "checkout/base.tpl"}

{block 'js'}
    <script type="text/javascript">
        function hideForm() {
            document.getElementById("registration").classList.add("hide");
        }
        function showForm() {
            document.getElementById("registration").classList.remove("hide");
        }
    </script>
{/block}

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
                        {include 'checkout/_address_view.tpl' info=$shipping_address header=$.t('Shipping Address','order') uri='checkout:shipping'}
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
                        {set $order_group = $order->groups->get(['manufacturerid' => $gi])}

                        <div class="row">
                            <div class="columns small-12">
                                <h3 class="shipped-from">
                                    {t 'Delivery methods for' dict='order'} <a>{t 'the items' dict='order'}</a> {t 'shipped from warehouse in' dict='order'} {$warehouse->m_city}, {$warehouse->m_state}, {$warehouse->m_country}
                                </h3>
                            </div>
                        </div>
                        {if $shipping}
                            <div class="shipping-methods">
                                {foreach $shipping as $quote first=$first}
                                    {set $shipping_model = $quote->shipping}
                                    <div class="row">
                                        <div class="columns small-9">
                                            <input {if $first}required{/if} {if (!$order_group && $first) || ($order_group && $order_group->shippingid == $shipping_model->shippingid)}checked{/if} id="shipping_{$gi}" type="radio" name="shipping_rates[{$gi}]" value="{$quote->rateid}"/>
                                            <label for="shipping_{$gi}">
                                                <span class="name">{$shipping_model->getFrontendName()}</span> {$shipping_model->shipping_time}
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
                            {foreach $payment_methods as $method first=$first}
                                <div class="row align-center-middle {cycle ["odd", ""]}">
                                    <div class="columns small-4">
                                        <input {if ($first && !$order->paymentid) || ($method->paymentid == $order->paymentid)}checked{/if} id="payment_{$method->paymentid}" type="radio" name="payment_method" value="{$method->paymentid}"/>
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


                    <div class="registration {if !$billing_diff}hide{/if}" id="registration">
                        <div class="row">
                            <div class="small-4 columns"></div>
                            <div class="small-8 columns">
                                <div class="mandatory">
                                    {t 'The fields marked with' dict='order'} <span class="required">*</span> {t 'are mandatory.' dict='order'}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-4 columns">
                                <label for="registration__b_firstname">{t 'Full name' dict='order'}</label>
                                <span class="reqired">*</span>
                            </div>
                            <div class="small-8 columns">
                                <input value="{$billing_address['firstname']}" id="registration__b_firstname" placeholder="{t 'Albert H. Einstein' dict='order'}" name="BillingAddressForm[b_firstname]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-4 columns">
                                <label for="registration__b_company">{t 'Company' dict='order'}</label>
                                <i>{t '(optional)' dict='order'}</i>
                            </div>
                            <div class="small-8 columns">
                                <input value="{$billing_address['company']}" id="registration__b_company" placeholder="{t 'Eureka Inc.' dict='order'}" name="BillingAddressForm[b_company]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-4 columns">
                                <label for="registration__b_address">{t 'Address' dict='order'}</label>
                                <span class="reqired">*</span>
                            </div>
                            <div class="small-8 columns">
                                <input value="{$billing_address['address'][0]}" id="registration__b_address" placeholder="{t '112 Mercer Street' dict='order'}" name="BillingAddressForm[b_address]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-4 columns">
                                <label for="registration__b_address_2">{t 'Address (line 2)' dict='order'}</label>
                            </div>
                            <div class="small-8 columns">
                                <input value="{$billing_address['address'][1]}" id="registration__b_address_2" placeholder="{t 'Apt 1' dict='order'}" name="BillingAddressForm[b_address_2]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-4 columns">
                                <label for="registration__b_countryname">{t 'Country' dict='order'}</label>
                                <span class="reqired">*</span>
                            </div>
                            <div class="small-8 columns">
                                <select id="registration__b_countryname" name="BillingAddressForm[b_country]">
                                    {foreach $countries as $country}
                                        <option {if $billing_address['country'] == $country.id}selected{/if} value="{raw $country.id}">{raw $country.text}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-4 columns">
                                <label for="registration__b_zipcode">{t 'Zip/Postal Code' dict='order'}</label>
                                <span class="reqired">*</span>
                            </div>
                            <div class="small-8 columns">
                                <input value="{$billing_address['zipcode']}" id="registration__b_zipcode" placeholder="{t '08540' dict='order'}" name="BillingAddressForm[b_zipcode]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-4 columns">
                                <label for="registration__b_statename">{t 'State/Province' dict='order'}</label>
                                <span class="reqired">*</span>
                            </div>
                            <div class="small-8 columns">
                                <input value="{$billing_address['state']}" id="registration__b_statename" placeholder="{t 'New Jersey' dict='order'}" name="BillingAddressForm[b_statename]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-4 columns">
                                <label for="registration__b_city">{t 'City' dict='order'}</label>
                                <span class="reqired">*</span>
                            </div>
                            <div class="small-8 columns">
                                <input value="{$billing_address['city']}" id="registration__b_city" placeholder="{t 'Princeton' dict='order'}" name="BillingAddressForm[b_city]" type="text"/>
                            </div>
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