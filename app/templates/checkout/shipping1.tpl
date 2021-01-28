{extends "checkout/base.tpl"}
{block 'content'}
    {raw $shippingForm->renderBegin([
    'action' => $.app->router->url('checkout:shipping'),
    'method' => 'POST',
    'class' => 'checkout-shipping-form'
    ])}
    <section class="checkout-shipping">
        <div class="row">
            <div class="columns small-12 large-4">
                {* shipping address *}
                <div class="row">
                    <div class="columns small-12 large-6 large-collapse-right show-for-large">
                        <div class="options">
                            <h2 class="title">{t 'Shipping Address' }</h2>
                        </div>
                    </div>
                    <div class="small-12  large-6 columns text-center large-text-left large-collapse-left">
                        <div class="mandatory">
                            {t 'The fields marked with' } <span class="required">*</span> {t 'are mandatory.' }
                        </div>
                    </div>
                </div>

                {* shipping address -- fields *}
                <div class="row">
                    <div class="columns small-12">
                        {set $fieldsets =  $shippingForm->createFieldsets()}
                        {foreach $fieldsets['shipping'] as $field}
                            {raw $field->render()}
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="columns small-12 large-8"></div>
        </div>


        <div class="row">
            <div class="columns small-12">
                <div class="contact-options">
                    <h2 class="title">{t 'Contact Information' }</h2>
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

        <div class="row show-for-large">
            <div class="small-12 columns">
                <div class="hr"></div>
            </div>
        </div>

        <div class="row">
            <div class="columns small-12">
                <div class="subscription-options">
                    <h5 class="title">{t 'Privacy Policy' }</h5>
                    <div class="private-claim">
                        123
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-center">
            <div class="column small-12">
                <div class="buttons text-center">
                    <button type="submit" class="button submit yellow waves waves-orange waves-effect">
                        {t 'Submit' }
                    </button>
                </div>
            </div>
        </div>

        <div class="row align-center">
            <div class="column small-12">
                <div class="submit-notes text-center hint">
                    {t 'Submit and proceed to shipping & payment options.' }
                </div>
            </div>
        </div>

    </section>
    {raw $shippingForm->renderEnd()}
{/block}