{extends "fraud_check/base.tpl"}
{block 'content'}
    {if $additional_info}
        Customer's Email on PayPal transaction page: <b>{$additional_info}</b>
    {/if}
{/block}