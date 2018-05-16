<div class="row align-center">
    <div class="column">
        <h1 class="text-center">{$header}</h1>
    </div>
</div>
<div class="row">
    <div class="columns small-12">
        <div class="order-review">
            {foreach $order->groups as $order_group}
                {set $warehouse = $.get_warehouse($order_group->manufacturerid)}
                {set $items = $order_group->detail_models}
                <h2 class="review-title">{t 'The items below will be shipped from warehouse in' dict='order'} {$warehouse->m_city}
                    , {$warehouse->m_state}, {$warehouse->m_country}</h2>
                <div class="order-table">
                    <div class="order-table-row table-head show-for-large">
                        <div class="order-table-cell sku">
                            {t 'SKU' dict='cart'}
                        </div>
                        <div class="order-table-cell item-name">
                            {t 'Item name' dict='cart'}
                        </div>
                        <div class="order-table-cell price">
                            {t 'Price' dict='cart'}
                        </div>
                        <div class="order-table-cell quantity">
                            {t 'Quantity' dict='cart'}
                        </div>
                        <div class="order-table-cell extended">
                            {t 'Extended' dict='cart'}
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
                                </div>
                                <div class="price-info hide-for-medium">
                                    US$ <span class="price">{$item->price|number_format:2}</span>
                                </div>
                            </div>

                            <div class="order-table-cell price-info show-for-medium">
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
                <div class="group-info order-table-row">
                    {if $order_group->shippingModel}
                        <div class="info shipping">
                                <span class="shipping-text">{t 'Shipping by' dict='order'} {$order_group->shippingModel->getFrontendName()}
                                    :
                                    US$ <span class="price">{$order_group->shipping_gross|number_format:2}</span>
                                </span>
                        </div>
                    {/if}
                    <div class="info shipping">
                                <span class="shipping-text">{t 'Shipping by' dict='order'} sfdfdfdfg
                                    :
                                    US$ <span class="price">3434535</span>
                                </span>
                    </div>
                    <div class="info price-info">
                        {t 'Subtotal:' dict='order'}
                        US$ <span class="price">{$order_group->total_gross|number_format:2}</span>
                    </div>
                </div>

            {/foreach}
        </div>
    </div>
</div>


