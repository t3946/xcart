<div class="payment-methods-container">
    {if $payment_methods}
        <h2 class="text-center large-text-left checkout__second-header checkout-payment-methods__header">{t 'Payment Methods' }</h2>
        {if $payment_methods}
            <div class="checkout-payment-methods checkout__payment-methods-container">
                {foreach $payment_methods as $method first=$first}
                    {if !$phone_order_only || ($phone_order_only && $method->payment_method === 'Phone Ordering')}
                        <div class="">
                            <label class="payment-method-item {cycle ["payment-method-item_odd", ""]}" for="payment_{$method->paymentid}">
                                <div class="row">
                                    <div class="columns small-12 large-4">
                                        <div class="payment-method-name">
                                            <input
                                                    {if $first || $phone_order_only || ($method->paymentid == $order->paymentid)}checked{/if}
                                                    id="payment_{$method->paymentid}"
                                                    type="radio"
                                                    name="payment_method"
                                                    value="{$method->paymentid}"
                                                    class="common-input-radio"
                                            />
                                            <div class="common-radio-label payment-radio-label payment-method-title">{$method->payment_method}</div>
                                        </div>
                                    </div>
                                    <div class="payment-method-description columns small-12 large-8">
                                        <div class="payment-method-description-preview">{$method->payment_details}</div>
                                        <div class="payment-method-description-long">
                                            {if stripos($method->payment_method, 'Pay by Credit or Debit card') !== false}
                                                <div class="billing-form-fields">
                                                    <label for="">Cardholder name *<input type="text"></label><br>
                                                    <label for="">Credit / Debit card details *<input type="text"></label>
                                                    <div class="billing-same-shipping">
                                                        <h3 class="payment-method-title billing-same-shipping__header">{t 'Is Billing Address the same as Shipping Address?' }</h3>
                                                        <div class="billing-same-shipping-variants billing-same-shipping__variants">
                                                            <label class="billing-same-shipping-radio-group" for="billing_yes">
                                                                <input class="common-input-radio" id="billing_yes" type="radio" name="billing_same" value="1" checked/>
                                                                <span class="common-radio-label billing-same-shipping-variant-label">{t 'Yes' }</span>
                                                            </label>
                                                            <label class="billing-same-shipping-radio-group" for="billing_no">
                                                                <input class="common-input-radio" id="billing_no" type="radio" name="billing_same" value="0"/>
                                                                <span class="common-radio-label billing-same-shipping-variant-label">{t 'No' }</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="billing-form-address-fields">
                                                        {foreach array_slice($fieldsets['billing'], 0, 3) as $field}
                                                            {if $field->getName() === 'b_address' }
                                                                <div class="checkout-billing-switcher-field-wrapper">
                                                                    {raw $field->render()}
                                                                    <span class="switcher-button switcher-button_white switcher-button_shipping-form checkout-billing-form__other-fields-switcher">
                                                                        <svg class="icon switcher-button-icon switcher-button-icon-plus"><use xlink:href="https://dev1.test.artistsupplysource.com/static/frontend/dist/images/icons/sprite.svg#switcher-plus"></use></svg>
                                                                        <svg class="icon switcher-button-icon switcher-button-icon-minus"><use xlink:href="https://dev1.test.artistsupplysource.com/static/frontend/dist/images/icons/sprite.svg#switcher-minus"></use></svg>
                                                                    </span>
                                                                </div>
                                                            {else}
                                                                {raw $field->render()}
                                                            {/if}
                                                        {/foreach}
                                                        <div class="checkout-billing-other-fields">
                                                            {foreach array_slice( $fieldsets['billing'], 3 ) as $field}
                                                                {raw $field->render()}
                                                            {/foreach}
                                                        </div>
                                                    </div>
                                                </div>
                                            {elseif stripos($method->payment_method, 'Pay by PayPal Balance') !== false }
                                                <h3 class="payment-method-title">You will be transferred to PayPal website to complete your payment.</h3>
                                            {elseif stripos($method->payment_method, 'Phone Ordering') !== false }
                                                <h3 class="payment-method-title">Please call us 1-800-929-2431 to finalize your order over the phone.</h3>
                                            {elseif stripos($method->payment_method, 'Purchase Order') !== false }
                                                <div class="purchase-order-wrapper">
                                                    <h2 class="checkout-payment-methods__purchase-order-header">Purchase Order Details</h2>
                                                    <div class="checkout-mandatory checkout__mandatory">
                                                        {t 'The fields marked with' }
                                                        <span class="common-label_required checkout-mandatory__required"></span> {t 'are mandatory.' }
                                                    </div>
                                                    {foreach $fieldsets['purchase_order_details'] as $field}
                                                        {raw $field->render()}
                                                    {/foreach}
                                                    <h2 class="checkout-payment-methods__purchase-order-header">Purchase Manager</h2>
                                                    {foreach $fieldsets['purchasing_manager'] as $field}
                                                        {raw $field->render()}
                                                    {/foreach}
                                                    <h2 class="checkout-payment-methods__purchase-order-header">Account Payable</h2>
                                                    {foreach $fieldsets['accounts_payable'] as $field}
                                                        {raw $field->render()}
                                                    {/foreach}
                                                </div>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    {/if}
                {/foreach}
            </div>
        {/if}
    {/if}
</div>

<style>
    .checkout-payment-methods__purchase-order-header {
        margin: 0 0 16px;
    }

    .purchase-order-wrapper {
        max-width: 360px;
    }
</style>