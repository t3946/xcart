{if $extended eq "" and $status eq ""}
    {$lng.lbl_wrong_status} 
{elseif $mode eq "select"}

    <select name="{$name}" {$extra}>
    {if $empty eq "Y"}
        <option value=""></option>
    {/if}

    {foreach from=$publication_statuses key="code" item="o_status"}
		<option value="{$code}"{if $status eq $code} selected="selected"{/if}>{$o_status}</option>
    {/foreach}
    </select>
{elseif $mode eq "static"}
    {if $color ne ""}<span style="color: {$color};">{/if}{$publication_statuses[$status]}{if $color ne ""}</span>{/if}
{/if}
