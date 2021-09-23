{if $order.additional_fee ne ""}
    {foreach from=$order.additional_fee item=v_f key=k_f}
        <tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
            <td>
                <input type="text" name="edit_additional_fee_name[{$v_f.id}][additional_fee_name]"
                       value="{$v_f.additional_fee_name}" size="16" style="width: 99%;"
                       {if $order.amazonorderid ne ""}readonly="readonly"{/if} />
            </td>
            <td colspan="6">&nbsp;</td>
            <td align="right">
                <input type="text" name="edit_additional_fee_name[{$v_f.id}][additional_fee_value]"
                       value="{$v_f.additional_fee_value}" size="8"
                       {if $order.amazonorderid ne ""}readonly="readonly"{/if} />
            </td>
            <td>&nbsp;</td>
            <td align="right">{$v_f.additional_fee_value}</td>
            <td><input type="checkbox" value="Y" name="delete_additional_fee[{$v_f.id}]" {if $order.amazonorderid ne ""}disabled="disabled"{/if} /></td>
        </tr>
    {/foreach}
{/if}