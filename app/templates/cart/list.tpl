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
                    <div class="thead show-for-large">
                        <div class="trow">
                            <div class="tcell cart_name">
                                {t 'Item name'  dict='cart'}
                            </div>
                            <div class="tcell cart_price">
                                {t 'Price'  dict='cart'}
                            </div>

                            <div class="cart_quantity_extended">
                                <div class="tcell cart_quantity">
                                    {t 'Quantity'  dict='cart'}
                                </div>
                                <div class="tcell cart_x">&nbsp;</div>
                                <div class="tcell cart_extended">
                                    {t 'Extended'  dict='cart'}
                                </div>
                            </div>

                            <div class="tcell cart_remove">
                                {t 'Remove'  dict='cart'}
                            </div>
                        </div>
                    </div>
                    <div class="tbody">
                        {foreach $items as $key=>$position}
                            <div class="trow">
                            <div class="tcell cart_name">
                                {$position->object}

                                {foreach $position->data as $name => $value}
                                    <p>{$name}: {$value}</p>
                                {/foreach}
                            </div>
                            <div class="tcell cart_price">
                                ${$position->object->getFrontendPrice()}
                            </div>
                            <div class="tcell cart_quantity_extended">
                                <div class="tcell cart_quantity">
                                    <div>
                                        <div class="quantity-group">
                                            <a href="{url 'catalog:cart:quantity:dec' key=$key}" class="btn dec">-</a>
                                            <input type="number" name="quantity"
                                                   min="{$position->object->min_amount}"
                                                   max="{$position->object->avail}"
                                                   step="{if $position->object->mult_order_quantity == 'Y'}{$position->object->min_amount}{else}1{/if}"
                                                   value="{$position->quantity}" data-action="{url 'catalog:cart:quantity:set:post' key=$key}">
                                            <a href="{url 'catalog:cart:quantity:inc' key=$key}" class="btn inc">+</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="tcell cart_x">
                                    X
                                </div>
                                <div class="tcell cart_extended">
                                    ${$position->getPrice()}
                                </div>
                            </div>
                            <div class="tcell cart_remove">
                                <a href="{url 'catalog:cart:delete' key=$key}" title="{t 'Delete' dict='cart'}" class="remove">{t 'Delete' dict='cart'}</a>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>

                <div class="subtotal">
                    {$waregouse->m_city},
                    {$waregouse->m_state},
                    {$waregouse->m_country}
                    warehouse subtotal: {$group.subtotal}
                </div>
                {/foreach}
            </div>

            <div class="hr"></div>

            <div class="grand_subtotal">
                {t "Subtotal" dict='cart'}: {$total}
            </div>
        </div>
    </div>
</section>
{/block}