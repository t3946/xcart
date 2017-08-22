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
                                <div class="image">
                                    {set $image = $position->object->images->limit(1)->get()}
                                    {if $image!}
                                        {if $.isBot}
                                            <img src="//cdn.{$.getSite->getBaseDomain()}{$image->getURL()}" width="{$image->image_x}" height="{$image->image_y}" alt="{$position->object.product}" itemscope itemprop="image">
                                        {else}
                                            <img data-original="//cdn.{$.getSite->getBaseDomain()}{$image->getURL()}" width="{$image->image_x}" height="{$image->image_y}" alt="{$position->object.product}" class="lazy lazy-img" itemprop="image">
                                        {/if}
                                    {else}

                                        {*<img src="http://via.placeholder.com/200x200/efefef/a6a6a6/?text=No+image" alt="Image not available">*}
                                        <div class="not-avail">
                                            <span class="text">
                                                Image not available
                                            </span>
                                        </div>
                                    {/if}
                                </div>

                                <div class="name_sku">

                                    <div class="name">
                                        {$position->object}
                                    </div>

                                    <div class="code sku">
                                        <div class="value">
                                            {t 'SKU'  dict='cart'}:
                                            {$position->object->productcode}
                                        </div>
                                    </div>

                                    {foreach $position->data as $name => $value}
                                        <p>{$name}: {$value}</p>
                                    {/foreach}
                                </div>

                            </div>
                            <div class="tcell cart_price">
                                US${$position->object->getFrontendPrice()}
                            </div>
                            <div class="tcell cart_quantity_extended">
                                <div class="tcell cart_quantity">
                                    <div class="inline-block">
                                        <div class="quantity-group">
                                            <a href="{url 'catalog:cart:quantity:dec' key=$key}" class="btn active dec">-</a>
                                            <input type="number" name="quantity"
                                                   min="{$position->object->min_amount}"
                                                   max="{$position->object->avail}"
                                                   step="{if $position->object->mult_order_quantity == 'Y'}{$position->object->min_amount}{else}1{/if}"
                                                   value="{$position->quantity}" data-action="{url 'catalog:cart:quantity:set:post' key=$key}">
                                            <a href="{url 'catalog:cart:quantity:inc' key=$key}" class="btn active inc">+</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="tcell cart_x">
                                    х
                                </div>
                                <div class="tcell cart_extended">
                                    US${$position->getPrice()}
                                </div>
                            </div>
                            <div class="tcell cart_remove">
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