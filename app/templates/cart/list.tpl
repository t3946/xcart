{extends "cart/base.tpl"}

{block 'content'}
<section class="cart-page">
    <div class="row">
        <div class="columns large-12">
            <h1>{t 'Shopping Cart' dict='cart'}</h1>

            {foreach $.app->cart->getItemsGroupedBy() as $gi => $group}
            {set $items = $group.items}
            {set $waregouse = $.get_warehouse($gi) }

            <div class="warehouse_products">
                <div class="shipped_from">
                    The items below will be shipped from warehouse in {$waregouse->m_city}, {$waregouse->m_state}, {$waregouse->m_country}
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
                        <div class="table-row">
                            <div class="table-column image">
                                {include 'catalog/parts/_item_image.tpl' model=$position->object}
                            </div>
                            <div class="table-wrapper name-quantity">

                                <div class="table-column name">


                                    <div class="title">
                                        {$position->object}
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

                                <div class="table-column price show-for-large">
                                    US$ {$position->object->getFrontendPrice()|number_format:2}
                                </div>

                                <div class="table-wrapper quantity-extended">
                                    <div class="table-column quantity">
                                        <div class="inline-block">
                                            <div class="quantity-group">
                                                <a href="{url 'catalog:cart:quantity:dec' key=$key}" class="btn active dec">-</a>
                                                <input type="number" name="quantity"
                                                       min="{$position->object->min_amount}"
                                                       max="{$position->object->avail}"
                                                       step="{if $position->object->mult_order_quantity == 'Y'}{$position->object->min_amount}{else}1{/if}"
                                                       value="{$position->quantity}"
                                                       data-action="{url 'catalog:cart:quantity:set:post' key=$key}">
                                                <a href="{url 'catalog:cart:quantity:inc' key=$key}" class="btn active inc">+</a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="table-column x">x</div>

                                    <div class="table-column extended">
                                        US$ {$position->getPrice()|number_format:2}
                                    </div>
                                </div>

                            </div>

                            <div class="table-column remove">
                                <a href="{url 'catalog:cart:delete' key=$key}" title="{t 'Delete' dict='cart'}" class="remove">{t 'Delete' dict='cart'}</a>
                            </div>

                        </div>
                        {/foreach}
                    </div>
                </div>

                <div class="warehouse_subtotal">
                    <div class="from">

                        {$waregouse->m_city},
                        {$waregouse->m_state},
                        {$waregouse->m_country}
                        warehouse subtotal:
                    </div>
                    <div class="subtotal">
                        US${$group.subtotal}
                    </div>
                </div>
                {/foreach}
            </div>

            <div class="hr"></div>

            <div class="grand_subtotal">
                <div class="memo">
                    Your merchandise subtotal does not include shipping charges and taxes, which will be reflected on the 'order review' page.
                </div>
                <div class="subtotal_title">
                    Subtotal:
                </div>
                <div class="subtotal">
                    US${$total}
                </div>
            </div>
        </div>
    </div>
</section>
{/block}