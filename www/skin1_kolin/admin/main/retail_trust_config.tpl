<tr>
    <td></td>
    <td>Order status send email</td>
    <td><select name="retail_trust_order_status">
            {foreach from=$statuses item=group key=type}
                {if $type ne 'BD' && $type ne 'CA'}
                    <optgroup label="{$status_types[$type]}">
                        {foreach from=$group item=order_status key="code"}
                            {if $code ne "K" && $code ne "L" && $code ne "M"}
                                <option value="{$code}"{if $status eq $code} selected="selected"{/if}>{$order_status}</option>
                            {/if}
                        {/foreach}
                    </optgroup>
                {/if}
            {/foreach}
        </select>
    </td>
</tr>