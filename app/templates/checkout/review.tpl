{extends "checkout/base.tpl"}

{block 'content'}
    <form data-abide action="{url 'checkout:options'}" method="POST" class="checkout-review-form">
        <section class="checkout-review">
            <div class="row align-center">
                <div class="columns small-12">
                    <h1 class="text-center">{t 'Product ordered' dict='order'}</h1>
                </div>
            </div>
            <div class="order-review">
                {foreach $.app->cart->getItemsGroupedBy() as $gi => $group}
                    {set $warehouse = $.get_warehouse($gi)}
                    {set $order_group = $order->groups->get(['manufacturerid' => $gi])}
                    {set $items = $group.items}
                    <div class="row shipped_from align-center">
                        <div class="columns text-align--center">
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
                        {set $subtotal = 0}
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

                                <div class="columns text-align--center price">
                                    US$ <span class="price">{$position->object->getFrontendPrice()|number_format:2}</span>
                                </div>

                                <div class="columns small-1 text-align--center quantity">{$position->quantity}</div>
                                <div class="columns extended">
                                    {set $extended = $position->quantity * $position->object->getFrontendPrice()}
                                    US$ <span class="price">{$extended|number_format:2}</span>
                                </div>
                            </div>
                            {set $subtotal += $extended}
                            {set $shipping_total += $order_group->shipping_gross}
                        {/foreach}
                        <div class="row group-shipping">
                            <div class="columns text-align--right">{t 'Shipping by' dict='order'} {$order_group->shippingModel->getFrontendName()}:</div>
                            <div class="columns small-2">US$ <span class="price">{$order_group->shipping_gross|number_format:2}</span></div>
                        </div>
                        <div class="row group-total">
                            <div class="columns text-align--right">{t 'Subtotal:' dict='order'}</div>
                            <div class="columns small-2">US$ <span class="price">{$subtotal|number_format:2}</span></div>
                        </div>
                    </div>
                    {set $order_total += $subtotal}
                {/foreach}
            </div>
            <div class="row">
                <div class="small-12 columns">
                    <div class="hr"></div>
                </div>
            </div>
            <div class="order-total">
                <div class="row total">
                    <div class="columns small-offset-8 text-align--right">{t 'Total:' dict='order'}</div>
                    <div class="columns">US$ <span class="price">{$order_total|number_format:2}</span></div>
                </div>
                <div class="row total-shipping">
                    <div class="columns title small-offset-8 text-align--right">{t 'Total Shipping Cost:' dict='order'}</div>
                    <div class="columns value">US$ <span class="price">{$shipping_total|number_format:2}</span></div>
                </div>
                {set $grand_total = $order_total + $shipping_total}
                <div class="row grand-total">
                    <div class="columns title small-offset-8 text-align--right">{t 'Grand Total:' dict='order'}</div>
                    <div class="columns value">US$ <span class="price">{$grand_total|number_format:2}</span></div>
                </div>
            </div>

        </section>

        <section class="shipping-review">
            <div class="row align-center">
                <div class="columns small-12">
                    <h1 class="text-center">{t 'Shipping and Billing Address' dict='order'}</h1>
                </div>
            </div>
            <div class="row">
                <div class="columns">
                    <div class="row">
                        <div class="columns">
                            <h2>{t 'Contact information' dict='order'}</h2>
                        </div>
                    </div>
                    <div class="row full-name">
                        <div class="columns small-4">{t 'Full name:' dict='order'}</div>
                        <div class="columns">{$order->firstname}</div>
                    </div>
                    <div class="row phone">
                        <div class="columns small-4">{t 'Phone:' dict='order'}</div>
                        <div class="columns">{$order->phone}</div>
                    </div>
                    <div class="row email">
                        <div class="columns small-4">{t 'Email:' dict='order'}</div>
                        <div class="columns">{$order->email}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns">
                    {include "checkout/_address_view.tpl" uri='checkout:shipping'}
                </div>
            </div>
        </section>
    </form>
{/block}