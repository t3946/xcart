<div class="payment-methods-container">
    {if $payment_methods}
        <h2 class="text-center large-text-left checkout__second-header checkout-payment-methods__header payment-methods-header">{t 'Payment Methods' }</h2>
        {if $payment_methods}
            <div class="checkout-payment-methods checkout__payment-methods-container">
                {foreach $payment_methods as $method first=$first}
                    {if !$phone_order_only || ($phone_order_only && $method->payment_method === 'Phone Ordering')}
                        <div class="payment-method-item {cycle ["payment-method-item_odd", ""]}" for="payment_{$method->paymentid}">
                            <div class="row">
                                <div class="columns small-12 large-4">
                                    <div class="payment-method-name">
                                        <input
                                                {if $first }checked{/if}
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
                                                {foreach $fieldsets['pay_by_card'] as $field}
                                                    {raw $field->render()}
                                                {/foreach}

                                                <div class="billing-same-shipping">
                                                    <h3 class="payment-method-title billing-same-shipping__header">{t 'Is Billing Address the same as Shipping Address?' }</h3>

                                                    <div class="switcher-slider billing-same-shipping-switcher billing__switcher">
                                                        <div class="switcher-slider-label">
                                                            <input type="checkbox" class="hide" name="billing_same_shipping" />
                                                            <b class="switcher-slider-caption switcher-slider-disable-caption switcher-slider-caption_disabled">{t 'no'}</b>
                                                            <span class="switcher-slider-ball"></span>
                                                            <b class="switcher-slider-caption switcher-slider-caption_enabled">{t 'yes'}</b>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="billing-form-address-fields">
                                                    {foreach array_slice($fieldsets['billing'], 0, 3) as $field}
                                                        {raw $field->render()}
                                                    {/foreach}
                                                    <div class="checkout-billing-other-fields">
                                                        {foreach array_slice( $fieldsets['billing'], 3 ) as $field}
                                                            {raw $field->render()}
                                                        {/foreach}
                                                    </div>
                                                </div>
                                            </div>
                                        {elseif stripos($method->payment_method, 'Pay by PayPal Balance') !== false }
                                            <h3 class="payment-method-title payment-method-">You will be transferred to PayPal website to complete your payment.</h3>
                                        {elseif stripos($method->payment_method, 'Phone Ordering') !== false }
                                            <h3 class="payment-method-title">Please call us 1-800-929-2431 to finalize your order over the phone.</h3>
                                        {elseif stripos($method->payment_method, 'Purchase Order') !== false }
                                            <div class="form-purchase-order-details">
                                                <h2 class="checkout-payment-methods__purchase-order-header text-center large-text-left">Purchase Order Details</h2>
                                                <div class="checkout-mandatory checkout__mandatory text-center large-text-left">
                                                    {t 'The fields marked with' }
                                                    <span class="mandatory-star">*</span> {t 'are mandatory.' }
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
</style>