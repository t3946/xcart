<style>
    .table__net td {
        border: 1px solid;
        white-space: nowrap;
    }
    .table__net tr.selected {
        background-color: #90d0ed;
    }
</style>
<form>
<table class="table__net" style="width:100%; border: 1px solid; border-collapse: collapse" cellpadding="5" cellspacing="0">
    <tr class="TableHead">
        <td><input id="check__all" type="checkbox" value="" /></td>
        <td>Order date</td>
        <td>Invoice date</td>
        <td>Payment due<br/>date</td>
        <td>Distributor</td>
        <td>Order #</td>
        <td>Profit <br/>Margin</td>
        <td>Dx Invoice #</td>
        <td>Invoice <br/>amount</td>
        <td>Credit memo <br/>amount</td>
        <td>Balance <br/>due</td>
    </tr>
    {set $profit = 0}
    {set $net = 0}
    {foreach $orders as $order_group first=$first}
        {set $profit += $order_group->accounting_net_5_profit}
        {set $net += $order_group->accounting_net_0}
        {if $first}
            <input type="hidden" name="manufacturer_id" value="{$order_group->manufacturerid}">
        {/if}
        {set $order = $order_group->order}
        {set $invoices = $order_group->invoices->filter(['status' => 'U'])}
        {set $memos = $order_group->memos->filter(['status' => 'U'])}
        {set $profit = $order_group->getProfitMargin()}
        {set $dx = $order_group->manufacturer}
        {foreach $invoices as $invoice}
            <tr data-total="{$invoice->invoice_total}" class="net__row">
                <td>
                    <input name="invoices[]" type="checkbox" value="{$invoice->orderid}_{$invoice->manufacturerid}_{$invoice->invoice_number}" />
                    <input type="hidden" name="manufacturer_id" value="{$invoice->manufacturerid}">
                </td>
                <td>{$order->date|date_format:'%d-%b-%Y'}</td>
                <td>{$invoice->invoice_date|date_format:'%d-%b-%Y'}</td>
                <td>{$invoice->getPaymentDueDate()->format('d-M-Y')}</td>
                <td><a target="_blank" href="{$dx->getAdminUrl(11)}">{$dx}</a></td>
                <td align="center"><a target="_blank" href="{$order->getAdminUrl()}">{$order->getOrderNumber()}</a></td>
                <td align="right">{if $profit < 0}({/if}{$profit|abs}%{if $profit < 0}){/if}</td>
                <td align="center">{$invoice->dx_invoice_number}</td>
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
            <tr data-total="-{$memo->ref_to_us_total}" class="net__row">
                <td>
                    <input name="memos[]" type="checkbox" value="{$memo->orderid}_{$memo->manufacturerid}_{$memo->memo_number}" />

                </td>
                <td>{$order->date|date_format:'%d-%b-%Y'}</td>
                <td>{$memo->memo_date|date_format:'%d-%b-%Y'}</td>
                <td>{$memo->getPaymentDueDate()->format('d-M-Y')}</td>
                <td><a target="_blank" href="{$dx->getAdminUrl(11)}">{$dx}</a></td>
                <td align="center"><a target="_blank" href="{$order->getAdminUrl()}">{$order->getOrderNumber()}</a></td>
                <td align="right">{if $profit < 0}({/if}{$profit|abs}%{if $profit < 0}){/if}</td>
                <td align="center">{$memo->dx_invoice_number}</td>
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
    {set $profit_margin = $profit / $net * 100}
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="right"><b>{$profit_margin|number_format:2:'.':','}%</b></td>
        <td></td>
        <td></td>
        <td colspan="2" align="right"><b>Total due: ${$balance_total|number_format:2:'.':','}</b></td>
    </tr>

</table>
</form>
<div style="text-align: center; margin-top:1em;">
    <b class="pay__balance"></b>
</div>
<div style="margin:10px 0; display: grid; grid-template-columns: 5fr 2fr 5fr;">
    <div>
        <button data-url="{url 'order:api:payable_prereconcile'}" class="net__pay__button">Combine for reconciliation</button>
        <div style="margin-top: 10px">
            <i>Selected invoices and credit memos will be pre-reconciled to a future payment to Dx.<br>
                Use this option for an upcoming VISA card (or a scheduled check) payment to Dx.</i>
        </div>
    </div>
    <div></div>
    <div>
        <div style="text-align: center">
            <button data-url="{url 'order:api:payable_tentatively'}" class="tent__pay__button">Mark as Tentatively paid</button>
        </div>
        <div style="margin-top: 10px">
            <i>{$.call.Modules.Core.Models.LanguageModel::translate('tentatively_paid_text')}</i>
        </div>
    </div>
</div>

<script>
    function calc_total(){
        let total = 0;

        $('.table__net .net__row.selected').each(function(){
            total += parseFloat($(this).data('total'));
        });
        $('.pay__balance').text(total.toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD'
        }));
    }

    $('.table__net #check__all').on('change', function(){
        $('.table__net .net__row input[type=checkbox]').prop('checked', $(this).prop('checked')).change();
    });

    $('.table__net .net__row input[type=checkbox]').on('change', function(){
        if ($(this).prop('checked')) {
            $(this).closest('tr').addClass('selected');
        } else {
            $(this).closest('tr').removeClass('selected');
        }
        calc_total();
    });

    $('.net__pay__button, .tent__pay__button').click(function(){
        const table = $('.table__net');
        const button = $(this);
        table.css('opacity', 0.4);
        button.prop('disabled', true);
        $.ajax({
            url: button.data('url'),
            data: table.closest('form').serialize(),
            type: 'POST',
            success: function(){
                $('#distributor_choises').change();
                button.prop('disabled', false);
                table.css('opacity', 1);
            }
        });
    });
</script>