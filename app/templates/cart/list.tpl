{extends "cart/base.tpl"}

{block 'content'}
<section class="cart-page">
    <div class="row">
        <div class="columns large-12">
            <h1>{t 'Shopping Cart' dict='cart'}</h1>

            {foreach $.app->cart->getItemsGroupedBy() as $gi => $group}
            {set $items = $group.items}
            {set $warehouse  = $.get_warehouse($gi) }

            <div class="warehouse_products">
                <div class="shipped_from">
                    The items below will be shipped from warehouse in {$warehouse->m_city}, {$warehouse->m_state}, {$warehouse->m_country}
                </div>

                <div class="table">
                    <div class="table-row table-head show-for-large">
                        <div class="table-column image-name">
                            {t 'Item name'  dict='cart'}
                        </div>

                        <div class="table-column price">
                            {t 'Price'  dict='cart'}
                        </div>

                        <div class="table-column quantity">
                            {t 'Quantity'  dict='cart'}
                        </div>
                        <div class="table-column x">
                            &nbsp;
                        </div>
                        <div class="table-column extended">
                            {t 'Extended'  dict='cart'}
                        </div>

                        <div class="table-column remove">
                            {t 'Remove'  dict='cart'}
                        </div>
                    </div>
                    <div class="table-body">
                        {foreach $items as $key=>$position}
                        <div class="table-row"
                             data-key="{$key}"
                             data-wh="{$gi}"
                             data-product='{$position->object->productid}'
                             data-subtotal="{$position->getPrice()|number_format:2}"
                             data-prices='{$position->object->getPrices()|json_encode}'
                             data-cart-action="{url 'cart:quantity:set:post' key=$key}">
                            <div class="table-column image">
                                {include 'catalog/parts/_item_image.tpl' model=$position->object}
                            </div>
                            <div class="table-wrapper name-quantity">

                                <div class="table-column name">

                                    <div class="title">
                                        <a href="{$position->object->getAbsoluteUrl()}">
                                            {$position->object}
                                        </a>
                                    </div>

                                    <div class="code sku show-for-medium">
                                        <div class="value">
                                            {t 'SKU'  dict='cart'}:
                                            {$position->object->productcode}
                                        </div>
                                    </div>

                                    {foreach $position->data as $name => $value}
                                        <p>{$name}: {$value}</p>
                                    {/foreach}

                                </div>

                                <div class="table-column price show-for-large format_price">
                                    US$ <span class="price" var-price>{$position->object->getFrontendPrice()|number_format:2}</span>
                                </div>

                                <div class="table-wrapper quantity-extended">
                                    <div class="table-column quantity">
                                        <div class="inline-block">
                                            <div class="quantity-group">
                                                <a href="{url 'cart:quantity:dec' key=$key}" class="btn active dec">-</a>
                                                <input type="number" name="quantity"
                                                       min="{$position->object->min_amount}"
                                                       max="{$position->object->avail}"
                                                       step="{if $position->object->mult_order_quantity == 'Y'}{$position->object->min_amount}{else}1{/if}"
                                                       value="{$position->quantity}">
                                                <a href="{url 'cart:quantity:inc' key=$key}" class="btn active inc">+</a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="table-column x">x</div>

                                    <div class="table-column extended format_price">
                                        <span class="show-for-large">
                                            US$ <span class="price" var-price-extended>{$position->getPrice()|number_format:2}</span>
                                        </span>
                                        <span class="hide-for-large">
                                            US$ <span class="price" var-price-extended>{$position->object->getFrontendPrice()|number_format:2}</span>
                                        </span>
                                    </div>
                                </div>

                            </div>

                            <div class="table-column remove">
                                <a href="{url 'cart:delete' key=$key}" title="{t 'Delete' dict='cart'}" class="icon cart_remove text-hide" onclick="loader.load(this)"></a>
                            </div>

                        </div>
                        {/foreach}
                    </div>
                </div>

                <div class="warehouse_subtotal wh_{$gi}" data-wh="{$gi}">
                    <div class="table">
                        <div class="table-body">

                            <div class="table-row">
                                <div class="table-column auto from">
                                    {$warehouse->m_city},
                                    {$warehouse->m_state},
                                    {$warehouse->m_country}
                                    warehouse subtotal:
                                </div>
                                <div class="table-column extended_remove format_price">
                                    US$ <span class="wh_{$gi}_subtotal subtotal" var-group-subtotal>{$group.subtotal|number_format:2}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                {/foreach}
            </div>

            <div class="hr"></div>

            <div class="memo_subtotal">
                <div class="grand-subtotal">
                    Subtotal:
                    <div class="subtotal">
                        US$ <span class="cart_subtotal" var-cart-subtotal>{$total|number_format:2}</span>
                    </div>
                </div>

                <div class="memo">
                    Your merchandise subtotal does not include shipping charges and taxes, which will be reflected on the 'order review' page.
                </div>
            </div>
        </div>
    </div>
</section>
{/block}