<div class="checkout-review">

    <div class="row align-center">
        <div class="column text-align--center">
            <div class="title">{t 'Products Ordered' dict='order'}</div>
        </div>
    </div>

    <div class="row">
        <div class="columns small-12">
            <div class="order-review">
                {foreach $order_groups as $group}
                    {set $warehouse = $.get_warehouse($group->manufacturerid)}
                    <h2 class="review-title">
                        {t 'The item below will be shipped from warehouse in' dict='order'} {$warehouse->m_city}, {$warehouse->m_state}, {$warehouse->m_country}
                    </h2>
                    <div class="order-table">
                        <div class="order-table-row table-head show-for-large">
                            <div class="order-table-cell sku">
                                {t 'SKU' dict='order'}
                            </div>
                            <div class="order-table-cell item-name">
                                {t 'Item name' dict='order'}
                            </div>
                            <div class="order-table-cell price">
                                {t 'Price' dict='order'}
                            </div>
                            <div class="order-table-cell quantity">
                                {t 'Qty ordered' dict='order'}
                            </div>
                            <div class="order-table-cell extended">
                                {t 'Extended' dict='order'}
                            </div>
                        </div>
                        {set $items = $group->detail_models}

                        {foreach $items as $item}
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
                                    </div>
                                    <div class="price-info hide-for-large">
                                        {set $extended = $item->amount * $item->price}
                                        {$item->amount} x US$ <span class="price">{$item->price|number_format:2}</span> = US$&nbsp;<span class="price">{$extended|number_format:2}</span>
                                    </div>
                                </div>

                                <div class="order-table-cell price-info show-for-large">
                                    US$&nbsp;<span class="price">{$item->price|number_format:2}</span>
                                </div>

                                <div class="order-table-cell quantity show-for-large">
                                    {$item->amount}
                                </div>

                                <div class="order-table-cell extended show-for-large">
                                    {set $extended = $item->amount * $item->price}
                                    US$&nbsp;<span class="price">{$extended|number_format:2}</span>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                    <div class="order-group-shipping">
                        <div class="row">
                            <div class="columns small-10 text-align-right">
                                {t 'Delivery from'}  {$warehouse->m_city}, {$warehouse->m_state}, {$warehouse->m_country} {t 'by'} {$group->shippingModel->getFrontendName()} :
                            </div>
                            <div class="columns small-2">
                                US$ <span class="price">{$group->shipping_gross|number_format:2}</span>
                            </div>
                        </div>
                    </div>
                    <div class="group-info order-table-row">
                        <div class="sum-info shipping">
                            <span class="sum-info-label">
                                {t 'Total' dict='order'}
                            </span>
                            <span class="sum">
                                US$ <span class="price">{$order->subtotal|number_format:2}</span>
                            </span>
                        </div>
                        <div class="sum-info sum-price-info">
                        <span class="sum-info-label">
                            {t 'Total Shipping Cost' dict='order'}
                        </span>
                            <span class="sum">
                            US$ <span class="price">{$order->shipping_cost|number_format:2}</span>
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
                    <span class="label">{t 'Grand Total' dict='order'}</span>
                    <span class="sum">{$order->total|number_format:2}</span>
                </div>
                {if $hst}
                <div class="info-row total-shipping">
                    <span class="label">{t 'Including 13% HST' dict='order'}</span>
                    <span class="sum">US$ <span class="price">{$order->tax|number_format:2}</span></span>
                </div>
                {/if}
                {if $order->customer_notes}
                <div class="info-row grand-total">
                    <span class="label">{t 'Customer notes' dict='order'}</span>
                    <span class="sum">{$order->customer_notes}</span></span>
                </div>
                {/if}
            </div>
        </div>
    </div>
</div>