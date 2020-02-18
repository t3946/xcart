{extends "fraud_check/base.tpl"}
{block 'content'}
    {if $additional_info}
        Sender of payment  on PayPal: <b>{$additional_info}</b>
    {/if}
{/block}