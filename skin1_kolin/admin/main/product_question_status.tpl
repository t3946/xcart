{if $extended eq "" and $status eq ""}
{*    {$lng.lbl_wrong_status} *}
{elseif $mode eq "select"}

    <select name="{$name}" {$extra}>
    {if $empty eq "Y"}
        <option value=""></option>
    {/if}

    {foreach from=$product_question_statuses key="code" item="o_status"}
		<option value="{$code}"{if $status eq $code} selected="selected"{/if}>{$o_status}</option>
    {/foreach}
    </select>
{elseif $mode eq "static"}
    {$product_question_statuses[$status]}
{/if}
