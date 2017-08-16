{if $states}
        {foreach $states as $state}
            <div class="row">
                <div class="columns large-2">
                    <b>{$state->state}</b>
                </div>
                <div class="columns large-10">
                    <ul>
                    {foreach $rates[$state->stateid] as $rate}
                        {set $shipping_model = $rate->getShippingEntity()}
                        <li>US$ {$rate->getShippingCharge()|formatprice:",":"."} ({$shipping_model->getFrontendName()} - {$shipping_model->shipping_time})</li>
                    {/foreach}
                    </ul>
                </div>
            </div>
        {/foreach}

{/if}