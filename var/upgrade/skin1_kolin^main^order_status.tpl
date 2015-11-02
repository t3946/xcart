{* $Id: order_status.tpl,v 1.7 2005/11/30 13:29:35 max Exp $ *}
{if $usertype eq 'A' && $current_membership_flag eq 'FS'}{assign var="limited" value="Y"}{/if}
{if $status_type eq ''}{assign var="status_type" value="CB"}{/if}
{if $extended eq "" and $status eq ""}
    {$lng.lbl_wrong_status}
{elseif $mode eq "select" && ($limited eq "" || $extended ne "")}
    <select name="{$name}" {$extra}>
    {if $extended ne "" && $limited eq ""}
        <option value=""></option>
        {if $usertype eq 'A' or $usertype eq 'P'}
            <option value="not_DCS"{if $status eq "not_DCS"} selected="selected"{/if}>{$lng.lbl_not_dcs_status}</option>
        {/if}
    {/if}

    {foreach from=$statuses[$status_type] key="code" item="o_status"}
        {if $limited eq ''}
            <option value="{$code}"{if $status eq $code} selected="selected"{/if}>{$o_status}</option>
        {else}
            {if $code eq 'C' || $code eq 'S'}
                <option value="{$code}"{if $status eq $code} selected="selected"{/if}>{$o_status}</option>
            {/if}
        {/if}
    {/foreach}
    </select>
{elseif $mode eq "static" || $limited ne ""}
    {$statuses[$status_type][$status]}
{/if}
