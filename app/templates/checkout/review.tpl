{extends "checkout/base.tpl"}

{block 'content'}
    <form data-abide action="{url 'checkout:options'}" method="POST" class="checkout-review-form">
        <section class="checkout-review">
            <div class="row align-center">
                <div class="columns small-12">
                    <h1 class="text-center">{t 'Product ordered' dict='order'}</h1>
                    <div class="order-review">
                        {foreach $.app->cart->getItemsGroupedBy() as $gi => $group}
                            {set $warehouse = $.get_warehouse($gi)}
                            {set $order_group = $order->groups->get(['manufacturerid' => $gi])}
                            {set $items = $group.items}
                            <div class="row shipped_from align-center">
                                <div class="columns small-12 text-align--center">
                                    <h2>{t 'The items below will be shipped from warehouse in' dict='order'} {$warehouse->m_city}, {$warehouse->m_state}, {$warehouse->m_country}</h2>
                                </div>
                            </div>
                            <div class="order-table">
                                <div class="row order-table-head">
                                    <div class="columns small-2 text-align--center sku">
                                        {t 'SKU' dict='cart'}
                                    </div>
                                    <div class="columns small-5 text-align--center item-name">
                                        {t 'Item name' dict='cart'}
                                    </div>
                                    <div class="columns small-2 text-align--center price">
                                        {t 'Price' dict='cart'}
                                    </div>
                                    <div class="columns small-1 text-align--center quantity">
                                        {t 'Quantity' dict='cart'}
                                    </div>
                                    <div class="columns small-2 text-align--center extended">
                                        {t 'Extended' dict='cart'}
                                    </div>
                                </div>
                                {foreach $items as $key=>$position}
                                    <div class="row order-table-body">
                                        <div class="columns small-2 text-align--center sku">
                                            {$position->object->productcode}
                                        </div>

                                        <div class="columns small-5 item-name">
                                            {$position->object}
                                            {foreach $position->data as $name => $value}
                                                <p>{$name}: {$value}</p>
                                            {/foreach}
                                        </div>

                                        <div class="columns small-2 text-align--center price">
                                            US$ <span class="price">{$position->object->getFrontendPrice()|number_format:2}</span>
                                        </div>

                                        <div class="columns small-1 text-align--center quantity">{$position->quantity}</div>
                                        <div class="columns small-2 text-align--center extended">
                                            {set $extended = $position->quantity * $position->object->getFrontendPrice()}
                                            {$extended|number_format:2}
                                        </div>
                                    </div>
                                {/foreach}
                            </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        </section>
    </form>
{/block}