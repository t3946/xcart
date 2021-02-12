{set $site = $.getSite}
{set $site_currency = $site->getCurrency()}

{set $warehouse = $.get_warehouse($gi)}
{set $shipping = $shipping_rates[$gi]}

{set $order_group = $order->groups->get(['manufacturerid' => $gi])}
<div class="product-group-shipping">
    {if $shipping}
        <div class="row">
            <div class="columns small-12">
                {foreach $shipping as $quote first=$first}
                    {set $shipping_model = $quote->shipping}
                    {if $shipping_model}
                        <label class="checkout-shipping-method-row checkout__shipping-method-item" for="shipping_{$quote->rateid}">
                            <input
                                    {if $first}required{/if} {if ($first) || ($order_group && $order_group->shippingid == $shipping_model->shippingid)}checked{/if}
                                    id="shipping_{$quote->rateid}"
                                    type="radio"
                                    name="shipping_rates[{$gi}]"
                                    class="common-input-radio"
                                    value="{$quote->rateid}"
                            />

                            <label class="methods-label common-radio-label shipping-radio-label shipping-method-row__label" for="shipping_{$quote->rateid}">
                                <span class="methods-text">
                                    <span class="shipping-method-name">{$shipping_model->getFrontendName()}</span>
                                    <span class="shipping-method-comment">- {$shipping_model->shipping_time}</span>
                                </span>
                                <div class="methods-cell delivery-item-price">
                                    <span class="cost">{$site_currency->symbol_prefix}{$site_currency} {$site_currency->getCurrencyFormat($quote->getShippingCharge())}</span>
                                </div>
                            </label>
                        </label>
                    {/if}
                {/foreach}
            </div>
        </div>
    {elseif !isset($silent) || $silent===false}
        {add $phone_order_only = true}
        <div class="row">
            <div class="columns small-12">
                <div class="no-quotes">
                    {t "Our shipping server couldn't provide us with an accurate shipping quote. This sometimes occurs"}
                    <br/>
                    {t '- when the product is oversized or somehow irregular in shape or weight'}<br/>
                    {t '- for overseas shipments'}<br/>
                    <br/>
                    <b>{t 'Please go ahead and place your order.'}</b><br/>
                    {t 'We will determine an accurate shipping charge manually and send you an updated invoice.'}
                    <br/>
                    {t "At this point we won't collect your payment information."}
                </div>
            </div>
        </div>
    {/if}
</div>