<div class="prices__container">
    <div class="price-section">
        {set $subtotal_hide = ($model->list_price > $model->getFrontendPrice())}
        {set $price_safe = ($model->list_price - $model->getFrontendPrice())}
        {set $has_discount = $model->list_price > $model->getFrontendPrice($model->min_amount)}

        <div class="product-quantity">
            <div class="column small-12">
                <div class="row m-0 table__prices table__prices--top product-quantity-row__title">
                    <div class="title col-4 product-quantity-title">{t 'Unit Price'}</div>
                    <div class="title col-4 product-quantity-title">{t 'Quantity'}</div>
                    {if !$model->isOutOfStockFrontend()}
                        <div class="title col-4 product-quantity-title">{t 'Subtotal'}</div>
                    {/if}
                </div>

                <div class="d-flex align-items-center table__prices table__prices--top {if $has_discount}product-quantity-row__price_discount{else}product-quantity-row__price{/if} text-center">
                    <div class="product-table-prices_price-column column-price col-4">
                        <div class="value product-quantity-one-price d-flex justify-content-center {if $has_discount}product-quantity-one-price__discount{/if}">
                            <span>
                                {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}
                            </span>
                            <span class="price ms-1" var-price>
                                {$site_currency->getCurrencyFormat($model->getFrontendPrice($model->min_amount))}
                            </span>

                            {if $site_currency->after}
                                <span class="ms-1">
                                    {$site_currency}
                                </span>
                            {/if}
                        </div>
                        {if $has_discount}
                            <div class="value product-quantity-old-price">
                                {$site_currency->symbol_prefix}
                                <span>
                                    {if !$site_currency->after}
                                        {$site_currency}
                                    {/if}
                                </span>
                                <span class="price">
                                    {$model->list_price}
                                </span>
                                <span>
                                    {if $site_currency->after}
                                        {$site_currency}
                                    {/if}
                                </span>
                            </div>
                        {/if}
                    </div>

                    <div class="quantity col-4">
                        <div class="value d-flex justify-content-center">
                            {if !$model->isOutOfStockFrontend()}
                                {include "product/parts/_quantity_group.tpl"}
                            {else}
                                {t 'Out of stock'}
                            {/if}
                        </div>
                    </div>

                    {if !$model->isOutOfStockFrontend()}
                        <div class="product-table-prices_price-column column-extended col-4">
                            <div class="product-quantity-extended-price">
                                {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}
                                <span class="ms-1 price" var-price-extended>{$site_currency->getCurrencyFormat($model->getFrontendPrice($model->min_amount) * $model->min_amount)}</span>&nbsp;{if $site_currency->after}{$site_currency}{/if}
                            </div>
                            {if $model->list_price > $model->getFrontendPrice($model->min_amount)}
                                <div class="value product-quantity-old-price">
                                    {$site_currency->symbol_prefix}
                                    <span>
                                        {if !$site_currency->after}
                                            {$site_currency}
                                        {/if}
                                    </span>
                                    <span class="price product-quantity-old-price">{number_format($model->list_price * $model->min_amount, 2, '.', ' ')}</span>
                                    <span>
                                        {if $site_currency->after}
                                            {$site_currency}
                                        {/if}
                                    </span>
                                </div>
                            {/if}
                        </div>
                    {/if}
                </div>

                {if !$model->isOutOfStockFrontend()}
                    <div class="table__prices table__prices--down price-row-width">
                        {set $max_show_rows = 2}
                        {set $showed_rows = 0}
                        {foreach $model->getPrices() as $quantity => $price last=$last index=$index}
                            {if $quantity == 1}
                                {set $discount_base = $price}
                                {continue}
                            {/if}

                            {if $last_quantity!}
                                {set $max_q = ($quantity > $model->avail) ? $model->avail : $quantity - 1}
                                {set $ql = ($max_q == $last_quantity) ? $last_quantity : "{$last_quantity} - {$max_q}"}
                                {set $discount = round(($discount_base - $last_price) / $discount_base * 100)}

                                {if $showed_rows < $max_show_rows}
                                    {set $hidden = $last_quantity <= $model->min_amount}
                                    {if $hidden === false}
                                        {set $showed_rows = $showed_rows + 1}
                                    {/if}
                                {else}
                                    {set $hidden = true}
                                {/if}

                                {include "product/price/_price_table_row.tpl" discount = $discount hidden=$hidden quantity=$last_quantity price=$last_price quantity_line = $ql}
                            {/if}

                            {if $quantity > $model->avail}{break}{/if}

                            {if $last}
                                {set $discount = round(($discount_base - $price) / $discount_base * 100)}
                                {include "product/price/_price_table_row.tpl" discount = $discount hidden=$index > 2 quantity=$quantity price=$price quantity_line = "{$quantity}+"}
                            {/if}

                            {set $last_quantity = $quantity}
                            {set $last_price = $price}
                        {/foreach}
                    </div>
                {/if}
            </div>
        </div>
    </div>

    <div class="button-section">
        {if !$model->isOutOfStockFrontend()}
            <div class="col-12 px-md-0">
                {if $form}
                    {include "product/parts/_options.tpl" form=$form}
                {/if}
                <div class="jackpot">
                    {t 'Congratulations! You got a great price!'}
                </div>
                <div class="cart_add add-product" data-form-id="{if $form}{$form->getFormId()}{/if}">
                    {include "product/parts/_add_to_cart.tpl" type='product' noAccount=true moder=$model }
                </div>
            </div>
        {/if}
    </div>

    {if $model->isGroupChild()}
        {set $parent = $model->parent}
        {if $parent}
            <div class="link__group_root">
                <a href="{$parent->getAbsoluteUrl()}" title="{t 'See full'} {$parent->getFrontendName()} {t 'product line'}">
                    {t 'Click here to see full product line'}
                </a>
            </div>
        {/if}
    {/if}

    {if $model->isOutOfStockFrontend()}
        <div class="notify-me-stock">

            <a class="notify-me grey-border text-decoration-none">
                <span>{t 'Notify me when product is in stock'}</span>
            </a>
            <div class="product-page-add-to-list-btn out-of-stock" data-out-of-stock="{$model->r_avail === 0 ? '1' : '0'}"></div>


        </div>
    {/if}

    <div class="mmodal-hide">
        <div class="select-quantity"></div>
    </div>

    <div class="mmodal-hide">
        <div class="notify_stock"></div>
    </div>
</div>
