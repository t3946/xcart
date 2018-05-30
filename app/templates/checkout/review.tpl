{extends "checkout/base.tpl"}
{block 'content'}
    <form data-abide action="{url 'checkout:review'}" method="POST" class="checkout-review-form" enctype= "multipart/form-data">
        {if $order->payment_method == 'Purchase Order'}
            {set $extra = $order->extra_model}
            <section class="checkout-po">
                <div class="row">
                    <div class="columns small-12">
                        <div class="options">
                            <h2 class="title">{t 'Purchase Order Details' dict='order'}</h2>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="row">
                            <div class="column small-12">
                                <div class="mandatory">
                                    {t 'The fields marked with' dict='order'} <span class="required">*</span> {t 'are mandatory.' dict='order'}
                                </div>
                            </div>
                        </div>
                        {include 'checkout/_form_row.tpl' field=$orderDetailsForm->getField('po_number')}
                        {include 'checkout/_form_row.tpl' field=$orderDetailsForm->getField('organization_name')}
                    </div>
                </div>
                <div class="row">
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
                        {include 'checkout/_form_row.tpl' field=$purchasingManagerForm->getField('firstname')}
                        <div class="row form-row compound-field">

                            <div class="column hide-for-large small-12 large-2 large-order-2">
                                {$purchasingManagerForm->getField('phone')->renderErrors()}
                            </div>

                            <div class="column small-12 large-order-1">
                                <div class="row">
                                    <div class="small-12 large-6 columns large-text-right text-block">
                                        {if $field->hint}
                                            <div class="multiline">
                                                {$purchasingManagerForm->getField('phone')->renderLabel()}

                                                <span class="hint">
                                                    {$purchasingManagerForm->getField('phone')->renderHint()}
                                                </span>
                                            </div>
                                        {else}
                                            {$purchasingManagerForm->getField('phone')->renderLabel()}
                                        {/if}
                                    </div>
                                    <div class="small-12 large-6 columns phone--container">
                                        {$purchasingManagerForm->getField('phone')->renderInput()}
                                        <span class="phone_ext--container">
                                            <label class="display-inline hide-for-medium">X</label>
                                            <label class="display-inline show-for-medium">{t 'ext' dict='order'}</label>

                                            {$purchasingManagerForm->getField('phone_ext')->renderInput()}
                                        </span>

                                        <span class="show-for-large">
                                            {$purchasingManagerForm->getField('phone')->renderErrors()}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {include 'checkout/_form_row.tpl' field=$purchasingManagerForm->getField('fax')}
                        {include 'checkout/_form_row.tpl' field=$purchasingManagerForm->getField('email')}
                    </div>
                </div>
                <div class="row">
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
                        {include 'checkout/_form_row.tpl' field=$accountsPayableForm->getField('firstname')}
                        <div class="row form-row compound-field">

                            <div class="column hide-for-large small-12 large-2 large-order-2">
                                {$accountsPayableForm->getField('phone')->renderErrors()}
                            </div>

                            <div class="column small-12 large-order-1">
                                <div class="row">
                                    <div class="small-12 large-6 columns large-text-right text-block">
                                        {if $accountsPayableForm->getField('phone')->hint}
                                            <div class="multiline">
                                                {$accountsPayableForm->getField('phone')->renderLabel()}

                                                <span class="hint">
                                                    {$accountsPayableForm->getField('phone')->renderHint()}
                                                </span>
                                            </div>
                                        {else}
                                            {$accountsPayableForm->getField('phone')->renderLabel()}
                                        {/if}
                                    </div>
                                    <div class="small-12 large-6 columns phone--container">
                                        {$accountsPayableForm->getField('phone')->renderInput()}
                                        <span class="phone_ext--container">
                                            <label class="display-inline hide-for-medium">X</label>
                                            <label class="display-inline show-for-medium">{t 'ext' dict='order'}</label>

                                            {$accountsPayableForm->getField('phone_ext')->renderInput()}
                                        </span>

                                        <span class="show-for-large">
                                            {$accountsPayableForm->getField('phone')->renderErrors()}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {include 'checkout/_form_row.tpl' field=$accountsPayableForm->getField('fax')}
                        {include 'checkout/_form_row.tpl' field=$accountsPayableForm->getField('email')}
                        {include 'checkout/_form_row.tpl' field=$orderDetailsForm->getField('purchase_order_file')}
                    </div>
                </div>
            </section>
        {/if}

        {include "checkout/_review_info.tpl" order = $order}

        <section class="customer-notes">
            <div class="row align-center">
                <div class="columns  small-12 medium-3 large-3">
                    <h2>{t 'Customer notes' dict='order'}</h2>
                </div>
                <div class="columns small-12 medium-6 large-9">
                    <textarea name="customer_notes" placeholder="{t 'Put your order related instructions here' dict='order'}">
                        {$order->customer_notes}
                    </textarea>
                </div>
            </div>

        </section>

        <section class="submit-order">
            <div class="row align-center">
                <div class="column small-12">
                    <div class="buttons text-center">
                        <button type="submit" class="button yellow waves waves-orange waves-effect submit-order-button">
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

    </form>
{/block}