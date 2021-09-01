{extends "fraud_check/base.tpl"}
{block 'content'}
    {if $additional_info}
        MANUAL_PAYPAL_SHIPPING_EQUAL_BILLING status: <b>{$additional_info['MANUAL_PAYPAL_SHIPPING_EQUAL_BILLING']}</b> <br/>
        Customer's shipping address in PayPal is: <b>{$additional_info['address_status']}</b>
    {/if}
{/block}