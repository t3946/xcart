{extends  $.request->getIsAjax() ? "ajax.tpl" : "cart/base.tpl"}
{block 'content'}
{set $isCartEmpty = $.app->cart->getIsEmpty()}
{add $site = $.getSite}
{add $site_currency = $site->getCurrency()}
<section class="cart-page cart_shipping-page">
    <div class="row head_line">
        <div class="columns small-6 medium-3">
            <div class="b-back">
                <a href="/" class="button yellow-white waves waves-orange waves-effect">
                    {t 'Shop more'}
                </a>
            </div>
        </div>

        <div class="columns small-12 medium-6 flex-container align-center align-middle head-line__header-column {if $isCartEmpty}align-self-middle{/if} small-order-2 medium-order-1">
            {if $isCartEmpty}
                <h2 class="text-center margin-0">{t 'Your shopping cart is empty'}</h2>
            {else}
                <h2 class="cart-number-header margin-0">{t 'Shopping Cart #'} {$.app->cart->getCartNumber()}</h2>
            {/if}
        </div>

        <div class="columns small-6 medium-3 small-order-1 medium-order-2">
            {if !$isCartEmpty}
                <div class="b-next">
                    <a href="{$.call.Modules.Order.Helpers.OrderHelper::getCheckoutUrl()}" class="button yellow waves waves-orange waves-effect">
                        {t 'Checkout'}
                    </a>
                </div>
            {/if}
        </div>
    </div>
    <div class="row">
        <div class="columns large-12">
            {foreach $.app->cart->getItemsGroupedBy() as $gi => $group}
            {set $items = $group.items}
            {set $warehouse  = $.get_warehouse($gi) }

            <div class="warehouse_products">
                <div class="shipped_from">
                    {t 'The items below will be shipped from warehouse in'} {$warehouse->m_city},
                    {if $config.show_full_state_country === 'Y'}{$warehouse->state_model}{else}{$warehouse->m_state}{/if},
                    {if $config.show_full_state_country === 'Y'}{$warehouse->country_model}{else}{$warehouse->m_country}{/if}
                </div>

                <div class="table">
                    <div class="table-row table-head show-for-large">
                        <div class="table-column image-name">
                            {t 'Item name'  }
                        </div>

                        <div class="table-column price">
                            {t 'Price'  }
                        </div>

                        <div class="table-column quantity">
                            {t 'Quantity'  }
                        </div>
                        <div class="table-column x">
                            &nbsp;
                        </div>
                        <div class="table-column extended">
                            {t 'Extended'  }
                        </div>

                        {*<div class="table-column remove">*}
                            {*{t 'Remove'  }*}
                        {*</div>*}
                    </div>
                    <div class="table-body">
                        {foreach $items as $key=>$position}
                        <div class="table-row"
                             data-key="{$key}"
                             data-wh="{$gi}"
                             data-product='{$position->object->productid}'
                             data-quantity="{$position->quantity}"
                             data-price="{$position->object->getFrontendPrice($position->quantity)}"
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
                                            {t 'SKU'  }:
                                            {$position->object->productcode}
                                        </div>
                                    </div>

                                    {if $position->data}
                                        <div class="options">
                                            {include '_parts/_options.tpl' options=$position->data}
                                        </div>
                                    {/if}

                                </div>

                                <div class="close-wide-screen show-for-large">
                                    <a href="{url 'cart:delete' key=$key}" title="{t 'Delete' }" class="icon cart_remove" onclick="loader.load(this)">
                                        {include 'cart/_close_icon.tpl'}
                                    </a>
                                </div>



                                <div class="table-column price show-for-large format_price">
                                    {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} <span class="price" var-price>{$site_currency->getCurrencyFormat($position->object->getFrontendPrice($position->quantity))}</span>{if $site_currency->after}&nbsp;{$site_currency}{/if}
                                </div>

                                <div class="table-wrapper quantity-extended">
                                    <div class="close-wide-screen show-for-medium hide-for-large">
                                        <a href="{url 'cart:delete' key=$key}" title="{t 'Delete' }" class="icon cart_remove" onclick="loader.load(this)">
                                            {include 'cart/_close_icon.tpl'}
                                        </a>
                                    </div>

                                    <div class="table-column quantity">
                                        <div class="inline-block">
                                            {include "product/parts/_quantity_group.tpl" model=$position->object quantity=$position->quantity btn_class='quantity-group-btn__checkout' group_class='quantity-group__checkout'}
                                        </div>

                                    </div>

                                    <div class="table-column x">x</div>

                                    <div class="table-column extended format_price">
                                        <span class="show-for-large">
                                            {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} <span class="price" var-price-extended>{$site_currency->getCurrencyFormat($position->getPrice())}</span>{if $site_currency->after}&nbsp;{$site_currency}{/if}
                                        </span>
                                        <span class="hide-for-large">
                                            {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} <span class="price" var-price-extended>{$site_currency->getCurrencyFormat($position->object->getFrontendPrice($position->quantity))}</span>{if $site_currency->after}&nbsp;{$site_currency}{/if}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="table-column remove hide-for-medium">
                                <a href="{url 'cart:delete' key=$key}" title="{t 'Delete' }" class="icon cart_remove" onclick="loader.load(this)">
                                    {include 'cart/_close_icon.tpl'}
                                </a>
                            </div>

                        </div>
                        {/foreach}
                    </div>
                </div>

                <div class="warehouse_subtotal wh_{$gi}"
                     data-wh="{$gi}"
                     data-minamount="{$warehouse->getMinimalAmount()}"
                >
                    <div class="table">
                        <div class="table-body">

                            <div class="table-row">
                                <div class="table-column auto from">
                                    {$warehouse->m_city},
                                    {if $config.show_full_state_country === 'Y'}{$warehouse->state_model}{else}{$warehouse->m_state}{/if},
                                    {if $config.show_full_state_country === 'Y'}{$warehouse->country_model}{else}{$warehouse->m_country}{/if}
                                    <b>{t 'warehouse subtotal'}</b>:
                                </div>
                                <div class="table-column extended_remove format_price">
                                    {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} <span class="wh_{$gi}_subtotal subtotal" var-group-subtotal>{$site_currency->getCurrencyFormat($group.subtotal)}</span>{if $site_currency->after}&nbsp;{$site_currency}{/if}
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="errors">
                        {if $warehouse->getMinimalAmount()}
                        {p_label hide=$warehouse->checkMinimalAmount($group.subtotal) type="minimal-amount"}
                            {t 'The minimum order amount for this product line is'} {$warehouse->getMinimalAmount()|site_currency}
                        {/p_label}
                        {/if}
                        {set $only_one_country = $warehouse->getShippingOnlyOneCountry()}
                        {if $only_one_country}
                            {p_label cls="err fill" type="last-items"}
                                {t 'This product line can only be shipped to a'} {$only_one_country} {t 'address.'}
                            {/p_label}
                        {/if}
                    </div>
                </div>
                {/foreach}
            </div>

            <div class="hr"></div>

            {if !$isCartEmpty}

                <div class="memo_subtotal">
                    <div class="grand-subtotal">
                        {t 'Subtotal'}:
                        <div class="subtotal">
                            {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} <span class="cart_subtotal" var-cart-subtotal>{$site_currency->getCurrencyFormat($total)}</span>{if $site_currency->after}&nbsp;{$site_currency}{/if}
                        </div>
                    </div>

                    <div class="memo">
                        {t "Your merchandise subtotal does not include shipping charges and taxes, which will be reflected on the 'order review' page."}
                    </div>
                </div>

                <div class="bottom_line">
                    <div class="b-next">
                        <a href="{$.call.Modules.Order.Helpers.OrderHelper::getCheckoutUrl()}" class="button yellow waves waves-orange waves-effect">
                            {t 'Checkout'}
                        </a>
                    </div>

                    <div class="b-back">
                        <a href="/" class="button yellow-white waves waves-orange waves-effect">
                            {t 'Shop more'}
                        </a>
                    </div>
                </div>
            {/if}
        </div>
    </div>
</section>
{/block}

{block 'search-menu'}{/block}

{block 'js'}
    {foreach $.app->cart->getItems() as $gi => $item}
        {set $pids[] = $item->getObject()->productid}
    {/foreach}
{/block}