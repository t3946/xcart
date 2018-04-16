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
                            <div class="row">
                                <div class="columns small-12">
                                    <ul class="quotes">
                                        {foreach $shipping as $quote}
                                            {set $shipping_model = $quote->shipping}
                                            <li>
                                                <input id="shipping_{$gi}" type="radio" name="shipping_rates[{$gi}]" value="{$quote->rateid}"
                                                <label class="label" for="shipping_{$gi}"><span class="name">{$shipping_model->getName()} {$shipping_model->shipping_time}: US$ {$quote->getShippingCharge()|number_format:2}</label>
                                            </li>
                                        {/foreach}
                                    </ul>
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
                </div>
            </div>
        </section>
    </form>
{/block}