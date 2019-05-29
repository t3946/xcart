<div class="prices__container">

    <div class="row align-justify">

        <div class="price-section columns small-12 medium-5 ml-12">
            {set $subtotal_hide = ($model->list_price > $model->getFrontendPrice())}
            {set $price_safe = ($model->list_price - $model->getFrontendPrice())}
            <div class="row price-info-block">
                <div class="columns shrink price-value-text medium-12">
                    {$site_currency->symbol_prefix}{$site_currency} <span class="price">{$site_currency->getCurrencyFormat($model->getFrontendPrice())}</span>
                </div>
                {if $subtotal_hide}
                    <div class="columns shrink save-info-text">
                        Save {$site_currency->symbol}<span class="price">{$site_currency->getCurrencyFormat($price_safe)}</span>
                    </div>
                    <div class="columns shrink orig-info-text">
                        Orig. {$site_currency->symbol}<span class="price">{$site_currency->getCurrencyFormat($model->list_price)}</span>
                    </div>
                {/if}
            </div>
        </div>

        <div class="button-section columns small-12 medium-6 ml-12">
            {if !$model->isOutOfStock()}
                <div class="row">
                    <div class="columns small-12">
                        {if $form}
                        {include "product/parts/_options.tpl" form=$form}
                        {/if}
                        <div class="cart_add add-product" data-form-id="{if $form}{$form->getFormId()}{/if}">
                            {include "product/parts/_number_button.tpl"}
                            <a class="add button yellow wait-button">
                                <span class="text">
                                    Add to cart
                                </span>
                                <span class="wait-text">
                                    Added
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            {/if}
        </div>
    </div>

    {if $model->isOutOfStock()}
        <div class="notify-me-stock">

            <a class="notify-me grey-border">
                <span>Notify me when product is in stock</span>
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

