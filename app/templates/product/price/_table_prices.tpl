<div class="prices__container">

    <div class="row align-justify">

        <div class="price-section columns small-12">
            {set $subtotal_hide = ($model->list_price > $model->getFrontendPrice())}
            {set $price_safe = ($model->list_price - $model->getFrontendPrice())}
            <div class="price__quantity">
                <div class="column small-12">
                        <div class="table table__prices table__prices--top">
                            <div class="column price">
                                <div class="title">{t 'Unit Price'}</div>
                                <div class="value">
                                    {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} <span class="price" var-price>{$site_currency->getCurrencyFormat($model->getFrontendPrice($model->min_amount))}</span>&nbsp;{if $site_currency->after}{$site_currency}{/if}
                                </div>
                            </div>

                            <div class="column quantity">
                                <div class="title">{t 'Quantity'}</div>
                                <div class="value">

                                    {if !$model->isOutOfStockFrontend()}
                                        {include "product/parts/_quantity_group.tpl"}
                                    {else}
                                        {t 'Out of stock'}
                                    {/if}
                                </div>
                            </div>

                            {if !$model->isOutOfStockFrontend()}
                                <div class="column extended">
                                    <div class="title">{t 'Subtotal'}</div>
                                    <div class="value">
                                        {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} <span class="price" var-price-extended>{$site_currency->getCurrencyFormat($model->getFrontendPrice($model->min_amount) * $model->min_amount)}</span>&nbsp;{if $site_currency->after}{$site_currency}{/if}
                                    </div>
                                </div>


                            {else}

                                <div class="column notify auto">
                                    <div class="title"></div>
                                    <div class="value">

                                    </div>
                                </div>

                            {/if}
                        </div>
                    </div>
                {if !$model->isOutOfStockFrontend()}
                    <div class="column small-8 large-8 price-row-width xl-8">
                        <div class="table table__prices table__prices--down price-row-width">
                            {foreach $model->getPrices() as $quantity => $price last=$last index=$index}
                                {if $quantity == 1}
                                    {set $discount_base = $price}
                                    {continue}
                                {/if}

                                {if $last_quantity!}
                                    {set $max_q = ($quantity > $model->avail) ? $model->avail : $quantity - 1}
                                    {set $ql = ($max_q == $last_quantity) ? $last_quantity : "{$last_quantity} - {$max_q}"}
                                    {set $discount = round(($discount_base - $last_price) / $discount_base * 100)}

                                    {include "product/price/_price_table_row.tpl" discount = $discount hidden=$index > 2 quantity=$last_quantity price=$last_price quantity_line = $ql}
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

                    </div>
                    {if $index}
                        <div class="column small-4 discount_block" data-timer="{Modules\User\Helpers\DiscountHelper::getDiscountTime()}" data-minutes="{Modules\User\Helpers\DiscountHelper::getDiscountMinutes()}">
                        <div class="row" style="margin:0">
                            <div class="columns discount__title">{t 'Extra qty discount'}</div>
                        </div>
                        <div class="row discount__counter">
                            <div class="columns">
                                <div class="digit hours"></div>
                                <div class="label hours">{t 'hrs'}</div>
                            </div>
                            <div class="columns">
                                <span class="delimiter">:</span>
                            </div>
                            <div class="columns">
                                <div class="digit minutes"></div>
                                <div class="label minutes">{t 'min'}</div>
                            </div>
                            <div class="columns">
                                <span class="delimiter">:</span>
                            </div>
                            <div class="columns">
                                <div class="digit seconds"></div>
                                <div class="label seconds">{t 'sec'}</div>
                            </div>
                        </div>
                    </div>
                    {/if}
                {/if}
            </div>
        </div>
        <div class="button-section columns small-12">
            {if !$model->isOutOfStockFrontend()}
                <div class="row">
                    <div class="columns small-12">
                        {if $form}
                            {include "product/parts/_options.tpl" form=$form}
                        {/if}
                        <div class="jackpot">
                            {t 'Congratulations! You got a great price!'}
                        </div>
                        <div class="cart_add add-product" data-form-id="{if $form}{$form->getFormId()}{/if}">
                            {include "product/parts/_add_to_cart.tpl" type='product' noAccount=true }
                        </div>
                    </div>
                    <div class="column large-4 xl-4 hide-for-small show-for-medium auto">
                        <div class="subtotal_container {if !$subtotal_hide}hide{/if}" cont-subtotal>
                            <div class="safe-prices list-price ">
                                <div class="title">
                                    {t 'List Price'}:
                                </div>
                                <div class="value">
                                    {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} <span class="price" var-price-list>{$site_currency->getCurrencyFormat($model->list_price)}</span>{if $site_currency->after}{$site_currency}{/if}
                                </div>
                            </div>

                            <div class="safe-prices safe safe-per-item ">
                                <div class="title">
                                    {t 'Per item savings'}:
                                </div>
                                <div class="value">
                                    {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} <span class="price" var-price-perunit-safe>{$site_currency->getCurrencyFormat($price_safe)}</span>{if $site_currency->after}{$site_currency}{/if}
                                </div>
                            </div>

                            <div class="safe-prices safe total-safe ">
                                <div class="title">
                                    {t 'Total savings'}:
                                </div>
                                <div class="value">
                                    {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} <span class="price" var-price-safe>{$site_currency->getCurrencyFormat($price_safe)}</span>{if $site_currency->after}{$site_currency}{/if}
                                </div>
                            </div>
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
