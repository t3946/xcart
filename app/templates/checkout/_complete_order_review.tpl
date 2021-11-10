<div class="checkout-review">

    <div class="row align-center">
        <div class="column text-align--center">
            <div class="title">{t 'Products Ordered'}</div>
        </div>
    </div>

    <div class="row">
        <div class="columns small-12">
            <div class="order-review">
                {foreach $order_groups as $group}
                    {set $warehouse = $.get_warehouse($group->manufacturerid)}
                    <h2 class="review-title">
                        {t 'The items below will be shipped from warehouse in'} {$warehouse->m_city},
                        {if $config.show_full_state_country}{$warehouse->state_model}{else}{$warehouse->m_state}{/if},
                        {if $config.show_full_state_country}{$warehouse->country_model->countryNameBySite()}{else}{$warehouse->m_country}{/if}
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
                                {t 'Qty ordered' }
                            </div>
                            <div class="order-table-cell extended">
                                {t 'Extended' }
                            </div>
                        </div>
                        {set $items = $group->detail_models}

                        {foreach $items as $item}
                            {set $extended = $item->amount * $item->price}
                            <div class="order-table-row table-body">
                                <div class="order-table-cell sku show-for-large">
                                    {$item->productcode}
                                </div>

                                <div class="order-table-cell picture hide-for-large">
                                    {include "catalog/parts/_item_image.tpl" model = $item->product_model}
                                </div>

                                <div class="order-table-cell product-info">
                                    <div class="item-name">
                                        {$item->product}
                                        {include "checkout/_parts/_options.tpl" item=$item}
                                    </div>
                                    <div class="price-info hide-for-large">
                                        {$item->amount} x {$extended|site_currency}
                                    </div>
                                </div>

                                <div class="order-table-cell price-info show-for-large">
                                    {$item->price|site_currency}
                                </div>

                                <div class="order-table-cell quantity show-for-large">
                                    {$item->amount}
                                </div>

                                <div class="order-table-cell extended show-for-large">
                                    {$extended|site_currency}
                                </div>
                            </div>
                        {/foreach}
                    </div>

                    <div class="group-info order-table-row">
                        <div class="sum-info shipping">
                            {set $shipping_m = $group->shippingModel}
                            {if $shipping_m}
                                <span class="sum-info-label">
                                    <span class="delivery-details show-for-medium">
                                        {t 'Delivery from'}  {$warehouse->m_city},
                                        {if $config.show_full_state_country}{$warehouse->state_model}{else}{$warehouse->m_state}{/if},
                                        {if $config.show_full_state_country}{$warehouse->country_model->countryNameBySite()}{else}{$warehouse->m_country}{/if} {t 'by'}
                                    </span>
                                    {$shipping_m->getFrontendName()}:
                                </span>
                            {/if}
                            <span class="sum">
                                {$group->shipping_gross|site_currency}
                            </span>
                        </div>
                    </div>

                {/foreach}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <div class="hr-bold"></div>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <div class="order-total">
                <div class="info-row total">
                    <span class="sum-info-label">{t 'Total' }:</span>
                    <span class="sum">{$order->subtotal|site_currency}</span>
                </div>
                <div class="info-row total-shipping">
                    <span class="sum-info-label">{t 'Total Shipping Cost' }:</span>
                    <span class="sum">{$order->shipping_cost|site_currency}</span>
                </div>
                <div class="info-row tax-info">
                    {foreach $order->getTaxes() as $tax_name => $tax_rate}
                        <div class="sum-info tax-info">
                            <span class="label">{t 'Total'}  {$tax_name}:</span>
                            <span class="sum">{$tax_rate|site_currency}</span>
                        </div>
                    {/foreach}
                </div>
                <div class="info-row grand-total">
                    <span class="label">{t 'Grand Total' }</span>
                    <span class="sum">{$order->total|site_currency}</span>
                </div>

            </div>
        </div>
    </div>
    {if $order->customer_notes}
    <div class="row">
        <div class="columns small-12 customer-notes-title-row large-3">
                <h2 class="customer-notes-title">{t 'Customer notes' }</h2>
        </div>
        <div class="columns small-12 large-6">
            <div class="customer-notes-text">
                <span class="sum">{$order->customer_notes}</span>
            </div>
        </div>
    </div>
    {/if}
</div>