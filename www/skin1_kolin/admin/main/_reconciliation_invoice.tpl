{if (!$distributor)}
    {assign var=distributor value=$model->manufacturer}
{/if}
    {assign var=order value=$model->order}

    <td width="90" align="center" nowrap="nowrap">
        {if $model instanceof Modules\Order\Models\OrderGroupInvoiceModel}
            ({$model->invoice_total})
        {else}
            {$model->ref_to_us_total}
        {/if}
        {if $last && $reconciliation}
            {if round($invoices_total,2) != $reconciliation.model->amount_csv && round($invoices_total,2) != 0}
                {math equation="x-y" x=$v.model->amount_csv y=$invoices_total assign="invoices_diff"}
                <br/>
                <span style="color: red;">{if $invoices_diff < 0}({/if}{$invoices_diff|abs|price_format}{if $invoices_diff < 0}){/if} </span>
            {/if}
        {/if}
    </td>
    <td width="90" align="center">
        <a href="{$distributor->getAdminUrl(11)}" target="_blank">{$distributor->code}</a></td>
    <td width="90" align="center">
        <a href="{$order->getAdminUrl()}" target="_blank">{$order->getOrderNumber()}</a><br/>
    </td>
    <td nowrap="nowrap" width="100" align="center">
        {if $model->dx_invoice_number}
            {$model->dx_invoice_number}
        {/if}
    </td>
    <td width="90" align="center">
        {if $reconciliation}
            {math equation="(x-y)/(60*60*24)" x=$v.model->date_csv y=$order->date assign="date_diff"}
        {/if}
        <span {if $date_diff >= 30}style="background-color: #F4CCCC;"{/if}>
           {if $model instanceof Modules\Order\Models\OrderGroupInvoiceModel}
               {$model->invoice_date|date_format:'%d-%b-%Y'}
           {else}
               {$model->memo_date|date_format:'%d-%b-%Y'}
           {/if}
       </span>
    </td>
    {if $reconciliation && $tab eq "unreconciled"}
        <td align="center" width="20">
            {if $model instanceof Modules\Order\Models\OrderGroupInvoiceModel}
                <input type="checkbox"
                       name="clear_invoices_memos[I_{$v.model->id}_{$model->invoice_number}_{$model->manufacturerid}_{$order->orderid}]"
                       value="Y"/>
            {else}
                <input type="checkbox"
                       name="clear_invoices_memos[M_{$v.model->id}_{$model->memo_number}_{$model->manufacturerid}_{$order->orderid}]"
                       value="Y"/>
            {/if}
        </td>
    {/if}