{if $shipping_rate}
    <table>
        <tr>
            {assign var=shipping_model value=$shipping_rate->getShippingEntity()}
            <td>The cost of shipping {$qty} item{if $qty > 1}s{/if} to {$shipping_state->state}, {$shipping_state->country_code}:<br><b>US$ {$shipping_rate->getShippingCharge()|price_format} ({$shipping_model->getFrontendName()} - {$shipping_model->shipping_time})</b></td>
        </tr>
        <tr>
            <td><i>Shipping cost of all your cart items can be obtained at the checkout.</i></td>
        </tr>
    </table>
{/if}