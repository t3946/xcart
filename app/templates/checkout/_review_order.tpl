<div class="row align-center">
    <div class="column column-with-button">
        <h1 class="text-center">{$header}</h1>

        <button type="submit" class="button submit yellow waves waves-orange waves-effect show-for-medium additional-button-large">
            <span class="short-label">{t 'Submit' dict='order'}</span>
            <span class="long-label">{t 'Submit order' dict='order'}</span>
        </button>

    </div>
</div>
<div class="row">
    <div class="columns small-12">
        <div class="order-review">
            {foreach $order->groups as $order_group}
                {set $warehouse = $.get_warehouse($order_group->manufacturerid)}
                {set $items = $order_group->detail_models}
                <h2 class="review-title">
                    {t 'The items below will be shipped from warehouse in' dict='order'} {$warehouse->m_city}
                    , {$warehouse->m_state}, {$warehouse->m_country}
                </h2>
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
                                    {include "checkout/_parts/_options.tpl" item=$item}
                                </div>
                                <div class="price-info hide-for-medium">
                                    {$site_currency->symbol_prefix}{$site_currency} <span class="price">{$site_currency->getCurrencyFormat($item->price)}</span>
                                </div>
                            </div>

                            <div class="order-table-cell price-info show-for-medium">
                                {$site_currency->symbol_prefix}{$site_currency}&nbsp;<span class="price">{$site_currency->getCurrencyFormat($item->price)}</span>
                            </div>

                            <div class="order-table-cell quantity show-for-large">
                                {$item->amount}
                            </div>

                            <div class="order-table-cell extended show-for-large">
                                {set $extended = $item->amount * $item->price}
                                {$site_currency->symbol_prefix}{$site_currency}&nbsp;<span class="price">{$site_currency->getCurrencyFormat($extended)}</span>
                            </div>
                        </div>
                    {/foreach}
                </div>
                <div class="group-info order-table-row">
                    {set $shippingModel = $order_group->shippingModel}
                    {if $shippingModel}
                        <div class="sum-info shipping">
                                <span class="sum-info-label underline">
                                    {if $shippingModel->is_free_shipping}
                                        {$shippingModel->getFrontendName()}:
                                    {else}
                                        {t 'Shipping by' dict='order'} {$shippingModel->getFrontendName()}:
                                    {/if}
                                    {* не должно быть пробела! *}
                                </span><span class="sum underline">
                                    {$site_currency->symbol_prefix}{$site_currency} <span class="price">{$site_currency->getCurrencyFormat($order_group->shipping_gross)}</span>
                                </span>
                        </div>
                    {/if}
                    {*<div class="sum-info shipping">*}
                                {*<span class="sum-info-label underline">*}
                                    {*{t 'Shipping by' dict='order'} dsfsdfsdsddf:*}
                                {*</span><span class="sum underline">*}
                                    {*US$ <span class="price">123.45</span>*}
                        {*</span>*}
                    {*</div>*}
                    <div class="sum-info sum-price-info">
                        <span class="sum-info-label">
                            {t 'Subtotal:' dict='order'}
                        </span>
                        <span class="sum">
                            {$site_currency->symbol_prefix}{$site_currency} <span class="price">{$site_currency->getCurrencyFormat($order_group->total_gross)}</span>
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
                <span class="label">{t 'Total:' dict='order'}</span>
                <span class="sum">{$site_currency->symbol_prefix}{$site_currency} <span class="price">{$site_currency->getCurrencyFormat($order->subtotal)}</span></span>
            </div>
            <div class="info-row total-shipping">
                <span class="label">{t 'Total Shipping Cost:' dict='order'}</span>
                <span class="sum">{$site_currency->symbol_prefix}{$site_currency} <span class="price">{$site_currency->getCurrencyFormat($order->shipping_cost)}</span></span>
            </div>
            <div class="info-row grand-total">
                <span class="label">{t 'Grand Total:' dict='order'}</span>
                <span class="sum">{$site_currency->symbol_prefix}{$site_currency} <span class="price">{$site_currency->getCurrencyFormat($order->total)}</span></span>
            </div>
        </div>
    </div>
</div>



