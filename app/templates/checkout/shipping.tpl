{extends "checkout/base.tpl"}

{block 'content'}
    <form data-abide action="{url 'checkout:shipping'}" method="POST" class="checkout-shipping-form">
        <section class="checkout-shipping">
            <div class="row">
                <div class="columns small-12 tet">
                    <h1>{t 'Shipping Cart' dict='order'}</h1>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12 medium-6">
                    <div class="options">
                        <h2 class="title" >{t 'Shipping Address' dict='order'}</h2>
                    </div>
                </div>
                <div class="small-12  medium-6 columns">
                    <div class="mandatory">
                        {t 'The fields marked with' dict='order'} <span class="required">*</span> {t 'are mandatory.' dict='order'}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12">
                    <div class="registration">

                        <div class="row">
                            <div class="small-6 columns medium-text-right">
                                <label for="registration__s_firstname">
                                    {t 'Full name' dict='order'}
                                    <span class="required">*</span>
                                </label>
                            </div>
                            <div class="small-12 medium-6 columns">
                                <input value="{$order->s_firstname}" id="registration__s_firstname" required placeholder="{t 'Albert H. Einstein' dict='order'}" name="ShippingAddressForm[s_firstname]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-12 medium-6 columns medium-text-right">
                                <label for="registration__s_company">{t 'Company' dict='order'}</label>
                                <i>{t '(optional)' dict='order'}</i>
                            </div>
                            <div class="small-12 medium-6 columns">
                                <input value="{$order->s_company}" id="registration__s_company" placeholder="{t 'Eureka Inc.' dict='order'}" name="ShippingAddressForm[s_company]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-12 medium-6 columns medium-text-right">
                                <label for="registration__s_address">
                                    {t 'Address' dict='order'}
                                    <span class="required">*</span>
                                </label>
                            </div>
                            <div class="small-12 medium-6 columns">
                                <input value="{$address[0]}" id="registration__s_address" required placeholder="{t '112 Mercer Street' dict='order'}" name="ShippingAddressForm[s_address]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-12 medium-6 columns medium-text-right">
                                <label for="registration__s_address_2">{t 'Address (line 2)' dict='order'}</label>
                            </div>
                            <div class="small-12 medium-6 columns">
                                <input value="{$address[1]}" id="registration__s_address_2" placeholder="{t 'Apt 1' dict='order'}" name="ShippingAddressForm[s_address_2]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-12 medium-6 columns medium-text-right">
                                <label for="registration__s_countryname">
                                    {t 'Country' dict='order'}
                                    <span class="required">*</span>
                                </label>
                            </div>
                            <div class="small-12 medium-6 columns">
                                <select id="registration__s_countryname" name="ShippingAddressForm[s_country]">
                                    {foreach $countries as $country}
                                        <option {if $order->s_country == $country.id}selected{/if} value="{raw $country.id}">{raw $country.text}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-12 medium-6 columns medium-text-right">
                                <label for="registration__s_zipcode">
                                    {t 'Zip/Postal Code' dict='order'}
                                    <span class="required">*</span>
                                </label>

                            </div>
                            <div class="small-12 medium-6 columns">
                                <input value="{$order->s_zipcode}" id="registration__s_zipcode" required placeholder="{t '08540' dict='order'}" name="ShippingAddressForm[s_zipcode]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-12 medium-6 columns medium-text-right">
                                <label for="registration__s_statename">
                                    {t 'State/Province' dict='order'}
                                    <span class="required">*</span>
                                </label>
                            </div>
                            <div class="small-12 medium-6 columns">
                                <input value="{$order->shipping_state}" id="registration__s_statename" required placeholder="{t 'New Jersey' dict='order'}" name="ShippingAddressForm[s_statename]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-12 medium-6 columns medium-text-right">
                                <label for="registration__s_city">
                                    {t 'City' dict='order'}
                                    <span class="required">*</span>
                                </label>

                            </div>
                            <div class="small-12 medium-6 columns">
                                <input value="{$order->s_city}" id="registration__s_city" required placeholder="{t 'Princeton' dict='order'}" name="ShippingAddressForm[s_city]" type="text"/>
                            </div>
                        </div>
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
                        <div class="row">
                            <div class="small-12 medium-6 columns medium-text-right">
                                <label>
                                    {t 'Full name' dict='order'}
                                    <span class="required">*</span>
                                </label>

                            </div>
                            <div class="small-12 medium-6 columns">
                                <input value="{$order->firstname}" required placeholder="{t 'Albert H. Einstein' dict='order'}" name="ContactInfoForm[firstname]" type="text"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-12 medium-6 columns medium-text-right">
                                <label>
                                    {t 'Phone' dict='order'}
                                    <span class="required">*</span>
                                </label>
                            </div>
                            <div class="small-12 medium-6 columns">
                                <input value="{$order->phone}" required type="tel" placeholder="{t '(609) 734-8000' dict='order'}" name="ContactInfoForm[phone]"/>

                                <span class="phone_ext--container">
                                    <label>{t 'ext' dict='order'}</label>
                                    <input placeholder="" name="ContactInfoForm[phone_ext]" type="text" class="phone_ext"/>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-12 medium-6 columns medium-text-right">
                                <label>
                                    {t 'Email' dict='order'}
                                    <span class="required">*</span>
                                </label>
                            </div>
                            <div class="small-12 medium-6 columns">
                                <input value="{$order->email}" required type="email" placeholder="{t 'albert.einstein@gmail.com' dict='order'}" name="ContactInfoForm[email]"/>
                            </div>
                        </div>
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
                        <button type="submit" class="button yellow waves waves-orange waves-effect">{t 'Submit' dict='order'}</button>
                    </div>
                </div>
            </div>
            <div class="row align-center">
                <div class="column small-12">
                    <div class="submit-notes text-center">
                        {t 'Submit and proceed to shipping & payment options.' dict='order'}
                    </div>
                </div>
            </div>

        </section>
    </form>
{/block}