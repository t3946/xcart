<table cellpadding="3" cellspacing="1" class="OrderSheet">
<tr>
    <td class="OrderSheetCell" colspan="2" style="text-align: right;">Distributors:</td>
    <td class="OrderSheetCell" colspan="10" style="text-align: left; font-weight: bold;">
        {$distributorCodes}
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
    <tr class="OrderSheetCell OrderSheetFirst" style="font-weight: bold;">
        <td></td>
        <td></td>
        <td></td>
        <td>{$totals.total_net}</td>
        <td></td>
        <td {if !$form_data.order.profit_margin}style="background-color: #D9EAD3;"{/if}>
             {$totals.accounting_net_0}
        </td>
        <td>{$totals.accounting_net_1_cost_to_us}</td>
        <td>{$totals.accounting_net_2_shipping}</td>
        <td {if !$form_data.order.profit_margin}style="background-color: #D9EAD3;"{/if}>
             {$totals.accounting_net_3_ref_to_cust}
        </td>
        <td {if !$form_data.order.profit_margin}style="background-color: #D9EAD3;"{/if}>
             {$totals.accounting_net_4_ref_to_us}
        </td>
        <td>{$totals.accounting_net_5_profit}</td>
        {if $data.show_reconciled}<td></td>{/if}
        <td>{$totals.total_margin}</td>
    </tr>
    <tr class="OrderSheetCell">
        <td></td>
        <td></td>
        <td><strong>REPORT</strong></td>
        <td>{$totals.gst}</td>
        <td></td>
        <td>{$totals.accounting_gst_0}</td>
        <td>{$totals.accounting_gst_1_cost_to_us}</td>
        <td>{$totals.accounting_gst_2_shipping}</td>
        <td>{$totals.accounting_gst_3_ref_to_cust}</td>
        <td>{$totals.accounting_gst_4_ref_to_us}</td>
        <td>{$totals.accounting_gst_5_profit}</td>
        {if $data.show_reconciled}<td></td>{/if}
        <td></td>
    </tr>
    <tr class="OrderSheetCell">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><strong>TOTALS:</strong></td>
        <td>{$totals.pst}</td>
        <td></td>
        <td>{$totals.accounting_pst_0}</td>
        <td>{$totals.accounting_pst_1_cost_to_us}</td>
        <td>{$totals.accounting_pst_2_shipping}</td>
        <td>{$totals.accounting_pst_3_ref_to_cust}</td>
        <td>{$totals.accounting_pst_4_ref_to_us}</td>
        <td>{$totals.accounting_pst_5_profit}</td>
        {if $data.show_reconciled}<td></td>{/if}
        <td {if !$form_data.order.profit_margin}style="background-color: #D9EAD3;"{/if}>
            {$totals.real_net}
        </td>
    </tr>
    <tr class="OrderSheetCell">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>{$totals.gross}</td>
        <td></td>
        <td>{$totals.accounting_gross_0}</td>
        <td>{$totals.accounting_gross_1_cost_to_us}</td>
        <td>{$totals.accounting_gross_2_shipping}</td>
        <td>{$totals.accounting_gross_3_ref_to_cust}</td>
        <td>{$totals.accounting_gross_4_ref_to_us}</td>
        <td>{$totals.accounting_gross_5_profit}</td>
        {if $data.show_reconciled}<td></td>{/if}
        <td {if $data.profit_margin_range}style="background-color: #D9EAD3;"{/if}>{$totals.real_pm}%</td>
    </tr>