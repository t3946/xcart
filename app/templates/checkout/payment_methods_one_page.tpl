{add $fieldsets = $checkout_form->createFieldsets()}
{set $payment_method_field = $fieldsets['other'][0]}
<div class="payment-methods-container">
    <h2 class="checkout__second-header checkout-payment-methods__header payment-methods-header">{t 'Payment Methods' }</h2>
    <div class="checkout-payment-methods checkout__payment-methods-container" data-default-checked-field="{$payment_method_field->value}">
        {foreach $payment_methods as $method}
            {set $checked = $method->paymentid == $payment_method_field->value || count($payment_methods) === 1}
            <div class="payment-method-item {cycle ["payment-method-item_odd", ""]}" for="payment_{$method->paymentid}">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="payment-method-name">
                            <input
                                {if $checked}checked{/if}
                                id="payment_{$method->paymentid}"
                                type="radio"
                                name="CheckoutForm[{$fieldsets['other'][0]->name}]"
                                value="{$method->paymentid}"
                                class="common-input-radio"
                                data-submit-hint="{$method->submit_hint}"
                            />
                            <div class="common-radio-label payment-radio-label payment-method-title">{$method->payment_method}</div>
                        </div>
                    </div>
                    <div class="payment-method-description col-12 col-lg-8">
                        <div class="payment-method-description-preview">{$method->payment_details}</div>
                        <div class="payment-method-description-long" {if $checked}style="display: block"{/if}>
                            {switch $method->paymentid}
                            {case 106}
                                <div class="billing-form-fields">
                                    {foreach $fieldsets['pay_by_card'] as $field}
                                        {raw $field->render()}
                                    {/foreach}

                                    <div class="billing-same-shipping">
                                        <h3 class="payment-method-title billing-same-shipping__header">{t 'Is Billing Address the same as Shipping Address?' }</h3>
                                        {raw $checkout_form->getField('billing_same_shipping')->render()}
                                    </div>

                                    <div class="billing-form-address-fields">
                                        {foreach $fieldsets['billing'] as $field}
                                            {if $field->name !== 'billing_same_shipping'}
                                                {raw $field->render()}
                                            {/if}
                                        {/foreach}
                                    </div>
                                </div>
                            {case 17}
                                <h3 class="payment-method-title">{t 'You will be transferred to PayPal website to complete your payment.'}</h3>
                            {case 4}
                                <h3 class="payment-method-title">{t 'Please call us 1-800-929-2431 to finalize your order over the phone.'}</h3>
                            {case 2}
                                <div class="form-purchase-order-details">
                                    <h2 class="checkout-payment-methods__purchase-order-header text-center large-text-left">Purchase Order Details</h2>
                                    <div class="checkout-mandatory checkout_mandatory text-center large-text-left">
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
                            {/switch}
                        </div>
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
</div>
