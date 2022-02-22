<div class="row align-center">
    <div class="column column-with-button">
        <h1 class="text-center fw-bold fs-3">{$header}</h1>

        <button type="submit" class="button submit yellow waves waves-orange waves-effect show-for-medium additional-button-large">
            <span class="short-label">{t 'Submit' }</span>
            <span class="long-label">{t 'Submit order' }</span>
        </button>

    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="order-review">
            {foreach $order->groups as $order_group}
                {set $warehouse = $.get_warehouse($order_group->manufacturerid)}
                {set $items = $order_group->detail_models}
                <h2 class="review-title">
                    {t 'The items below will be shipped from warehouse in' } {$warehouse->m_city},
                    {if $config.show_full_state_country === 'Y'}{$warehouse->state_model}{else}{$warehouse->m_state}{/if},
                    {if $config.show_full_state_country === 'Y'}{$warehouse->country_model}{else}{$warehouse->m_country}{/if}
                </h2>
                <div class="order-table">
                    <div class="order-table-row table-head show-for-large">
                        <div class="order-table-cell sku">
                            {t 'SKU' }
                        </div>
                        <div class="order-table-cell item-name">
                            {t 'Item name' }
                        </div>
                        <div class="order-table-cell price">
                            {t 'Price' }
                        </div>
                        <div class="order-table-cell quantity">
                            {t 'Quantity' }
                        </div>
                        <div class="order-table-cell extended">
                            {t 'Extended' }
                        </div>
                    </div>
                    {foreach $items as $item}
                        <div class="order-table-row table-body">
                            <div class="order-table-cell sku show-for-large">
                                {$item->productcode}
                            </div>

                            <div class="order-table-cell picture hide-for-large">
                                {include "catalog/parts/_item_image.tpl" model = $item->product_model}
                            </div>

                            <div class="order-table-cell quantity hide-for-large">
                                {$item->amount}
                            </div>

                            <div class="order-table-cell product-info">
                                <div class="item-name">
                                    {$item->product}
                                    {include "checkout/_parts/_options.tpl" item=$item}
                                </div>
                                <div class="price-info hide-for-medium">
                                    {$item->price|site_currency}
                                </div>
                            </div>

                            <div class="order-table-cell price-info show-for-medium">
                                {$item->price|site_currency}
                            </div>

                            <div class="order-table-cell quantity show-for-large">
                                {$item->amount}
                            </div>

                            <div class="order-table-cell extended show-for-large">
                                {($item->amount * $item->price)|site_currency}
                            </div>
                        </div>
                    {/foreach}
                </div>
                <div class="group-info order-table-row">
                    {set $shippingModel = $order_group->shippingModel}
                    {set $is_tax_applied = $order_group->total_tax > 0}
                    {if $shippingModel}
                        <div class="sum-info shipping">
                                <span class="sum-info-label{if !$is_tax_applied} underline{/if}">
                                    {if $shippingModel->is_free_shipping}
                                        {$shippingModel->getFrontendName()}:
                                    {else}
                                        {t 'Shipping by' } {$shippingModel->getFrontendName()}:
                                    {/if}
                                    {* не должно быть пробела! *}
                                </span><span class="sum{if !$is_tax_applied} underline{/if}">
                                    {$order_group->shipping_gross|site_currency}
                                </span>
                        </div>
                    {/if}
                    {if $is_tax_applied}
                        {foreach $order_group->tax_rates as $group_tax}
                            <div class="sum-info tax-info">
                                <span class="sum-info-label underline">
                                    {$group_tax->tax_rate->tax}:
                                </span><span class="sum underline">
                                     {$group_tax->value|site_currency}
                                </span>
                            </div>
                        {/foreach}
                    {/if}
                    <div class="sum-info sum-price-info">
                        <span class="sum-info-label">
                            {t 'Subtotal' }:
                        </span>
                        <span class="sum">
                            {$order_group->total_gross|site_currency}
                        </span>
                    </div>
                </div>

            {/foreach}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="hr-bold"></div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="order-total">
            <div class="info-row total">
                <span class="label">{t 'Total' }:</span>
                <span class="sum">{$order->subtotal|site_currency}</span>
            </div>
            <div class="info-row total-shipping border-0 pt-0">
                <span class="label">{t 'Total Shipping Cost' }:</span>
                <span class="sum">{$order->shipping_cost|site_currency}</span>
            </div>
            <div class="info-row tax-info">
                {foreach $order->getTaxes() as $tax_name => $tax_rate}
                    <div class="sum-info tax-info">
                        <span class="label">{t 'Total'} {$tax_name}:</span>
                        <span class="sum">{$tax_rate|site_currency}</span>
                    </div>
                {/foreach}
            </div>
            <div class="info-row grand-total">
                <span class="label">{t 'Grand Total' }:</span>
                <span class="sum">{$order->total|site_currency}</span>
            </div>
        </div>
    </div>
</div>



