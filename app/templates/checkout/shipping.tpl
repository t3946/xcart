{extends "checkout/base.tpl"}

{block 'content'}
        {raw $shippingForm->renderBegin([
            'action' => $.app->router->url('checkout:shipping'),
            'method' => 'POST',
            'class' => 'checkout-shipping-form'
        ])}
        {set $fieldsets =  $shippingForm->createFieldsets()}

        <section class="checkout-shipping">
            <div class="row">
                <div class="columns small-12 large-6">
                    <div class="options">
                        <h2 class="title" >{t 'Shipping Address' dict='order'}</h2>
                    </div>
                </div>
                <div class="small-12  large-6 columns text-center large-text-left">
                    <div class="mandatory">
                        {t 'The fields marked with' dict='order'} <span class="required">*</span> {t 'are mandatory.' dict='order'}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12">
                        {foreach $fieldsets['shipping'] as $field}
                            {raw $field->render()}
                        {/foreach}
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    <div class="hr"></div>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12">
                    <div class="contact-options">
                        <h2 class="title">{t 'Contact Information' dict='order'}</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="columns small-12 ">
                        {foreach $fieldsets['contact'] as $field}
                            {raw $field->render()}
                        {/foreach}
                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <div class="hr"></div>
                </div>
            </div>

            <div class="row">
                <div class="columns small-12">
                    <div class="subscription-options">
                        <h2 class="title">{t 'Privacy Policy' dict='order'}</h2>
                        <div class="private-claim">
                            {t 'All your private data is confidential. We will never sell, exchange or market it in any way.' dict='order'}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-center">
                <div class="column small-12">
                    <div class="buttons text-center">
                        <button type="submit" class="button submit yellow waves waves-orange waves-effect">
                            {t 'Submit' dict='order'}
                        </button>
                    </div>
                </div>
            </div>

            <div class="row align-center">
                <div class="column small-12">
                    <div class="submit-notes text-center hint">
                        {t 'Submit and proceed to shipping & payment options.' dict='order'}
                    </div>
                </div>
            </div>

        </section>
    {raw $shippingForm->renderEnd()}
{/block}