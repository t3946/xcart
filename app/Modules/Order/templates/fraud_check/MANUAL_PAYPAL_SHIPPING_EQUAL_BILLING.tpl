{extends "fraud_check/base.tpl"}
{block 'content'}
    {if $additional_info}
        X-cart address: <b>{$additional_info['o_address']}</b><br/>
        PayPal address: <b>{$additional_info['p_address']}</b>
    {/if}
{/block}