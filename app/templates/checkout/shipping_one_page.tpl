{extends "checkout/base_one_page.tpl"}
{block 'content'}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    {raw $checkout_form->renderBegin([
    'action' => $.app->router->url('checkout:shipping'),
    'method' => 'POST',
    'class' => 'checkout-shipping-form'
    ])}
    <section
            class="checkout-shipping checkout-page"
            data-product-removed="{t 'The selected product has been removed successfully' }"
    >
        <div class="row">
            <div class="columns small-12 large-4 checkout-left-column">
                {* shipping address form *}
                {* shipping address form -- header *}
                <div class="options">
                    <h2 class="title checkout-second-header checkout__second-header text-center large-text-left checkout__shipping-header">{t 'Shipping Address' }</h2>
                </div>
                <div class="checkout-mandatory checkout__mandatory text-center large-text-left">
                    {t 'The fields marked with' }
                    <span class="mandatory-star">*</span> {t 'are mandatory.' }
                </div>
                {* shipping address form -- fields *}
                {set $fieldsets = $checkout_form->createFieldsets()}
                {foreach array_slice($fieldsets['shipping'], 0, 3) as $field}
                    {raw $field->render()}
                {/foreach}
                <div class="checkout-shipping-other-fields">
                    {foreach array_slice($fieldsets['shipping'], 3) as $field}
                        {if $field->getName() === 's_address_2' }{/if}
                        {raw $field->render()}
                    {/foreach}
                </div>

                {* contact information form *}
                {* contact information form -- header *}
                <div class="contact-options">
                    <h2 class="title text-center checkout__second-header checkout-second-header large-text-left">{t 'Contact Information' }</h2>
                </div>
                {* contact information form -- fields *}
                {foreach $fieldsets['contact'] as $field}
                    {raw $field->render()}
                {/foreach}
            </div>
            <div class="columns small-12 large-8">
                <h2 class="title checkout-second-header checkout__second-header text-center large-text-left checkout__delivery-methods-header">{t 'Delivery Methods' }</h2>
                {* distributor carts *}
                {foreach $.app->cart->getItemsGroupedBy() as $gi => $group}
                    {set $items = $group.items}
                    {set $warehouse = $.get_warehouse($gi) }
                    <div class="warehouse_products">
                        <div class="distributor-cart">
                            <div class="cart-table-caption checkout__cart-table-caption">
                                <span class="cart-show-switcher cart-show-switcher_text">{t 'The items'}</span> {t 'below will be shipped from warehouse in'} {$warehouse->m_city},
                                {if $config.show_full_state_country === 'Y'}{$warehouse->state_model}{else}{$warehouse->m_state}{/if},
                                {if $config.show_full_state_country === 'Y'}{$warehouse->country_model}{else}{$warehouse->m_country}{/if}
                                <span class="cart__switcher-button switcher-button switcher-button_product-list">
                                    <svg class="icon switcher-button-icon switcher-button-icon-plus"><use xlink:href="https://dev1.test.artistsupplysource.com/static/frontend/dist/images/icons/sprite.svg#switcher-plus"></use></svg>
                                    <svg class="icon switcher-button-icon switcher-button-icon-minus"><use xlink:href="https://dev1.test.artistsupplysource.com/static/frontend/dist/images/icons/sprite.svg#switcher-minus"></use></svg>
                                </span>
                            </div>
                            <div class="table cart-table_checkout">
                                <div class="cart-table-row cart-table-head cart-table-row__head table-head show-for-large">
                                    <div class="table-column cart-column-image"></div>

                                    <div class="table-column grid-title cart-column-name cart-column-name__header">{t 'Item name / SKU' }</div>

                                    <div class="table-column cart-column-remove"></div>

                                    <div class="table-column grid-quantity cart-column-quantity">{t 'Quantity' }</div>

                                    <div class="table-column cart-column-multiply-sign"></div>

                                    <div class="table-column grid-price cart-column-price">{t 'Unit price' }</div>
                                </div>
                                <style>
                                    @media (min-width: 1024px) {
                                        .grid-quantity {
                                            grid-area: grid-quantity;
                                        }

                                        .cart-table-head {
                                            display: grid;
                                            grid-template-areas: "cell1 grid-title cell3 grid-quantity cell4 grid-price";
                                            grid-template-columns: 100px 1fr 111px 95px 66px 100px;
                                            grid-column-gap: 5px;
                                        }

                                        .grid-price {
                                            text-align: right;
                                        }
                                    }
                                </style>

                                <div class="table-body">
                                    {foreach $items as $key=>$position}
                                        <div
                                                class="cart-table-row cart-table-row_product cart-table-row_checkout"
                                                data-key="{$key}"
                                                data-wh="{$gi}"
                                                data-product='{$position->object->productid}'
                                                data-quantity="{$position->quantity}"
                                                data-price="{$position->object->getFrontendPrice($position->quantity)}"
                                                data-prices='{$position->object->getPrices()|json_encode}'
                                                data-cart-action="{url 'cart:quantity:set:post' key=$key}"
                                        >
                                            <div class="grid-image table-column cart-column-image image">
                                                {include 'catalog/parts/_item_image.tpl' model=$position->object class='cart-item-image'}
                                            </div>

                                            <div class="grid-title table-column cart-column-name">
                                                <a class="cart-item-title-link" href="{$position->object->getAbsoluteUrl()}">
                                                    {$position->object}
                                                </a>

                                                <div class="cart-item-sku show-for-medium">
                                                    {t 'SKU' }:
                                                    {$position->object->productcode}
                                                </div>

                                                {if $position->data}
                                                    <div class="options">
                                                        {include '_parts/_options.tpl' options=$position->data}
                                                    </div>
                                                {/if}

                                            </div>

                                            <div class="grid-counter table-wrapper cart-column-quantity quantity-extended">
                                                <div class="table-column quantity">
                                                    <div class="inline-block">
                                                        <div class="quantity-group">
                                                            <a href="{url 'cart:quantity:dec' key=$key}" class="btn active dec quantity-group-dec">-</a>
                                                            <input type="number" name="quantity"
                                                                   min="{$position->object->min_amount}"
                                                                   max="{$position->object->avail}"
                                                                   step="{if $position->object->mult_order_quantity == 'Y'}{$position->object->min_amount}{else}1{/if}"
                                                                   value="{$position->quantity}">
                                                            <a href="{url 'cart:quantity:inc' key=$key}" class="btn active inc quantity-group-inc">+</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="grid-multiplier table-column cart-column-multiply-sign">x</div>

                                            <div class="grid-price table-column cart-column-price price format_price">
                                                {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}
                                                <span class="price" var-price>{$site_currency->getCurrencyFormat($position->object->getFrontendPrice($position->quantity))}</span>{if $site_currency->after}&nbsp;{$site_currency}{/if}
                                            </div>

                                            <div class="grid-remove table-column remove">
                                                <a href="{url 'cart:delete' key=$key}" title="{t 'Delete' }" class="icon cart-remove-item-button">
                                                    <svg class="cart-remove-icon">
                                                        <use xlink:href="https://dev1.test.artistsupplysource.com/static/frontend/dist/images/icons/sprite.svg#cross"></use>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    {/foreach}
                                </div>
                            </div>
                            {include 'checkout/shipping_methods_one_page.tpl' silent=true}
                        </div>
                        <div class="warehouse_subtotal wh_{$gi}" data-wh="{$gi}" data-minamount="{$warehouse->getMinimalAmount()}">
                            <div class="table">
                                <div class="table-body">
                                    <div class="cart-table-row cart-table-row_subtotal">
                                        <div class="table-column extended_remove format_price">
                                            {t 'Subtotal' }: {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}
                                            <span class="wh_{$gi}_subtotal subtotal" var-group-subtotal>{$site_currency->getCurrencyFormat($group.subtotal)}</span>{if $site_currency->after}&nbsp;{$site_currency}{/if}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {/foreach}
                <div class="order-total">
                    <div class="total">
                        <span class="sum-info-label">{t 'Total' }:</span>
                        <span class="sum">
                            {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}
                            <span class="price cart_subtotal">{$site_currency->getCurrencyFormat($order->subtotal)}</span>
                            {if $site_currency->after}&nbsp;{$site_currency}{/if}
                        </span>
                    </div>
                    <div class="shipping-total">
                        <span class="sum-info-label">{t 'Total Shipping Cost' }:</span>
                        <span class="sum">{$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}&nbsp
                            <span class="price">{$site_currency->getCurrencyFormat($order->shipping_cost)}</span>
                            {if $site_currency->after}&nbsp;{$site_currency}{/if}
                        </span>
                    </div>
                    <div class="grand-total order-total__grand">
                        <span class="label">{t 'Grand Total' }</span>
                        <span class="sum">
                            {t 'Sales Tax' }: {$site_currency->symbol_prefix} 1.80
                            {t 'VAT Tax' }: {$site_currency->symbol_prefix} 1.80
                            {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}&nbsp
                            <span class="price">{$site_currency->getCurrencyFormat($order->total)}</span>
                            {if $site_currency->after}&nbsp;{$site_currency}{/if}
                        </span>
                    </div>
                    {if $hst}
                        <div>
                            <span class="label">{t 'Including 13% HST' }</span>
                            <span class="sum">
                                {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}&nbsp
                                <span class="price">{$site_currency->getCurrencyFormat($order->tax)}</span>
                                {if $site_currency->after}&nbsp;{$site_currency}{/if}
                            </span>
                        </div>
                    {/if}
                </div>
            </div>
        </div>

        <div class="row align-center">
            <div class="column small-12">
                {include 'checkout/payment_methods_one_page.tpl'}
            </div>
        </div>

        <div class="row checkout-customer-notes__row checkout-customer-notes-row">
            <div class="column small-12 medium-6 large-4">
                <h2 class="customer-notes-header">Customers notes</h2>
            </div>
            <div class="column small-12 medium-6 large-8">
                <textarea name="customer_notes" class="checkout-customer-notes" placeholder="Put order related instructions here"></textarea>
            </div>
        </div>

        <div class="row align-center">
            <div class="column show-for-large large-4"></div>
            <div class="column small-12 large-8">
                <div class="buttons checkout-form__submit-button text-center">
                    <button type="submit" class="button submit yellow waves waves-orange waves-effect submit_big">Submit order</button>
                </div>
            </div>
        </div>
    </section>
    {raw $checkout_form->renderEnd()}
{/block}

{block 'js'}
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQERMkixIWZNodbqoI5vFYt7IxuGQGdpk&libraries=places&language=en" defer></script>
{/block}