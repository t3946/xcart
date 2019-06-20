<style>
    .table__net td {
        border: 1px solid;
        white-space: nowrap;
    }
</style>
<table class="table__net" style="width:100%; border: 1px solid; border-collapse: collapse" cellpadding="5" cellspacing="0">
    <tr class="TableHead">
        <td>Order date</td>
        <td>Invoice date</td>
        <td>Payment due<br/>date</td>
        <td>Distributor</td>
        <td>Order #</td>
        <td>Invoice # <br/>Credit memo #</td>
        <td>Invoice <br/>amount</td>
        <td>Credit memo <br/>amount</td>
        <td>Balance <br/>due</td>
    </tr>
    {foreach $orders as $order_group}
        {set $order = $order_group->order}
        {set $invoices = $order_group->invoices->filter(['status' => 'U'])}
        {set $memos = $order_group->memos->filter(['status' => 'U'])}
        {foreach $invoices as $invoice}
            <tr>
                <td>{$order->date|date_format:'%d-%b-%Y'}</td>
                <td>{$invoice->invoice_date|date_format:'%d-%b-%Y'}</td>
                <td>{$invoice->getPaymentDueDate()|date_format:'%d-%b-%Y'}</td>
                <td>{$order_group->manufacturer}</td>
                <td align="center"><a target="_blank" href="{$order->getAdminUrl()}">{$order->getOrderNumber()}</a></td>
                <td align="center">{$invoice}</td>
                <td align="right">{$invoice->invoice_total|number_format:2:'.':','}</td>
                <td></td>
                <td align="right">
                    {set $balance_due = $invoice->invoice_total}
                    {set $balance_total += $invoice->invoice_total}
                    {$balance_due|number_format:2:'.':','}
                </td>
            </tr>
        {/foreach}
        {foreach $memos as $memo}
            <tr>
                <td>{$order->date|date_format:'%d-%b-%Y'}</td>
                <td>{$memo->memo_date|date_format:'%d-%b-%Y'}</td>
                <td>{$memo->getPaymentDueDate()|date_format:'%d-%b-%Y'}</td>
                <td>{$order_group->manufacturer}</td>
                <td align="center"><a target="_blank" href="{$order->getAdminUrl()}">{$order->getOrderNumber()}</a></td>
                <td align="center">{$memo}</td>
                <td></td>
                <td align="right">{$memo->ref_to_us_total|number_format:2:'.':','}</td>
                <td align="right">
                    {set $balance_due = $memo->ref_to_us_total}
                    {set $balance_total -= $memo->ref_to_us_total}
                    ({$balance_due|number_format:2:'.':','})
                </td>
            </tr>
        {/foreach}
    {/foreach}
    <tr>
        <td colspan="9" align="right">
            <b>Total due: {$balance_total|number_format:2:'.':','}</b>
        </td>
    </tr>
</table>
