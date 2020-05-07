{extends "fraud_check/base.tpl"}
{block 'content'}
    {if $additional_info}
        {foreach $additional_info as $k => $v}
            {$k}: <b>{$v}</b><br/>
        {/foreach}
    {/if}
{/block}