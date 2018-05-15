{extends "checkout/base.tpl"}

{block 'content'}
    <form data-abide action="{url 'checkout:review'}" method="POST" class="checkout-review-form" enctype= "multipart/form-data">
        {if $order->payment_method->payment_method == 'Purchase Order'}
            {set $extra = $order->extra_model}
            <section class="checkout-po">
                <div class="row">
                    <div class="columns small-3">
                        <div class="options">
                            <h2 class="title">{t 'Purchase Order Details' dict='order'}</h2>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="row">
                            <div class="column small-offset-4">
                                <div class="mandatory">
                                    {t 'The fields marked with' dict='order'} <span class="required">*</span> {t 'are mandatory.' dict='order'}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-4 text-align--right">
                                <label for="purchase_order__number">{t 'PO number' dict='order'} <span class="required">*</span></label>
                                <div class="description">{t 'PO number or internal order code in your system' dict='order'}</div>
                            </div>
                            <div class="columns">
                                <input value="{$extra->purchase_order['po_number']}" id="purchase_order__number" required placeholder="{t '14031879' dict='order'}" name="PurchaseOrderForm[po_number]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-4 text-align--right">
                                <label for="purchase_order__company">{t 'Organization name' dict='order'} <span class="required">*</span></label>
                                <div class="description">{t 'The name of your organization' dict='order'}</div>
                            </div>
                            <div class="columns">
                                <input value="{$extra->purchase_order['company_name']}" id="purchase_order__company" required placeholder="{t 'Eureka Inc.' dict='order'}" name="PurchaseOrderForm[company_name]" type="text"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="small-12 columns">
                        <div class="hr"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-3">
                        <div class="options">
                            <h2 class="title">{t 'Purchasing Manager' dict='order'}</h2>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="row">
                            <div class="columns small-4 text-align--right">
                                <label for="purchase_order__name_of_purchaser">{t 'Full name' dict='order'} <span class="required">*</span></label>
                                <div class="description">{t 'Full name of the person placing the order' dict='order'}</div>
                            </div>
                            <div class="columns">
                                <input value="{$extra->purchase_order['name_of_purchaser']}" id="purchase_order__name_of_purchaser" required placeholder="{t 'Albert H. Einstein' dict='order'}" name="PurchaseOrderForm[name_of_purchaser]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-4 text-align--right">
                                <label for="purchase_order__phone">{t 'Phone' dict='order'} <span class="required">*</span></label>
                                <div class="description">{t 'Phone number of the person placing the order' dict='order'}</div>
                            </div>
                            <div class="columns">
                                <input value="{$extra->purchase_order['purchase_manager_phone']}" id="purchase_order__phone" required placeholder="{t '(609) 734-8000' dict='order'}" name="PurchaseOrderForm[purchase_manager_phone]" type="tel"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-4 text-align--right">
                                <label for="purchase_order__fax">{t 'Fax' dict='order'} <i>{t '(optional)' dict='order'}</i></label>
                                <div class="description">{t 'Fax number of the person placing the order' dict='order'}</div>
                            </div>
                            <div class="columns">
                                <input value="{$extra->purchase_order['purchase_manager_fax']}" id="purchase_order__fax" placeholder="{t '(609) 924-8399' dict='order'}" name="PurchaseOrderForm[purchase_manager_fax]" type="tel"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-4 text-align--right">
                                <label for="purchase_order__email">{t 'Email' dict='order'} <span class="required">*</span></label>
                                <div class="description">{t 'Email of the person placing the order' dict='order'}</div>
                            </div>
                            <div class="columns">
                                <input value="{$extra->purchase_order['purchase_manager_email']}" id="purchase_order__email" required placeholder="{t 'albert.einstein@gmail.com' dict='order'}" name="PurchaseOrderForm[purchase_manager_email]" type="email"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="small-12 columns">
                        <div class="hr"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-3">
                        <div class="options">
                            <h2 class="title">{t 'Accounts Payable' dict='order'}</h2>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="row">
                            <div class="columns small-4 text-align--right">
                                <label for="purchase_order__accounts_payable_full_name">{t 'Full name' dict='order'} <span class="required">*</span></label>
                                <div class="description">{t 'Full name of the person who will remit the payment' dict='order'}</div>
                            </div>
                            <div class="columns">
                                <input value="{$extra->purchase_order['accounts_payable_full_name']}" id="purchase_order__accounts_payable_full_name" required placeholder="{t 'Albert H. Einstein' dict='order'}" name="PurchaseOrderForm[accounts_payable_full_name]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-4 text-align--right">
                                <label for="purchase_order__accounts_payable_phone">{t 'Phone' dict='order'} <span class="required">*</span></label>
                                <div class="description">{t 'Phone number of the person who will remit the payment' dict='order'}</div>
                            </div>
                            <div class="columns">
                                <input value="{$extra->purchase_order['accounts_payable_phone']}" id="purchase_order__accounts_payable_phone" required placeholder="{t '(609) 734-8000' dict='order'}" name="PurchaseOrderForm[accounts_payable_phone]" type="tel"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-4 text-align--right">
                                <label for="purchase_order__accounts_payable_fax">{t 'Fax' dict='order'} <i>{t '(optional)' dict='order'}</i></label>
                                <div class="description">{t 'Fax number of the person who will remit the payment' dict='order'}</div>
                            </div>
                            <div class="columns">
                                <input value="{$extra->purchase_order['accounts_payable_fax']}" id="purchase_order__accounts_payable_fax" placeholder="{t '(609) 924-8399' dict='order'}" name="PurchaseOrderForm[accounts_payable_fax]" type="tel"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-4 text-align--right">
                                <label for="purchase_order__accounts_payable_email">{t 'Email' dict='order'} <span class="required">*</span></label>
                                <div class="description">{t 'Email of the person who will remit the payment' dict='order'}</div>
                            </div>
                            <div class="columns">
                                <input value="{$extra->purchase_order['accounts_payable_email']}" id="purchase_order__accounts_payable_email" required placeholder="{t 'albert.einstein@gmail.com' dict='order'}" name="PurchaseOrderForm[accounts_payable_email]" type="email"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-4">
                                <label for="purchase_order__file">{t 'Attach original PO' dict='order'} <i>{t '(optional)' dict='order'}</i></label>
                                <div class="description">{t 'Alternatively fax PO to (813) 944-4516' dict='order'}</div>
                            </div>
                            <div class="columns">
                                <input accept=".pdf" type="file" name="purchase_order_file" id="purchase_order__file" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        {/if}
        <section class="checkout-review">
            <div class="row align-center">
                <div class="column">
                    <h1 class="text-center">{t 'Product ordered' dict='order'}</h1>
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
                        {foreach $items as $item}
                            <div class="row order-table-body">
                                <div class="columns small-2 text-align--center sku">
                                    {$item->productcode}
                                </div>

                                <div class="columns small-5 item-name">
                                    {$item->product}
                                    {*options*}
                                </div>

                                <div class="columns text-align--center price">
                                    US$ <span class="price">{$item->price|number_format:2}</span>
                                </div>

                                <div class="columns small-1 text-align--center quantity">{$item->amount}</div>
                                <div class="columns extended">
                                    {set $extended = $item->amount * $item->price}
                                    US$ <span class="price">{$extended|number_format:2}</span>
                                </div>
                            </div>
                        {/foreach}
                        {if $order_group->shippingModel}
                            <div class="row group-shipping">
                                <div class="columns text-align--right">{t 'Shipping by' dict='order'} {$order_group->shippingModel->getFrontendName()}:</div>
                                <div class="columns small-2">US$ <span class="price">{$order_group->shipping_gross|number_format:2}</span></div>
                            </div>
                        {/if}
                        <div class="row group-total">
                            <div class="columns text-align--right">{t 'Subtotal:' dict='order'}</div>
                            <div class="columns small-2">US$ <span class="price">{$order_group->total_gross|number_format:2}</span></div>
                        </div>
                    </div>
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
                    <div class="columns">US$ <span class="price">{$order->subtotal|number_format:2}</span></div>
                </div>
                <div class="row total-shipping">
                    <div class="columns title small-offset-8 text-align--right">{t 'Total Shipping Cost:' dict='order'}</div>
                    <div class="columns value">US$ <span class="price">{$order->shipping_cost|number_format:2}</span></div>
                </div>
                <div class="row grand-total">
                    <div class="columns title small-offset-8 text-align--right">{t 'Grand Total:' dict='order'}</div>
                    <div class="columns value">US$ <span class="price">{$order->total|number_format:2}</span></div>
                </div>
            </div>

        </section>

        <section class="shipping-review">
            <div class="row align-center">
                <div class="columns small-12">
                    <h1 class="text-center">{t 'Shipping and Billing Address' dict='order'}</h1>
                </div>
            </div>
            <div class="row contact-info">
                <div class="columns small-6">
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
            <div class="row address">
                <div class="columns">
                    {include "checkout/_address_view_full.tpl" info=$shipping_address uri='checkout:shipping' header=$.t('Shipping Address','order')}
                </div>
                <div class="columns">
                    {include "checkout/_address_view_full.tpl" info=$billing_address uri='checkout:options' header=$.t('Billing Address','order')}
                </div>
            </div>
            <div class="row delivery">
                <div class="columns">
                    <div class="row">
                        <div class="columns">
                            <h2>{t 'Delivery methods' dict='order'}</h2>
                        </div>
                    </div>
                    {foreach $order->groups as $group}
                        {set $warehouse = $.get_warehouse($group->manufacturerid)}
                        {set $shipping_model = $group->shippingModel}
                        <div class="row delivery-method">
                            <div class="columns small-4">
                                <div class="row">
                                    <div class="column">{$warehouse->m_city}, {$warehouse->m_state}, {$warehouse->m_country}</div>
                                </div>
                                <div class="row">
                                    <div class="column">{t 'warehouse items:' dict='order'}</div>
                                </div>
                            </div>
                            {if $shipping_model}
                                <div class="columns">{$shipping_model->getFrontendName()} - {$shipping_model->shipping_time}</div>
                            {/if}
                        </div>
                    {/foreach}
                    <div class="row align-center">
                        <div class="columns small-12">
                            <a href="{url 'checkout:shipping'}" class="button yellow-white waves waves-orange waves-effect">{t 'Modify' dict='order'}</a>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="row">
                        <div class="columns">
                            <h2>{t 'Payment method' dict='order'}</h2>
                        </div>
                    </div>
                    <div class="row payment-method">
                        <div class="columns small-4">
                            {t 'Payment method:' dict='order'}
                        </div>
                        <div class="columns">{$order->payment_method->payment_method}</div>
                    </div>
                    <div class="row align-center">
                        <div class="columns small-12">
                            <a href="{url 'checkout:options'}" class="button yellow-white waves waves-orange waves-effect">{t 'Modify' dict='order'}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    <div class="hr"></div>
                </div>
            </div>
        </section>

        <section class="customer-notes">
            <div class="row">
                <div class="columns  small-4">
                    <h2>{t 'Customer notes' dict='order'}</h2>
                </div>
                <div class="columns">
                    <textarea name="customer_notes" placeholder="{t 'Put your order related instructions here' dict='order'}">
                        {$order->customer_notes}
                    </textarea>
                </div>
            </div>

        </section>

        <section class="submit-order">
            <div class="row align-center">
                <div class="column small-12">
                    <div class="buttons text-center">
                        <button type="submit" class="button yellow waves waves-orange waves-effect">{t 'Submit order' dict='order'}</button>
                    </div>
                </div>
            </div>
            <div class="row align-center">
                <div class="column small-12">
                    <div class="submit-notes text-center">
                        {t 'Submit your order and get transferred to a credit card payment system.' dict='order'}
                    </div>
                </div>
            </div>
        </section>

    </form>
{/block}