{extends "checkout/base.tpl"}
{block 'content'}

    {raw $checkoutReviewForm->renderBegin([
        'action' => $.app->router->url('checkout:review'),
        'method' => 'POST',
        'class' => 'checkout-review-form container',
        'enctype' => 'multipart/form-data'
    ])}
    {set $fieldsets =  $checkoutReviewForm->createFieldsets()}
        {if $showAllForm}
            <section class="checkout-po container">
                <div class="row">
                    <div class=" col-12">
                        <div class="options">
                            <h2 class="title top-title default-form-header">{t 'Purchase Order Details'}</h2>
                        </div>
                    </div>
                    <div class="">
                        <div class="row">
                            <div class="column col-12 col-lg-6 large-offset-6 large-collapse-left text-center large-text-left">
                                <div class="mandatory">
                                    {t 'The fields marked with'} <span class="required">*</span> {t 'are mandatory.'}
                                </div>
                            </div>
                        </div>

                        {foreach $fieldsets['order_details'] as $field}
                            {raw $field->render()}
                        {/foreach}
                    </div>
                </div>
                <div class="row show-for-large">
                    <div class="col-12 ">
                        <div class="hr"></div>
                    </div>
                </div>
                <div class="row">
                    <div class=" col-12">
                        <div class="options">
                            <h2 class="title default-form-header">{t 'Purchasing Manager'}</h2>
                        </div>
                    </div>
                    <div>
                        {foreach $fieldsets['purchasing_manager'] as $field}
                            {raw $field->render()}
                        {/foreach}
                    </div>
                </div>
                <div class="row show-for-large">
                    <div class="col-12 ">
                        <div class="hr"></div>
                    </div>
                </div>
                <div class="row">
                    <div class=" col-12">
                        <div class="options">
                            <h2 class="title default-form-header">{t 'Accounts Payable'}</h2>
                        </div>
                    </div>
                    <div>
                        {foreach $fieldsets['accounts_payable'] as $field}
                            {raw $field->render()}
                        {/foreach}
                    </div>
                </div>
            </section>
        {/if}

        <div class="row align-center hide-for-medium additional-button-mobile">
            <div class="column col-12">
                <div class="buttons text-center">
                    <button type="submit" class="button submit yellow waves waves-orange waves-effect">
                        {t 'Submit order'}
                    </button>
                </div>
            </div>
        </div>

        {include "checkout/_review_info.tpl" order = $order}

        <section style="background-color: #ffffff;" class="customer-notes p-0">
            <div class="row align-center">
                <div class="  col-12">
                    {foreach $fieldsets['notes'] as $field}
                        {raw $field->render()}
                    {/foreach}
                </div>
            </div>
        </section>

        <section class="submit-order">
            <div class="row align-center">
                <div class="column col-12">
                    <div class="buttons text-center">
                        <button type="submit" class="button submit yellow waves waves-orange waves-effect">
                            {t 'Submit order'}
                        </button>
                    </div>
                </div>
            </div>
            <div class="row align-center">
                <div class="column col-12">
                    <div class="submit-notes text-center submit-order-comment">
                        {if $order->payment_method_model->submit_hint}
                            {$order->payment_method_model->submit_hint}
                        {else}
                        {t 'Submit your order and get transferred to a credit card payment system.'}
                        {/if}
                    </div>
                </div>
            </div>
        </section>
    {raw $checkoutReviewForm->renderEnd()}
{/block}