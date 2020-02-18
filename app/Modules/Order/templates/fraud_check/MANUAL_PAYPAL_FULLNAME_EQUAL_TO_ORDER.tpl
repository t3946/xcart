{extends "fraud_check/base.tpl"}
{block 'content'}
    {if $additional_info}
        Customer name on PayPal transaction page: <b>{$additional_info}</b>
    {/if}
{/block}