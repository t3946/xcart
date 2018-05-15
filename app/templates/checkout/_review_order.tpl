<div class="row align-center">
    <div class="column">
        <h1 class="text-center">{$header}</h1>
    </div>
</div>
<div class="order-review">
{foreach $order->groups as $order_group}
    {set $warehouse = $.get_warehouse($order_group->manufacturerid)}
    {set $items = $order_group->detail_models}
    <div class="row shipped_from align-center">
        <div class="columns text-align--center">
            <h2>{t 'The items below will be shipped from warehouse in' dict='order'} {$warehouse->m_city}, {$warehouse->m_state}, {$warehouse->m_country}</h2>
        </div>
    </div>
    <div class="order-table">
        <div class="row hide-for-small show-for-large">
            <div class="columns small-2 text-align--center sku">
                {t 'SKU' dict='cart'}
            </div>
            <div class="columns small-5 text-align--center item-name">
                {t 'Item name' dict='cart'}
            </div>
            <div class="columns text-align--center price">
                {t 'Price' dict='cart'}
            </div>
            <div class="columns small-1 text-align--center quantity">
                {t 'Quantity' dict='cart'}
            </div>
            <div class="columns extended">
                {t 'Extended' dict='cart'}
            </div>
        </div>
        <div class="table-items">
            {foreach $items as $item}
                <div class="table-row">
                    <div class="table-cell sku hide-for-small show-for-large">
                        {$item->productcode}
                    </div>

                    <div class="table-cell picture hide-for-large">
                        {include "catalog/parts/_item_image.tpl" model = $item->product_model}
                    </div>

                    <div class="quantity table-cell">
                        {$item->amount}
                    </div>

                    <div class="product-info table-cell">
                        <div class="item-name">
                            {$item->product}
                        </div>
                        <div class="price-info">
                            US$ <span class="price">{$item->price|number_format:2}</span>
                        </div>
                    </div>

                    <div class="extended hide-for-small show-for-large table-cell">
                        {set $extended = $item->amount * $item->price}
                        US$ <span class="price">{$extended|number_format:2}</span>
                    </div>
                </div>
            {/foreach}
        </div>

        {if $order_group->shippingModel}
            <div class="row group-shipping group-info">
                <div class="columns small-12">
                    <div class="shipping-text">{t 'Shipping by' dict='order'} {$order_group->shippingModel->getFrontendName()}:
                        US$ <span class="price">{$order_group->shipping_gross|number_format:2}</span>
                    </div>
                </div>
            </div>
        {/if}
        <div class="row group-total group-info">
            <div class="columns small-12">{t 'Subtotal:' dict='order'}
                US$ <span class="price">{$order_group->total_gross|number_format:2}</span></div>
        </div>
    </div>
{/foreach}
</div>