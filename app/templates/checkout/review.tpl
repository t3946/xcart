{extends "checkout/base.tpl"}
{block 'content'}

    {raw $checkoutReviewForm->renderBegin([
        'action' => $.app->router->url('checkout:review'),
        'method' => 'POST',
        'class' => 'checkout-review-form',
        'enctype' => 'multipart/form-data'
    ])}
    {set $fieldsets =  $checkoutReviewForm->createFieldsets()}
        {if $showAllForm}
            <section class="checkout-po">
                <div class="row">
                    <div class="columns small-12">
                        <div class="options">
                            <h2 class="title top-title">{t 'Purchase Order Details' dict='order'}</h2>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="row">
                            <div class="column small-12 large-6 large-offset-6 large-collapse-left text-center large-text-left">
                                <div class="mandatory">
                                    {t 'The fields marked with' dict='order'} <span class="required">*</span> {t 'are mandatory.' dict='order'}
                                </div>
                            </div>
                        </div>

                        {foreach $fieldsets['order_details'] as $field}
                            {raw $field->render()}
                        {/foreach}
                    </div>
                </div>
                <div class="row show-for-large">
                    <div class="small-12 columns">
                        <div class="hr"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12">
                        <div class="options">
                            <h2 class="title">{t 'Purchasing Manager' dict='order'}</h2>
                        </div>
                    </div>
                    <div class="columns">
                        {foreach $fieldsets['purchasing_manager'] as $field}
                            {raw $field->render()}
                        {/foreach}
                    </div>
                </div>
                <div class="row show-for-large">
                    <div class="small-12 columns">
                        <div class="hr"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12">
                        <div class="options">
                            <h2 class="title">{t 'Accounts Payable' dict='order'}</h2>
                        </div>
                    </div>
                    <div class="columns">
                        {foreach $fieldsets['accounts_payable'] as $field}
                            {raw $field->render()}
                        {/foreach}
                    </div>
                </div>
            </section>
        {/if}

        <div class="row align-center hide-for-medium additional-button-mobile">
            <div class="column small-12">
                <div class="buttons text-center">
                    <button type="submit" class="button submit yellow waves waves-orange waves-effect">
                        {t 'Submit order' dict='order'}
                    </button>
                </div>
            </div>
        </div>

        {include "checkout/_review_info.tpl" order = $order}

        <section class="customer-notes">
            <div class="row align-center">
                <div class="columns  small-12">
                    {foreach $fieldsets['notes'] as $field}
                        {raw $field->render()}
                    {/foreach}
                </div>
            </div>
        </section>

        <section class="submit-order">
            <div class="row align-center">
                <div class="column small-12">
                    <div class="buttons text-center">
                        <button type="submit" class="button submit yellow waves waves-orange waves-effect">
                            {t 'Submit order' dict='order'}
                        </button>
                    </div>
                </div>
            </div>
            <div class="row align-center">
                <div class="column small-12">
                    <div class="submit-notes text-center submit-order-comment">
                        {t 'Submit your order and get transferred to a credit card payment system.' dict='order'}
                    </div>
                </div>
            </div>
        </section>
    {raw $checkoutReviewForm->renderEnd()}
{/block}