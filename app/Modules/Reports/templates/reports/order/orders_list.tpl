<table cellpadding="3" cellspacing="1" class="OrderSheet">
<tr>
    <td class="OrderSheetCell" colspan="2" style="text-align: right;">Distributors:</td>
    <td class="OrderSheetCell" colspan="10" style="text-align: left; font-weight: bold;">
        {$totals.codes}
    </td>
    {if $data.show_reconciled !=''}<td class="OrderSheetCell"></td>{/if}
</tr>
<tr>
    <td class="OrderSheetCell" colspan="2" style="text-align: right;">Report period:</td>
    <td class="OrderSheetCell" colspan="10" style="text-align: left; font-weight: bold;">
        {if $form_data.order.date}
            {$form_data.order.date}
        {else}
            All dates
        {/if}
    </td>
    {if $data.show_reconciled !=''}<td class="OrderSheetCell"></td>{/if}
</tr>
<tr>
    <td class="OrderSheetCell" colspan="{if $data.show_reconciled !=''}13{else}12{/if}">&nbsp; </td>
</tr>
</table>
<table cellpadding="3" cellspacing="1" class="OrderSheet">
    <tr class="TableHead TableHeadAccounting">
        <td width="5">&nbsp;</td>
        <td>C2B PAYMENT</td>
        <td>CUSTOMER</td>
        <td>NET</td>
        <td>PROCESSOR</td>
        <td>NET</td>
        <td {if $full_reconciliation_info_found}title="To unfreeze this cell unreconcile this order with the corresponding transaction."{/if}>COST TO US</td>
        <td {if $full_reconciliation_info_found}title="To unfreeze this cell unreconcile this order with the corresponding transaction."{/if}>SHIPPING</td>
        <td>REF TO CUST</td>
        <td>RED TO US</td>
        <td>PROFIT</td>
        {if $data.show_reconciled}<td>ORDER RECONCILED</td>{/if}
        <td>PROFIT</td>
    </tr>
    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td width="5"><b>#</b></td>
        <td><b>D2C SHIPPING</b></td>
        <td>&nbsp;</td>
        <td>HST IN</td>
        <td><b>PAYMENT</b></td>
        <td>HST IN</td>
        <td {if $full_reconciliation_info_found}title="To unfreeze this cell unreconcile this order with the corresponding transaction."{/if}>HST OUT</td>
        <td {if $full_reconciliation_info_found}title="To unfreeze this cell unreconcile this order with the corresponding transaction."{/if}>HST OUT</td>
        <td>HST OUT</td>
        <td>HST IN</td>
        <td>HST IN</td>
        {if $data.show_reconciled}<td></td>{/if}
        <td><b>MARGIN</b></td>
    </tr>
    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td width="5"><b>DISTR</b></td>
        <td><b>B2D PAYMENT</b></td>
        <td>&nbsp;</td>
        <td>PST IN</td>
        <td><b>DATE</b></td>
        <td>PST IN</td>
        <td {if $full_reconciliation_info_found}title="To unfreeze this cell unreconcile this order with the corresponding transaction."{/if}>PST OUT</td>
        <td {if $full_reconciliation_info_found}title="To unfreeze this cell unreconcile this order with the corresponding transaction."{/if}>PST OUT</td>
        <td>PST OUT</td>
        <td>PST IN</td>
        <td>PST IN</td>
        {if $data.show_reconciled}<td></td>{/if}
        <td>REAL NET</td>
    </tr>
    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td width="5">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>GROSS</td>
        <td><b>TIME</b></td>
        <td>GROSS</td>
        <td {if $full_reconciliation_info_found}title="To unfreeze this cell unreconcile this order with the corresponding transaction."{/if}>COST TO US</td>
        <td {if $full_reconciliation_info_found}title="To unfreeze this cell unreconcile this order with the corresponding transaction."{/if}>SHIPPING</td>
        <td>REF TO CUST</td>
        <td>REF TO US</td>
        <td>PROFIT</td>
        {if $data.show_reconciled !=''}<td></td>{/if}
        <td>REAL PM</td>
    </tr>
    <tr>
        <td class="OrderSheetCell" colspan="{if $data.show_reconciled !=''}13{else}12{/if}">&nbsp; </td>
    </tr>
    <tr class="OrderSheetCell OrderSheetFirst" style="font-weight: bold;">
        <td></td>
        <td></td>
        <td></td>
        <td>{$totals.total_net|hide_zero|formatprice:",":"."|west_style}</td>
        <td></td>
        <td style="background-color: #D9EAD3;">
             {$totals.accounting_net_0|hide_zero|formatprice:",":"."|west_style}
        </td>
        <td {if $form_data.order.profit_margin}style="background-color: #D9EAD3;"{/if}>{$totals.accounting_net_1_cost_to_us|hide_zero|formatprice:",":"."|west_style}</td>
        <td {if $form_data.order.profit_margin}style="background-color: #D9EAD3;"{/if}>{$totals.accounting_net_2_shipping|hide_zero|formatprice:",":"."|west_style}</td>
        <td style="background-color: #D9EAD3;">
             {$totals.accounting_net_3_ref_to_cust|hide_zero|formatprice:",":"."|west_style}
        </td>
        <td style="background-color: #D9EAD3;">
             {$totals.accounting_net_4_ref_to_us|hide_zero|formatprice:",":"."|west_style}
        </td>
        <td>{$totals.accounting_net_5_profit|hide_zero|formatprice:",":"."|west_style}</td>
        {if $data.show_reconciled}<td></td>{/if}
        <td>{$totals.total_margin}%</td>
    </tr>
    <tr class="OrderSheetCell">
        <td></td>
        <td></td>
        <td><strong>REPORT</strong></td>
        <td>{$totals.gst}</td>
        <td></td>
        <td>{$totals.accounting_gst_0|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_gst_1_cost_to_us|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_gst_2_shipping|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_gst_3_ref_to_cust|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_gst_4_ref_to_us|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_gst_5_profit|hide_zero|formatprice:",":"."|west_style}</td>
        {if $data.show_reconciled}<td></td>{/if}
        <td></td>
    </tr>
    <tr class="OrderSheetCell">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><strong>TOTALS:</strong></td>
        <td>{if floatval($totals.pst)}{$totals.pst}{/if}</td>
        <td></td>
        <td>{$totals.accounting_pst_0|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_pst_1_cost_to_us|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_pst_2_shipping|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_pst_3_ref_to_cust|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_pst_4_ref_to_us|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_pst_5_profit|hide_zero|formatprice:",":"."|west_style}</td>
        {if $data.show_reconciled}<td></td>{/if}
        <td {if !$form_data.order.profit_margin}style="background-color: #D9EAD3;"{/if}>
            {$totals.real_net|hide_zero|formatprice:",":"."|west_style}
        </td>
    </tr>
    <tr class="OrderSheetCell">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>{$totals.total_gross|hide_zero|formatprice:",":"."|west_style}</td>
        <td></td>
        <td>{$totals.accounting_gross_0|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_gross_1_cost_to_us|hide_zero|formatprice:",":"."|west_style}</td>
        <td>{$totals.accounting_gross_2_shipping|formatprice:",":"."}</td>
        <td>{$totals.accounting_gross_3_ref_to_cust|formatprice:",":"."}</td>
        <td>{$totals.accounting_gross_4_ref_to_us|formatprice:",":"."}</td>
        <td>{$totals.accounting_gross_5_profit|formatprice:",":"."}</td>
        {if $data.show_reconciled}<td></td>{/if}
        <td {if $form_data.order.profit_margin}style="background-color: #D9EAD3;"{/if}>{$totals.real_pm|formatprice:",":"."|west_style}%</td>
    </tr>
    <tr class="OrderSheetCell OrderSheetFirst">
        <td colspan="{if $data.show_reconciled !=''}13{else}12{/if}">&nbsp; </td>
    </tr>
{foreach $models as $order index=$index}
    {foreach $order->groups as $group}
        <tr class="OrderSheetCell {if !$index}OrderSheetFirst{/if}">
            <td width="5"></td>
            <td>
                {foreach $order_statuses.CB as $status}
                    {if $status.code == $group->cb_status}
                        <b>{$status.name}</b>
                    {/if}
                {/foreach}
            </td>
            <td nowrap="nowrap" class="OrderSheetCommonCell">{$order->firstname}</td>
            <td>{$group->total_net|formatprice:",":"."}</td>
            <td>
                {foreach $payment_methods as $method}
                    {if $method.paymentid ==$order->paymentid}
                        <span title="{$method.payment_details}">
                            <b>{$method.payment_method}</b>
                        </span>
                    {/if}
                {/foreach}
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                <b>{$group->accounting_net_0|hide_zero|formatprice:",":"."|west_style}</b>
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                <b>{$group->accounting_net_1_cost_to_us|hide_zero|formatprice:",":"."|west_style}</b>
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                <b>{$group->accounting_net_2_shipping|hide_zero|formatprice:",":"."|west_style}</b>
            </td>
            <td {if $group->accounting_filled_3_ref_to_cust == 'Y'}class="FilledAccounting"{/if}>
                <b>{$group->accounting_net_3_ref_to_cust|hide_zero|formatprice:",":"."|west_style}</b>
            </td>
            <td {if $group->accounting_filled_4_ref_to_us == 'Y'}class="FilledAccounting"{/if} style="background-color: #B4A7D6;">
                <b>{$group->accounting_net_4_ref_to_us|hide_zero|formatprice:",":"."|west_style}</b>
            </td>
            <td>
                <b>{$group->accounting_net_5_profit|hide_zero|formatprice:",":"."|west_style}</b>
            </td>
            {if $data.show_reconciled}
                <td {if $v.reconcile_status == 1}style="background-color:#D9EAD3;"{/if} {if $v.reconcile_status == 2}style="background-color:#DDF177;"{/if} >
                    {if $v.reconcile_status == 1}
                        Reconciled
                    {elseif $v.reconcile_status == 2}
                        Partial Reconciled
                    {/if}
                </td>
            {/if}
            <td>
                <b>
                    {if floatval($group->accounting_net_0)}
                        {set $profit_margin = round($group->accounting_net_5_profit / $group->accounting_net_0 * 100, 2)}
                        {if (floatval($profit_margin))}
                            {$profit_margin|formatprice:",":"."|west_style}%
                        {else}
                            &infin;
                        {/if}
                    {else}
                        &infin;
                    {/if}
                </b>
            </td>
        </tr>
        <tr class="OrderSheetCell">
            <td width="5">
                <a href="{$order->getAdminUrl()}" style="color: blue; font-weight: bold;" target="_blank">{$order->getOrderNumber()}</a>
            </td>
            <td>
                {foreach $order_statuses.DC as $status}
                    {if $status.code == $group->dc_status}
                        <b>{$status.name}</b>
                    {/if}
                {/foreach}
            </td>
            <td>{$order->lastname}</td>
            <td>{$group->total_gst|hide_zero|formatprice:",":"."|west_style}</td>
            <td>
                {$order->payment_method}
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_gst_0|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_gst_1_cost_to_us|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_gst_2_shipping|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_3_ref_to_cust == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_gst_3_ref_to_cust|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_4_ref_to_us == 'Y'}class="FilledAccounting"{/if} style="background-color: #B4A7D6;">
                {$group->accounting_gst_4_ref_to_us|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td>
                {$group->accounting_gst_5_profit|hide_zero|formatprice:",":"."|west_style}
            </td>
            {if $data.show_reconciled}
                <td></td>
            {/if}
            <td></td>
        </tr>
        <tr class="OrderSheetCell">
            <td width="5"></td>
            <td>
                {foreach $order_statuses.BD as $status}
                    {if $status.code == $group->bd_status}
                        <b>{$status.name}</b>
                    {/if}
                {/foreach}
            </td>
            <td>
                {foreach $countries as $country}
                    {if $country.id == $order->s_country}
                        {raw $country.text}
                    {/if}
                {/foreach}
            </td>
            <td>{$group->total_pst|hide_zero|formatprice:",":"."|west_style}</td>
            <td>
                {raw $order->date|date_format:"%d-%b-%Y"}
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_pst_0|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_pst_1_cost_to_us|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_pst_2_shipping|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_3_ref_to_cust == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_pst_3_ref_to_cust|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_4_ref_to_us == 'Y'}class="FilledAccounting"{/if} style="background-color: #B4A7D6;">
                {$group->accounting_pst_4_ref_to_us|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td>
                {$group->accounting_pst_5_profit|hide_zero|formatprice:",":"."|west_style}
            </td>
            {if $data.show_reconciled}
                <td></td>
            {/if}
            <td></td>
        </tr>
        <tr class="OrderSheetCell OrderSheetLast">
            <td width="5"></td>
            <td></td>
            <td></td>
            <td>
                {$group->total_gross|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td>
                {raw $order->date|date_format:"%H:%M:%S"}
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_gross_0|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_gross_1_cost_to_us|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_0 == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_gross_2_shipping|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_3_ref_to_cust == 'Y'}class="FilledAccounting"{/if}>
                {$group->accounting_gross_3_ref_to_cust|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td {if $group->accounting_filled_4_ref_to_us == 'Y'}class="FilledAccounting"{/if} style="background-color: #B4A7D6;">
                {$group->accounting_gross_4_ref_to_us|hide_zero|formatprice:",":"."|west_style}
            </td>
            <td>
                {$group->accounting_gross_5_profit|hide_zero|formatprice:",":"."|west_style}
            </td>
            {if $data.show_reconciled}
                <td></td>
            {/if}
            <td></td>
        </tr>
        <tr class="OrderSheetCell OrderSheetFirst">
            <td colspan="{if $data.show_reconciled !=''}13{else}12{/if}">&nbsp; </td>
        </tr>
    {/foreach}
{/foreach}
</table>