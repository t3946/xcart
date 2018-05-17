{extends "checkout/base.tpl"}

{block 'content'}
    <form data-abide action="{url 'checkout:shipping'}" method="POST" class="checkout-shipping-form">
        <section class="checkout-shipping">
            <div class="row show-for-large">
                <div class="columns small-12">
                    <h1>{t 'Shipping Cart' dict='order'}</h1>
                </div>
            </div>
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
                    <div class="registration">

                        {include 'checkout/_form_row.tpl' field=$shippingForm->getField('s_firstname')}
                        {include 'checkout/_form_row.tpl' field=$shippingForm->getField('s_company')}
                        {include 'checkout/_form_row.tpl' field=$shippingForm->getField('s_address')}
                        {include 'checkout/_form_row.tpl' field=$shippingForm->getField('s_address_2')}
                        {include 'checkout/_form_row.tpl' field=$shippingForm->getField('s_country')}
                        {include 'checkout/_form_row.tpl' field=$shippingForm->getField('s_zipcode')}
                        {include 'checkout/_form_row.tpl' field=$shippingForm->getField('s_state')}
                        {include 'checkout/_form_row.tpl' field=$shippingForm->getField('s_city')}

                    </div>
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
                    <div class="contact-information">

                        {include 'checkout/_form_row.tpl' field=$contactForm->getField('firstname')}

                        <div class="row">
                            <div class="column show-for-large small-12 large-2"></div>
                            <div class="column small-12 large-2 large-order-2">
                                {$contactForm->getField('phone')->renderErrors()}
                            </div>

                            <div class="column small-12 large-8 large-order-1">
                                <div class="row">
                                    <div class="small-12 large-6 columns large-text-right text-block">
                                        <div class="multiline">
                                            {$contactForm->getField('phone')->renderLabel()}

                                            <span class="hint">
                                                {$contactForm->getField('phone')->renderHint()}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="small-12 large-6 columns phone--container">

                                        {$contactForm->getField('phone')->renderInput()}

                                        <span class="phone_ext--container">
                                            <label class="display-inline hide-for-medium">X</label>
                                            <label class="display-inline show-for-medium">{t 'ext' dict='order'}</label>

                                            {$contactForm->getField('phone_ext')->renderInput()}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>




                        {include 'checkout/_form_row.tpl' field=$contactForm->getField('email')}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <div class="hr"></div>
                </div>
            </div>

            {*<div class="row">
                <div class="columns small-12 medium-6">
                    <div class="subscription-options">
                        <h2 class="title">{t 'Newsletter' dict='order'}</h2>
                    </div>
                </div>
                <div class="columns small-12 medium-6">
                    <div class="subscription-information">
                        <div class="row">
                            <div class="small-12 medium-6 columns">
                                <div>{t 'Artist Supply Source' dict='order'}</div>
                                <div><i>{t 'Specials on art supplies!' dict='order'}</i></div>
                            </div>
                            <div class="small-12 medium-6 columns">
                                <input type="checkbox" name="mailchimp_subscription[3ffb82f7e0]"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    <div class="hr"></div>
                </div>
            </div>*}
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
    </form>
{/block}