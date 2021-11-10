<div class="prices__container">

    <div class="row align-justify">

        <div class="price-section columns small-12">
            {set $subtotal_hide = ($model->list_price > $model->getFrontendPrice())}
            {set $price_safe = ($model->list_price - $model->getFrontendPrice())}
            {set $has_discount = $model->list_price > $model->getFrontendPrice($model->min_amount)}

            <div class="product-quantity">
                <div class="column small-12">
                    <div class="table table__prices table__prices--top product-quantity-row__title">
                        <div class="title column small-4 product-quantity-title">{t 'Unit Price'}</div>
                        <div class="title column small-4 product-quantity-title">{t 'Quantity'}</div>
                        {if !$model->isOutOfStockFrontend()}
                            <div class="title column small-4 product-quantity-title">{t 'Subtotal'}</div>
                        {/if}
                    </div>

                    <div class="table table__prices table__prices--top {if $has_discount}product-quantity-row__price_discount{else}product-quantity-row__price{/if}">
                        <div class="column product-table-prices_price-column column-price small-4">
                            <div class="value product-quantity-one-price {if $has_discount}product-quantity-one-price__discount{/if}">
                                {$model->getFrontendPrice($model->min_amount)|site_currency:$site:'var-price'}
                            </div>
                            {if $has_discount}
                                <div class="value product-quantity-old-price">
                                    {$model->list_price|site_currency}
                                </div>
                            {/if}
                        </div>

                        <div class="column quantity small-4">
                            <div class="value">
                                {if !$model->isOutOfStockFrontend()}
                                    {include "product/parts/_quantity_group.tpl"}
                                {else}
                                    {t 'Out of stock'}
                                {/if}
                            </div>
                        </div>

                        {if !$model->isOutOfStockFrontend()}
                            <div class="column product-table-prices_price-column column-extended small-4">
                                <div class="product-quantity-extended-price">
                                    {set $estended_price = $model->getFrontendPrice($model->min_amount) * $model->min_amount}
                                    {$estended_price|site_currency:$site:'var-price-extended'}
                                </div>
                                {if $model->list_price > $model->getFrontendPrice($model->min_amount)}
                                    <div class="value product-quantity-old-price">
                                        {set $extended_price = $model->list_price * $model->min_amount}
                                        {$extended_price|site_currency}
                                    </div>
                                {/if}
                            </div>
                        {/if}
                    </div>

                    {if !$model->isOutOfStockFrontend()}
                        <div class="table table__prices table__prices--down price-row-width">
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
        <div class="button-section columns small-12">
            {if !$model->isOutOfStockFrontend()}
                <div class="row">
                    <div class="columns small-12">
                        {if $form}
                            {include "product/parts/_options.tpl" form=$form}
                        {/if}
                        {if $site.code !== 'RD'}
                            <div class="jackpot">
                                {t 'Congratulations! You got a great price!'}
                            </div>
                        {/if}
                        <div class="cart_add add-product" data-form-id="{if $form}{$form->getFormId()}{/if}">
                            {include "product/parts/_add_to_cart.tpl" type='product' noAccount=true }
                        </div>
                    </div>
                </div>
            {/if}
        </div>
    </div>

    {if $model->isOutOfStockFrontend()}
        <div class="notify-me-stock">

            <a class="notify-me grey-border">
                <span>{t 'Notify me when product is in stock'}</span>
            </a>

        </div>
    {/if}

    <div class="mmodal-hide">
        <div class="select-quantity"></div>
    </div>

    <div class="mmodal-hide">
        <div class="notify_stock"></div>
    </div>
</div>
