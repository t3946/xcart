{extends "fraud_check/base.tpl"}
{block 'content'}
    {if $additional_info}
        {foreach $additional_info as $k => $a}
            {$k}: <b>{$a}</b>
        {/foreach}
    {/if}
{/block}