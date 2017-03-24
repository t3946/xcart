{*
$Id: order_accounting_table.tpl, v 1.0.0 2010/04/09 17:03:56 random Exp $
vim: set ts=2 sw=2 sts=2 et:
*}

<script type="text/javascript">
//<![CDATA[
$(function() {ldelim}
  $('#order_tabs-groups_container').tabs(

    {literal}
      {selected: {/literal}{$order_tabs_group_tab_number|default:0}{literal}}
    {/literal}

  );
{rdelim});


{literal}


function func_recalculate_manufacturer_memos_data(m_id, memo_number){
{/literal}
  {foreach from=$order.shipping_groups item=item key=key_m_id}
{literal}

    if (m_id == "{/literal}{$key_m_id}{literal}"){
        
        var ref_to_us_HST = parseFloat($("#manufacturer_memos_data_ref_to_us_HST_"+m_id+"_"+memo_number).val());
        var ref_to_us_total = parseFloat($("#manufacturer_memos_data_ref_to_us_total_"+m_id+"_"+memo_number).val());      
        
        var ref_to_us = ref_to_us_total - ref_to_us_HST;
        $("#ref_to_us_"+m_id+"_"+memo_number).text(price_format(ref_to_us));
    }
{/literal}
  {/foreach}
{literal}
}


function func_recalculate_manufacturer_invoices_data(m_id, invoice_number){
{/literal}
  {foreach from=$order.shipping_groups item=item key=key_m_id}
{literal}

    if (m_id == "{/literal}{$key_m_id}{literal}"){

      var unit_cost_total_sum = parseInt(0);
      var unit_cost_to_us_total_sum = parseInt(0);

{/literal}
      {foreach from=$item.products item=product key=prod_num}
{literal}

          var itemid = {/literal}{$product.itemid}{literal};
//        if (itemid == "{/literal}{$product.itemid}{literal}"){

          var qty_inv = $("#manufacturer_invoices_data_qty_inv_"+m_id+"_"+invoice_number+"_"+itemid).val();
          var unit_cost = $("#manufacturer_invoices_data_unit_cost_"+m_id+"_"+invoice_number+"_"+itemid).val();
          var add_item_value_row = $("#manufacturer_add_extra_value_"+m_id+"_"+invoice_number);
          var add_qty_inv = parseInt(add_item_value_row.find('input[name=add_extra_value_qty]').val());
          var add_cost_inv = parseFloat(add_item_value_row.find('input[name=add_extra_value_cost]').val());

          var unit_cost_total;
          var unit_cost_to_us_total;

          if (qty_inv >= 0){
            unit_cost_total = qty_inv * unit_cost;
            unit_cost_to_us_total = qty_inv * {/literal}{$product.cost_to_us}{literal};

            $("#unit_cost_total_"+m_id+"_"+invoice_number+"_"+itemid).text(price_format(unit_cost_total));
            $("#unit_cost_to_us_total_"+m_id+"_"+invoice_number+"_"+itemid).text(price_format(unit_cost_to_us_total));

            unit_cost_total_sum += parseFloat(unit_cost_total);
            unit_cost_to_us_total_sum += parseFloat(unit_cost_to_us_total);

          }
          if (add_qty_inv >= 0) {
              var add_cost_total = add_qty_inv * add_cost_inv;
              $("#add_extra_value_total_"+m_id+"_"+invoice_number).text(price_format(add_cost_total));
              unit_cost_total_sum += add_cost_total;
          }
//        }
{/literal}
      {/foreach}
{literal}

        $("#cost_to_us_for_products_charged_"+m_id+"_"+invoice_number).text(price_format(unit_cost_total_sum));
        $("#cost_to_us_for_products_in_xcart_"+m_id+"_"+invoice_number).text(price_format(unit_cost_to_us_total_sum));

        var tax_charged_except_HST = parseFloat($("#manufacturer_invoices_data_tax_charged_except_HST_"+m_id+"_"+invoice_number).val());

        var Products_total = tax_charged_except_HST + parseFloat(unit_cost_total_sum);
        $("#Products_total_"+m_id+"_"+invoice_number).text(price_format(Products_total));

        var shipping_charged = parseFloat($("#manufacturer_invoices_data_shipping_charged_"+m_id+"_"+invoice_number).val());
        var drop_ship_fee_charged = parseFloat($("#manufacturer_invoices_data_drop_ship_fee_charged_"+m_id+"_"+invoice_number).val());

        var Shipping_total = shipping_charged + drop_ship_fee_charged;
        $("#Shipping_total_"+m_id+"_"+invoice_number).text(price_format(Shipping_total));

        var HST_charged = parseFloat($("#manufacturer_invoices_data_HST_charged_"+m_id+"_"+invoice_number).val());

        var Invoice_total = Products_total + Shipping_total + HST_charged;
        $("#Invoice_total_"+m_id+"_"+invoice_number).text(price_format(Invoice_total));
    }
{/literal}
  {/foreach}
{literal}
}

{/literal}

//]]>
</script>


<div id="order_tabs-groups_container">

<script type="text/javascript">
        <!--
                var lbl_add = '{$lng.lbl_add|escape}';
                var lbl_remove_row = '{$lng.lbl_remove_row|escape}';
                var ImagesDir = '{$ImagesDir}';
        -->
</script>

{include file="main/include_js.tpl" src="main/manage_distributor_links.js"}
{include file="main/include_js.tpl" src="main/manage_distributor_memo_links.js"}

<input type="hidden" name="order_tabs_group_tab_number" id="order_tabs_group_tab_number" value="{$order_tabs_group_tab_number|default:0}" />
{assign var="tmp_counter" value=0}
<ul>
{foreach from=$order.shipping_groups item=v key=m_id name=groups}

<li><a href="#order_tabs-group{$m_id}" onclick="javascript: $('#order_tabs_group_tab_number').val({$tmp_counter});">{$v.code}</a></li>

{math equation="x+1" x=$tmp_counter assign="tmp_counter"}

{if $v.full_reconciliation_info ne ""}
{assign var="full_reconciliation_info_found" value="Y"}
{/if}
{/foreach}
</ul>


{foreach from=$order.shipping_groups item=v key=m_id name=groups}

<div id="order_tabs-group{$m_id}">


<table cellpadding="3" cellspacing="1" class="OrderSheet">

{if $cycle_state eq "" || $cycle_state eq "first"}
<tr class="TableHead TableHeadAccounting">
  <td width="5">&nbsp;</td>
  <td>{$lng.lbl_c2b_payment|upper}</td>
  <td>{$lng.lbl_customer}</td>
  <td>{$lng.lbl_net}</td>
  <td>{$lng.lbl_processor}</td>
  <td>{$lng.lbl_net}</td>
  <td {if $full_reconciliation_info_found ne ""}onmouseout="javascript: $('#reconciled_lbl_net').hide();" onmouseover="javascript: cidev_showNote('reconciled_lbl_net', this);"{/if}>
  {$lng.lbl_cost_to_us}
  <div id="reconciled_lbl_net" class="cidev_NoteBox" style="display: none; width: 600px; margin-left: -640px;">{$lng.lbl_full_reconciliation_info_found}</div>
  </td>
  <td {if $full_reconciliation_info_found ne ""}onmouseout="javascript: $('#reconciled_lbl_net').hide();" onmouseover="javascript: cidev_showNote('reconciled_lbl_net', this);"{/if}>{$lng.lbl_shipping}</td>
  <td>{$lng.lbl_ref_to_cust}</td>
  <td>{$lng.lbl_ref_to_us}</td>
  <td>{$lng.lbl_profit}</td>
  <td>{$lng.lbl_profit}</td>
</tr>
<tr class="TableHead TableHeadAccounting TableHeadLight">
  <td width="5"><b>#</b></td>
  <td><b>{$lng.lbl_d2c_shipping|upper}</b></td>
  <td>&nbsp;</td>
  <td>{$lng.lbl_gst_in}</td>
  <td><b>{$lng.lbl_payment}</b></td>
  <td>{$lng.lbl_gst_in}</td>
  <td {if $full_reconciliation_info_found ne ""}onmouseout="javascript: $('#reconciled_lbl_net').hide();" onmouseover="javascript: cidev_showNote('reconciled_lbl_net', this);"{/if}>{$lng.lbl_gst_out}</td>
  <td {if $full_reconciliation_info_found ne ""}onmouseout="javascript: $('#reconciled_lbl_net').hide();" onmouseover="javascript: cidev_showNote('reconciled_lbl_net', this);"{/if}>{$lng.lbl_gst_out}</td>
  <td>{$lng.lbl_gst_out}</td>
  <td>{$lng.lbl_gst_in}</td>
  <td>{$lng.lbl_gst_in}</td>
  <td><strong>{$lng.lbl_margin}</strong></td>
</tr>
<tr class="TableHead TableHeadAccounting TableHeadLight">
  <td width="5"><b>{$lng.lbl_distr}</b></td>
  <td><b>B2D INVOICE</b></td>
  <td>&nbsp;</td>
  <td>{$lng.lbl_pst_in}</td>
  <td><b>{$lng.lbl_date}</b></td>
  <td>{$lng.lbl_pst_in}</td>
  <td {if $full_reconciliation_info_found ne ""}onmouseout="javascript: $('#reconciled_lbl_net').hide();" onmouseover="javascript: cidev_showNote('reconciled_lbl_net', this);"{/if}>{$lng.lbl_pst_out}</td>
  <td {if $full_reconciliation_info_found ne ""}onmouseout="javascript: $('#reconciled_lbl_net').hide();" onmouseover="javascript: cidev_showNote('reconciled_lbl_net', this);"{/if}>{$lng.lbl_pst_out}</td>
  <td>{$lng.lbl_pst_out}</td>
  <td>{$lng.lbl_pst_in}</td>
  <td>{$lng.lbl_pst_in}</td>
  <td>REAL NET</td>
</tr>
<tr class="TableHead TableHeadAccounting TableHeadLight">
  <td width="5">&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>{$lng.lbl_gross}</td>
  <td><b>{$lng.lbl_time}</b></td>
  <td>{$lng.lbl_gross}</td>
  <td {if $full_reconciliation_info_found ne ""}onmouseout="javascript: $('#reconciled_lbl_net').hide();" onmouseover="javascript: cidev_showNote('reconciled_lbl_net', this);"{/if}>{$lng.lbl_cost_to_us}</td>
  <td {if $full_reconciliation_info_found ne ""}onmouseout="javascript: $('#reconciled_lbl_net').hide();" onmouseover="javascript: cidev_showNote('reconciled_lbl_net', this);"{/if}>{$lng.lbl_shipping}</td>
  <td>{$lng.lbl_ref_to_cust}</td>
  <td>{$lng.lbl_ref_to_us}</td>
  <td>{$lng.lbl_profit}</td>
  <td>REAL PM</td>
</tr>
{if $static eq 'R'}
<tr class="OrderSheetCell OrderSheetFirst">
<td colspan="12">&nbsp;</td>
</tr>
<tr class="OrderSheetCell OrderSheetFirst" style="font-weight: bold;">
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>{if $data.total.net eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$data.total.net}{/if}</td>
  <td>&nbsp;</td>
  {section loop=5 name="acc"}
  <td {if $data.profit_margin_range eq "margin_100" && ($smarty.section.acc.index eq "1" || $smarty.section.acc.index eq "2")} {else}style="background-color: #D9EAD3;"{/if}> {if $smarty.section.acc.index eq "0"}{if $data.total_accounting[$smarty.section.acc.index].net eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].net}{/if}{else}{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].net}{/if}</td>
  {/section}
  <td>{include file="currency2.tpl" value=$data.total_accounting[5].net show_minus_brackets='Y'}</td>
  <td>{if $data.total_margin lt 0}({/if}{$data.total_margin|price_format|replace:"-":""}%{if $data.total_margin lt 0}){/if}</td>
</tr>
<tr class="OrderSheetCell">
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><strong>{$lng.lbl_report_word}</strong></td>
  <td>{if $data.total.gst eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$data.total.gst hide_zero='Y'}{/if}</td>
  <td>&nbsp;</td>
  {section loop=5 name="acc"}
  <td>{if $smarty.section.acc.index eq "0"}{if $data.total_accounting[$smarty.section.acc.index].gst eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].gst hide_zero='Y'}{/if}{else}{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].gst hide_zero='Y'}{/if}</td>
  {/section}
  <td>{include file="currency2.tpl" value=$data.total_accounting[5].gst show_minus_brackets='Y'}</td>
  <td></td>
</tr>
<tr class="OrderSheetCell">
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><strong>{$lng.lbl_totals_word}:</strong></td>
  <td>{if $data.total.pst eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$data.total.pst hide_zero='Y'}{/if}</td>
  <td>&nbsp;</td>
  {section loop=5 name="acc"}
  <td>{if $smarty.section.acc.index eq "0"}{if $data.total_accounting[$smarty.section.acc.index].pst eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].pst hide_zero='Y'}{/if}{else}{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].pst hide_zero='Y'}{/if}</td>
  {/section}
  <td>{include file="currency2.tpl" value=$data.total_accounting[5].pst show_minus_brackets='Y'}</td>
  <td {if $data.profit_margin_range eq "margin_100"}style="background-color: #D9EAD3;"{/if}>{include file="currency2.tpl" value=$data.real_net}</td>
</tr>
<tr class="OrderSheetCell">
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>{if $data.total.gross eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$data.total.gross}{/if}</td>
  <td></td>
  {section loop=5 name="acc"}
  <td>{if $smarty.section.acc.index eq "0"}{if $data.total_accounting[$smarty.section.acc.index].gross eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].gross}{/if}{else}{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].gross}{/if}</td>
  {/section}
  <td>{include file="currency2.tpl" value=$data.total_accounting[5].gross show_minus_brackets='Y'}</td>
  <td {if $data.profit_margin_range ne "margin_100"}style="background-color: #D9EAD3;"{/if}>{include file="currency2.tpl" value=$data.real_pm}%</td>
</tr>
<tr class="OrderSheetCell OrderSheetFirst">
<td colspan="12">&nbsp;</td>
</tr>
{/if}
{/if}








{if ($v.cb_status eq 'P' || $v.dc_status eq 'C' || $v.dc_status eq 'S' || $v.cb_status eq 'R' || $v.cb_status eq 'H') && $v.acc_paymentid ne 0}
{assign var="show_accounting" value=true}
{else}
{assign var="show_accounting" value=false}
{/if}

{* ----------------- *}
{if $v.acc_paymentid ne "0"}
{assign var="show_accounting" value=true}
{/if}
{* ----------------- *}

{cycle values=", OrderSheetDark" assign="cycle_class"}
<tr class="OrderSheetCell{$cycle_class}{if $v.profit_margin lt 0} OrderSheetRed{else} OrderSheetGreen{/if}{if $smarty.foreach.groups.first} OrderSheetFirst{/if}" style="font-weight: bold;">
  <td width="5">{if $static eq 'Y' || $static eq 'O'}{if $smarty.foreach.groups.first}<input type="checkbox" name="orderids[{$order.orderid}]" />{/if}{else}&nbsp;{/if}</td>
  <td>{include file="main/order_status.tpl" status=$v.cb_status mode="static" status_type="CB"}</td>
  <td nowrap="nowrap" class="OrderSheetCommonCell">{$order.firstname}</td>
  <td>{if $v.total.net eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$v.total.net}{/if}</td>
  <td>
  {if !$static || $static eq 'O'}
  <select name="groups{if $static eq 'O'}[{$v.oOrderGroup->getOrderId()}]{/if}[{$v.oOrderGroup->getManufacturerId()}][paymentid]">
      <option value="0"></option>
      {html_options options=$v.oOrderGroup->getPaymentMethodsAvailForOrderGroup() selected=$v.oOrderGroup->getPaymentMethodId()}
  </select>
  {else}
  {foreach from=$all_processors item=ps key=pid}
  {if $pid eq $v.acc_paymentid}{$ps.payment_method}{/if}
  {/foreach}
  {/if}
  </td>
  {if $show_accounting}
  {assign var="index_num" value=0}
  {section loop=5 name="acc"}
    <td{if $v.accounting[$smarty.section.acc.index].filled eq 'Y'} class="FilledAccounting"{/if} {if $smarty.section.acc.index eq "4"}style="background-color: #B4A7D6;"{/if}>
     {if $index_num eq "0" && $v.accounting[$smarty.section.acc.index].net eq "0.01"}
      0.0001
     {else}
      {if $index_num eq "0" && $v.accounting[$smarty.section.acc.index].net eq "0"}
        {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].net}
      {else}
        {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].net hide_zero='Y'}
      {/if}
     {/if}
    </td>
  {math assign="index_num" equation="x+1" x=$index_num}
  {/section}
  <td>{include file="currency2.tpl" value=$v.accounting[5].net show_minus_brackets='Y'}</td>
  <td>
{*
    {if $v.profit_margin lt 0}({/if}{$v.profit_margin|price_format|replace:"-":""}%{if $v.profit_margin lt 0}){/if}
*}

{if $v.accounting[5].net ne 0}

    {if $v.profit_margin lt 0 || $v.accounting[5].net lt 0}({/if}

    {assign var="profit_margin" value=$v.profit_margin|price_format|replace:"-":""}

    {if $profit_margin eq "0.00"} 
      &infin;
    {else}
      {$profit_margin}
    {/if}
    %

{*
    {$v.profit_margin|price_format|replace:"-":""|replace:"0.00":"&infin;"}%
*}

    {if $v.profit_margin lt 0 || $v.accounting[5].net lt 0}){/if}

{/if}

  </td>
  {else}
  {section loop=7 name="empty_cells"}<td></td>{/section}
  {/if}
</tr>
<tr class="OrderSheetCell{$cycle_class}">
  <td width="5">{* {if $static eq 'Y' || $static eq 'O'} *}<a href="order.php?orderid={$order.orderid}" style="color: blue;" target="_blank">{* {/if} *}<b>{$order.order_prefix}{$order.orderid}</b>{* {if $static} *}</a>{* {/if} *}</td>
  <td class="OrderSheetGreenCell"><b>{include file="main/order_status.tpl" status=$v.dc_status mode="static" status_type="DC"}</b></td>
  <td>{$order.lastname}</td>
  <td>{if $v.total.gst eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$v.total.gst hide_zero='Y'}{/if}</td>
  <td>{$order.payment_method}</td>
  {if $show_accounting}
  <td{if $v.accounting[0].filled eq 'Y'} class="FilledAccounting"{/if}>{if $v.accounting[0].gst eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$v.accounting[0].gst hide_zero='Y'}{/if}</td>
  {section start=1 loop=5 name="acc"}
  <td{if $v.accounting[$smarty.section.acc.index].filled eq 'Y'} class="FilledAccounting"{/if} {if $smarty.section.acc.index eq "4"}style="background-color: #B4A7D6;"{/if}>
  {if !$static || $static eq 'O'}
    {if $smarty.section.acc.index eq $ACC_REF_TO_CUST}
      {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].gst hide_zero='Y'}
      <input type="hidden"name="groups{if $static eq 'O'}[{$order.orderid}]{/if}[{$m_id}][acc][{$smarty.section.acc.index}][gst]" size="8" value="{$v.accounting[$smarty.section.acc.index].gst|price_format}" />
    {else}
        {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].gst hide_zero='Y'}
    {/if}
  {else}
  {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].gst hide_zero='Y'}
  {/if}
  </td>
  {/section}
  <td>{include file="currency2.tpl" value=$v.accounting[5].gst hide_zero='Y' show_minus_brackets='Y'}</td>
  <td></td>
  {else}
  {section loop=7 name="empty_cells"}<td></td>{/section}
  {/if}
</tr>
<tr class="OrderSheetCell{$cycle_class}">
  <td width="5">{if $static eq 'Y' || $static eq 'O'}<a href="order.php?orderid={$order.orderid}" target="_blank">{/if}{$v.code}{if $static}</a>{/if}</td>
  <td class="OrderSheetGreenCell" align="center">

{if $order.amazon_fulfillment_channel eq "AFN"}
<B>I: Reconciled</B><br />
<B>C: Reconciled</B>
{else}
    {if $v.invoices ne ""}
        {foreach from=$v.invoices item=invoice key=invoice_number}
                <B>I-{$invoice_number}: {$invoice_memo_statuses[$invoice.status]}</B><br />
        {/foreach}
    {else}
        <B>I: {$invoice_memo_statuses.N}<br /></B>
    {/if}

    {if $v.memos ne ""}
        {foreach from=$v.memos item=memo key=memo_number}
                <B>C-{$memo_number}: {$invoice_memo_statuses[$memo.status]}</B><br />
        {/foreach}
    {else}
        <B>C: {$invoice_memo_statuses.N}</B>
    {/if}
{/if}

  </td>
  <td>{$order.s_countryname}</td>
  <td>{if $v.total.pst eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$v.total.pst hide_zero='Y'}{/if}</td>
  <td>{$order.date|date_format:"%d-%b-%G"}</td>
  {if $show_accounting}
  <td{if $v.accounting[0].filled eq 'Y'} class="FilledAccounting"{/if}>{if $v.accounting[0].pst eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$v.accounting[0].pst hide_zero='Y'}{/if}</td>
  {section start=1 loop=5 name="acc"}
  <td{if $v.accounting[$smarty.section.acc.index].filled eq 'Y'} class="FilledAccounting"{/if} {if $smarty.section.acc.index eq "4"}style="background-color: #B4A7D6;"{/if}>
  {if !$static || $static eq 'O'}
    {if $smarty.section.acc.index eq $ACC_REF_TO_CUST}
      {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].pst hide_zero='Y'}
      <input type="hidden" name="groups{if $static eq 'O'}[{$order.orderid}]{/if}[{$m_id}][acc][{$smarty.section.acc.index}][pst]" size="8" value="{$v.accounting[$smarty.section.acc.index].pst|price_format}" />
    {else}
        {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].pst hide_zero='Y'}
    {/if}
  {else}
  {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].pst hide_zero='Y'}
  {/if}
  </td>
  {/section}
  <td>{include file="currency2.tpl" value=$v.accounting[5].pst hide_zero='Y' show_minus_brackets='Y'}</td>
  <td></td>
  {else}
  {section loop=7 name="empty_cells"}<td></td>{/section}
  {/if}
</tr>
<tr class="OrderSheetCell{$cycle_class} OrderSheetLast">
  <td width="5"></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>{if $v.total.gross eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$v.total.gross}{/if}</td>
  <td>{$order.date|date_format:"%T"}</td>
  {if $show_accounting}
  <td{if $v.accounting[0].filled eq 'Y'} class="FilledAccounting"{/if}>{if $v.accounting[0].gross eq "0.01"}0.0001{else}{include file="currency2.tpl" value=$v.accounting[0].gross}{/if}</td>

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
function func_check_ref_to_us_part_of_transaction(mid, index){

  var id_ref_to_us_part_of_transaction = "id_ref_to_us_part_of_transaction_"+mid;
  var id_groups_acc_gross = "id_groups_acc_gross_"+index+"_"+mid;

  if ($("#"+id_groups_acc_gross).val() != 0){
    $("#"+id_ref_to_us_part_of_transaction).show();
  } else {
    $("#"+id_ref_to_us_part_of_transaction).hide();
  }
}
{/literal}
-->
</script>

  {assign var="show_ref_to_us_part_of_transaction" value="N"}

  {section start=1 loop=5 name="acc"}
  <td{if $v.accounting[$smarty.section.acc.index].filled eq 'Y'} class="FilledAccounting"{/if} {if $smarty.section.acc.index eq "4"}style="background-color: #B4A7D6;"{/if}>
  {if !$static || $static eq 'O'}
    {if $smarty.section.acc.index eq $ACC_REF_TO_CUST}
      {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].gross hide_zero='Y'}
      <input type="hidden" name="groups{if $static eq 'O'}[{$order.orderid}]{/if}[{$m_id}][acc][{$smarty.section.acc.index}][gross]" size="8" value="{$v.accounting[$smarty.section.acc.index].gross|price_format}" />
    {else}
        {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].gross hide_zero='Y'}

{if $smarty.section.acc.index eq "4"}  
  {if $v.accounting[$smarty.section.acc.index].gross|price_format ne "0.00"}
    {assign var="show_ref_to_us_part_of_transaction" value="Y"}
  {/if}
{/if}

    {/if}
  {else}
  {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].gross hide_zero='Y'}
  {/if}
  </td>
  {/section}
  <td>{include file="currency2.tpl" value=$v.accounting[5].gross show_minus_brackets='Y'}</td>
  <td></td>
  {else}
  {section loop=7 name="empty_cells"}<td></td>{/section}
  {/if}
</tr>


{if $v.ru_status ne ""}
<tr class="OrderSheetCell{$cycle_class}">
<td colspan="9" align="left">

</td>

<td colspan="3" valign="top" align="left">
 {if $v.ru_status ne ""}
  <table cellpadding="0" cellspacing="0" {* width="100%" *} style="background-color: #B4A7D6; margin-top: -3px; margin-left: -3px; padding-top: 3px;">  
  <tr>
  <td align="left">  
    {include file="main/order_status.tpl" status=$v.ru_status mode="static" status_type="RU" extended="Y"}
  </td>
  </tr>
  </table>
 {/if}
</td>
</tr>
{/if}

</table>
<br />


<table width="100%">
<tr>
<td width="50%" align="left">
{if $v.memos eq "" && $v.invoices eq ""}
<input type="button" value="Update" onclick="javascript: $('#mode_accounting_page').val('table_accounting_apply'); $('#certain_mid').val('{$m_id}'); this.form.submit();" />
{/if}
</td>
{assign var="oOrder" value= $v.oOrderGroup->getOrderInstance()}
<td align="right">
{if $v.invoices eq "" && !($oOrder->getAmazonChanell()=='AFN') && !($v.oOrderGroup->isOrderGroupShippedByAmazon())}
<input type="button" value="Invoice received" onclick="javascript: $('#mode_accounting_page').val('invoice_received'); $('#certain_mid').val('{$m_id}'); this.form.submit();" />
{/if}

{if $v.memos eq ""}
&nbsp;
<input type="button" value="Credit memo received" onclick="javascript: $('#mode_accounting_page').val('memo_received'); $('#certain_mid').val('{$m_id}'); this.form.submit();" />
{/if}
</td>
</tr>
</table>






{if $v.all_distributor_info.d_bulk_or_individual_order_payments eq "distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping"}
{$lng.lbl_distributor_charges_for_each_order_twice}
{/if}






{if $v.invoices ne ""}
{foreach from=$v.invoices item=invoice key=invoice_number}

{if $invoice.invoice_received ne "N"}

<br />
<br />

<div align="center"><h1  style="color: #550000;">Invoice # {$order.order_prefix}{$order.orderid}_{$v.code}-I-{$invoice_number}</h1></div>

<a target="_blank" style="color: green;" href="manufacturers.php?manufacturerid={$m_id}&distributor_section=3">{$v.group_name} distributor invoice</a>
{include file="main/subheader.tpl" title=""}

<table cellpadding="3" cellspacing="1">
<tr class="TableHead">
  <td width="350">Product Name</td>
  <td width="80">Item #</td>
  <td width="40">Unit cost</td>
  <td width="40">QTY DISPATCHED</td>
  <td width="40">QTY INVOICED</td>
  <td width="40">Extended</td>
</tr>

{assign var="cost_to_us_for_products_in_xcart" value=0}

{foreach from=$v.products item=product key=prod_num}
{if $invoice.products[$product.itemid] ne ""}


<tr{cycle values=", class='TableSubHead'" name="cycle_`$m_id`"}>

<td>{$product.product}</td>

<td nowrap="nowrap">
{assign var="mpn" value=`$product.mpn`}
{if $order_manufacturers[$m_id].d_website_search_for_sku_url ne ""}
{*  <a style="color: #3A3AFF;" href='{$order_manufacturers[$m_id].d_website_search_for_sku_url|replace:"---mpn---":"$mpn"}' target="_blank">*}{$mpn}{*</a>*}
{/if}
</td>

<td align="center">
<input type="text" size="8" id="manufacturer_invoices_data_unit_cost_{$m_id}_{$invoice_number}_{$product.itemid}" name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][unit_cost][{$product.itemid}]" value="{$invoice.products[$product.itemid].unit_cost}" onkeyup="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" onchange="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" {if $invoice.status eq "R"}readonly="readonly"{/if} />

{* --- *}
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000;" align="right">
{include file="currency2.tpl" value=$product.cost_to_us|price_format}
</div>

{if $product.item_cost_to_us ne ""}
<div style="BACKGROUND-COLOR: #F2A3A8; color: #000000;" align="right">
{if $product.item_cost_to_us ne $product.cost_to_us}
{include file="currency2.tpl" value=$product.item_cost_to_us|price_format}
{else}
Cost to us accurate
{/if}
</div>
{/if}
{* --- *}
</td>

<td align="right">
{assign var="ref_qty" value=0}
{if $order.refund_groups[$m_id].products[$product.itemid].ref_qty ne ""}
{assign var="ref_qty" value=$order.refund_groups[$m_id].products[$product.itemid].ref_qty}
{/if}

{math equation="x-y" x=$product.amount y=$ref_qty assign="qty_disp"}

{$qty_disp}
</td>

<td align="center">
<input type="text" size="5" id="manufacturer_invoices_data_qty_inv_{$m_id}_{$invoice_number}_{$product.itemid}" name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][qty_inv][{$product.itemid}]" value="{$invoice.products[$product.itemid].qty_inv}" onkeyup="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" onchange="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" {if $invoice.status eq "R"}readonly="readonly"{/if} />
</td>

<td align="right">
<span id="unit_cost_total_{$m_id}_{$invoice_number}_{$product.itemid}">{include file="currency2.tpl" value=$invoice.products[$product.itemid].unit_cost_total}</span>

<div style="BACKGROUND-COLOR: #FFD44C; color: #000000;" align="right">
{math equation="x*y" x=$product.cost_to_us y=$invoice.products[$product.itemid].qty_inv assign="unit_cost_to_us_total"}
<span id="unit_cost_to_us_total_{$m_id}_{$invoice_number}_{$product.itemid}">{include file="currency2.tpl" value=$unit_cost_to_us_total|price_format}</span>
</div>

{math equation="x+y" x=$cost_to_us_for_products_in_xcart y=$unit_cost_to_us_total assign="cost_to_us_for_products_in_xcart"}
</td>

</tr>

{/if}
{/foreach}

<tr>
<td colspan="6">
<input type="checkbox" value="Y" name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][extra_items_on_invoice]" {if $invoice.extra_items_on_invoice eq "Y"}checked{/if} /> Extra items are present on the invoice.
</td>
</tr>
<tr class="manufacturer_add_extra_value" data-mnfid="{$m_id}" data-invoice="{$invoice_number}">
    <td colspan="2" id="add_extra_track_{$m_id}_{$invoice_number}_box_1">
        <select name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][add_extra_value_type][]">
            <option value="add_extra_sku">Product SKU</option>
            <option value="add_extra_other">Other charges</option>
        </select>
        <input size="40" name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][add_extra_value_string][]" type="text" value="" />
    </td>
    <td id="add_extra_track_{$m_id}_{$invoice_number}_box_2" align="center"><input onkeyup="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" onchange="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" size="8" name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][add_extra_value_cost][]" type="text" value="" /></td>
    <td id="add_extra_track_{$m_id}_{$invoice_number}_box_3"></td>
    <td id="add_extra_track_{$m_id}_{$invoice_number}_box_4" align="center"><input onkeyup="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" onchange="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" size="5"name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][add_extra_value_qty][]" type="text" value="" /></td>
    <td id="add_extra_track_{$m_id}_{$invoice_number}_box_5" align="right" class="add_extra_value_total">
        <span id="add_extra_value_total_{$m_id}_{$invoice_number}"></span>
    </td>
    <td>{include file="buttons/multirow_add.tpl" mark="add_extra_track_`$m_id`_`$invoice_number`"}
    </td>
</tr>
<tr>
<td>
Cost to us for the products charged
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000;">
Cost to us for the products in X-cart
</div>
</td>
<td colspan="4"></td>
<td align="right">
<span id="cost_to_us_for_products_charged_{$m_id}_{$invoice_number}">
{include file="currency2.tpl" value=$invoice.cost_to_us_for_products_charged}
</span>
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000;" align="right">
<span id="cost_to_us_for_products_in_xcart_{$m_id}_{$invoice_number}">
{include file="currency2.tpl" value=$cost_to_us_for_products_in_xcart|price_format}
</span>
</div>
</td>
</tr>

<tr>
<td>
Tax charged (except HST)
</td>
<td colspan="4"></td>
<td align="center">
  <input id="manufacturer_invoices_data_tax_charged_except_HST_{$m_id}_{$invoice_number}" name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][tax_charged_except_HST]" size="8" value="{$invoice.tax_charged_except_HST}" onkeyup="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" onchange="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" {if $invoice.status eq "R"}readonly="readonly"{/if} />
</td>
</tr>

<tr>
<td>
<B>Products total</B>
<br />
</td>
<td colspan="4"></td>
<td align="right">
<B><span id="Products_total_{$m_id}_{$invoice_number}">{include file="currency2.tpl" value=$invoice.products_total}</span></B>
</td>
</tr>

<tr>
<td>
Shipping charged
<div style="BACKGROUND-COLOR: #F2A3A8; color: #000000;">
Shipping quoted by distributor
</div>
</td>
<td colspan="4"></td>
<td align="center">

  <input id="manufacturer_invoices_data_shipping_charged_{$m_id}_{$invoice_number}" name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][shipping_charged]" size="8" value="{$invoice.shipping_charged}" onkeyup="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" onchange="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" {if $invoice.status eq "R"}readonly="readonly"{/if} />

<div style="BACKGROUND-COLOR: #F2A3A8; color: #000000;" align="right">
{include file="currency2.tpl" value=$v.actual_shipping_cost.net}
</div>
</td>
</tr>

<tr>
<td colspan="6">
 <table>
 <tr>
 <td>
<input type="checkbox" value="Y" name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][items_shipped_to_wrong_address]" {if $invoice.items_shipped_to_wrong_address eq "Y"}checked{/if} /> 
 </td>
 <td>
Items are shipped to an address that is different from 
 </td>
 <td>
<a onclick="javascript: $('#customers_shipping_address_{$m_id}_{$invoice_number}').toggle();" style="color: blue; border-bottom:1px dotted; text-decoration: none;" href="javascript: void(0);">the customer's shipping address</a>.

<div id="customers_shipping_address_{$m_id}_{$invoice_number}" class="cidev_NoteBox" style="display: none; margin-left: 0px; color: #550000; text-align: left; border: 1px solid #ff6600;">
 <table border="0">
  <tr><td nowrap="nowrap"><B>{$lng.lbl_first_name}:</B></td><td>&nbsp;</td><td>{$customer.s_firstname}</td></tr>

{foreach from=$customer.additional_fields item=v_a}
{if $v_a.section eq 'S'}
  <tr>
    <td>{if $v_a.title ne "Company"}<b>{/if}{$v_a.title}:{if $v_a.title ne "Company"}</b>{/if}</td><td>&nbsp;</td>
        <td nowrap="nowrap">{$v_a.value}</td>
  </tr>
{/if}
{/foreach}

  <tr><td nowrap="nowrap"><B>{$lng.lbl_address}:</B></td><td>&nbsp;</td><td>{$customer.s_address}</td></tr>
  <tr><td nowrap="nowrap">{$lng.lbl_address_2}:</td><td>&nbsp;</td><td>{$customer.s_address_2}</td></tr>
  <tr><td nowrap="nowrap"><B>{$lng.lbl_city}:</B></td><td>&nbsp;</td><td>{$customer.s_city}</td></tr>
  <tr><td nowrap="nowrap"><B>{$lng.lbl_state}:</B></td><td>&nbsp;</td><td>{$customer.s_statename} ({$customer.s_state})</td></tr>
  <tr><td nowrap="nowrap"><B>{$lng.lbl_country}:</B></td><td>&nbsp;</td><td>{$customer.s_countryname}</td></tr>
  <tr><td nowrap="nowrap"><B>{$lng.lbl_zip_code}:</B></td><td>&nbsp;</td><td>{$customer.s_zipcode}</td></tr>
 </table>
</div>
 </td>
 </tr>
 </table>
</td>
</tr>

<tr>
<td>
Drop-ship fee charged
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000;">
Drop-ship fee in X-cart
</div>
</td>
<td colspan="4"></td>
<td align="center">
  <input id="manufacturer_invoices_data_drop_ship_fee_charged_{$m_id}_{$invoice_number}" name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][drop_ship_fee_charged]" size="8" value="{$invoice.drop_ship_fee_charged}" onkeyup="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" onchange="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" {if $invoice.status eq "R"}readonly="readonly"{/if} />

<div style="BACKGROUND-COLOR: #FFD44C; color: #000000;" align="right">

{if $v.real_drop_ship_fee ne ""}
  {include file="currency2.tpl" value=$v.real_drop_ship_fee}
{else}
  {include file="currency2.tpl" value=$order_manufacturers[$m_id].d_drop_ship_fee_in_us}
{/if}

</div>
</td>
</tr>

<tr>
<td>
<B>Shipping total</B>
<br />
</td>
<td colspan="4"></td>
<td align="right">
<B><span id="Shipping_total_{$m_id}_{$invoice_number}">{include file="currency2.tpl" value=$invoice.shipping_total}</span></B>
</td>
</tr>

<tr>
<td>
HST charged
</td>
<td colspan="4"></td>
<td align="center">
  <input id="manufacturer_invoices_data_HST_charged_{$m_id}_{$invoice_number}" name="manufacturer_invoices_data[{$m_id}][{$invoice_number}][HST_charged]" size="8" value="{$invoice.HST_charged}" onkeyup="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" onchange="func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}')" {if $invoice.status eq "R"}readonly="readonly"{/if} />
</td>
</tr>

<tr><td colspan="6"><hr /></td></tr>

<tr>
<td>
<span style="font-weight: bold; font-size: 14px;">Invoice total</span>
</td>
<td colspan="4"></td>
<td align="right">
<B><span id="Invoice_total_{$m_id}_{$invoice_number}" style="font-size: 14px;">{include file="currency2.tpl" value=$invoice.invoice_total}</span></B>
</td>
</tr>

</table>

<br />







        <input type="hidden" id="row_max_index_{$m_id}_{$invoice_number}" name="row_max_index_{$m_id}_{$invoice_number}" value="{if $all_distributor_links.$m_id.distributor_links.count_links_to_distributor_invoices}{$all_distributor_links.$m_id.distributor_links.count_links_to_distributor_invoices}{else}1{/if}" />
        <table cellpadding="0" cellspacing="0">
        {if $all_distributor_links.$m_id.distributor_links.$invoice_number}
                {foreach from=$all_distributor_links.$m_id.distributor_links.$invoice_number item="distributor_link" key=key name="depforeach"}

                <tr id="distributor_link_row_{$m_id}_{$invoice_number}_{$key}">
                        <td style="padding: 2px 0px;">

Link to distributor invoice&nbsp;<input type="text" size="40" name="links_to_distributor_invoices[{$m_id}][{$invoice_number}][{$key}][link_to_distributor_invoice]" value="{$distributor_link.link_to_distributor_invoice|escape}" />

&nbsp;<a href="javascript: void(0);" onclick="javascript: add_distributor_link_row('{$key}', '{$m_id}', '{$invoice_number}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
&nbsp;<a href="javascript: void(0);" onclick="javascript: remove_distributor_link_row('{$key}', '{$m_id}', '{$invoice_number}');"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_remove_row|escape:'javascript'}" /></a>

&nbsp;&nbsp;<a style="color: #3A3AFF; font-weight: normal;" href='{$distributor_link.link_to_distributor_invoice}' target="_blank">View invoice</a>

                        </td>
                </tr>

                {/foreach}
        {else}
                <tr id="distributor_link_row_{$m_id}_{$invoice_number}_1">
                        <td style="padding: 2px 0px;">
                                Link to distributor invoice&nbsp;<input type="text" size="40" name="links_to_distributor_invoices[{$m_id}][{$invoice_number}][1][link_to_distributor_invoice]" value="" />
&nbsp;<a href="javascript: void(0);" onclick="javascript: add_distributor_link_row(1, '{$m_id}', '{$invoice_number}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>

                        </td>
                </tr>
        {/if}
        </table>






{if $order_manufacturers[$m_id].d_bulk_or_individual_order_payments eq "distributor_may_charge_for_several_orders_at_once"}
  <table cellpadding="0" cellspacing="0">
  <tr>
  <td>This invoice is a part of the total transaction in the amount of&nbsp;</td>
  <td>
{*  <input type="text" name="part_of_total_transaction_in_amount_of[{$m_id}]" value="{$v.part_of_total_transaction_in_amount_of}" size="6" /> *}
  <input type="text" name="part_of_total_transaction_in_amount_of[{$m_id}][{$invoice_number}]" value="{$invoice.part_of_total_transaction_in_amount_of}" size="6" />
  </td>
  </tr>
  </table>
{/if}


  {if $invoice.full_reconciliation_info ne "" && $invoice.status eq "R"}
Invoice payment in the amount of ({$invoice.full_reconciliation_info.amount_csv_abs|price_format}) taken on {$invoice.full_reconciliation_info.date_csv|date_format:"%d-%b-%G"}:<br />
{$invoice.full_reconciliation_info.description_csv}
  {/if}




<br />
<br />





<script type="text/javascript">
<!--
multirowInputSets['acc_track_{$m_id}_{$invoice_number}'] = [];
multirowInputSets['acc_track_{$m_id}_{$invoice_number}'].noCloneContent = 1;
-->
</script>

<table cellpadding="1" cellspacing="1" border="0">
<tr class="TableHead">
  <td>Ship date</td>
  <td>Carrier</td>
  <td width="240">Shipping method</td>
  <td colspan="2">Tracking number</td>
</tr>

<tr>
  <td colspan="3"></td>
  <td colspan="2">
    {if $v.tracking}

      {assign var="row_conter" value="0"}
      {foreach from=$v.tracking item=t}

       {math equation="x+1" x=$row_conter assign="row_conter"}
       {assign var="current_carrier_id" value=$t.carrier_id}

       {if ($t.invoice_number eq $invoice_number) || ($t.invoice_number eq "" && $invoice_number eq "1")}

        <div id="acc_tracknum_{$m_id}_{$invoice_number}_{$row_conter}">
          {if $t.tracknum ne ""}
            <a href="{$tracking_links_carrier[$current_carrier_id].link|substitute:"tracknum":$t.tracknum}" target="_blank">Shipped{if $t.ship_date ne ""} on {$t.ship_date}{/if} by {$tracking_links_carrier[$current_carrier_id].carrier}{if $tracking_links[$t.linkid].shipping ne ""} {$tracking_links[$t.linkid].shipping}{/if}: {$t.tracknum}</a>
          {else}
            Shipped{if $t.ship_date ne ""} on {$t.ship_date}{/if} by {$tracking_links_carrier[$current_carrier_id].carrier}{if $tracking_links[$t.linkid].shipping ne ""} {$tracking_links[$t.linkid].shipping}{/if}: {$tracking_links_carrier[$current_carrier_id].link}
          {/if}

          <a href="javascript: void(0);" onclick="javascript: $('#acc_tracknum_val_{$m_id}_{$invoice_number}_{$row_conter}').val(''); $('#acc_tracknum_link_{$m_id}_{$invoice_number}_{$row_conter}').val('');  $('#acc_tracknum_carrier_id_{$m_id}_{$invoice_number}_{$row_conter}').val(''); $('#acc_tracknum_{$m_id}_{$invoice_number}_{$row_conter}').hide();"><img src="{$ImagesDir}/minus.gif" /></a>

          <input type="hidden" name="tracknums[{$m_id}][{$invoice_number}][{$row_conter}][tracknum]" value="{$t.tracknum}" id="acc_tracknum_val_{$m_id}_{$invoice_number}_{$row_conter}" />
          <input type="hidden" name="tracknums[{$m_id}][{$invoice_number}][{$row_conter}][linkid]" value="{$t.linkid|default:0}" id="acc_tracknum_link_{$m_id}_{$invoice_number}_{$row_conter}" />
          <input type="hidden" name="tracknums[{$m_id}][{$invoice_number}][{$row_conter}][ship_date]" value="{$t.ship_date}" id="acc_tracknum_ship_date_{$m_id}_{$invoice_number}_{$row_conter}" />
          <input type="hidden" name="tracknums[{$m_id}][{$invoice_number}][{$row_conter}][carrier_id]" value="{$t.carrier_id}" id="acc_tracknum_carrier_id_{$m_id}_{$invoice_number}_{$row_conter}" />

          </div>

       {/if}

      {/foreach}
    {else}
      &nbsp;
    {/if}
  </td>
</tr>


<tr id="acc_track_{$m_id}_{$invoice_number}_tr">

  <td id="acc_track_{$m_id}_{$invoice_number}_box_3" style="padding-right: 5px;">

  <input type="text" id="tracking_ship_date_{$m_id}_{$invoice_number}_box_0" name="groups[{$m_id}][tracking_ship_date][{$invoice_number}][0]" value="" size="15" {* {if $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly" {/if} *} onclick="javascript: $(this).datepicker(); /* $(this).datepicker('option', 'dateFormat', 'MM d, yy'); */ $(this).datepicker('show');" />
  </td>

  <td id="acc_track_{$m_id}_{$invoice_number}_box_4" style="padding-right: 10px;">
  <select id="tracking_carrier_{$m_id}_{$invoice_number}_box_0" name="groups[{$m_id}][tracking_carrier][{$invoice_number}][0]" {* {if $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if} *} onchange="func_set_tracking_shipping(this, '{$m_id}', '{$invoice_number}');">
  <option value=""></option>
{foreach from=$tracking_links_carrier item=vvv key=carrier_id}
  <option value="{$carrier_id}">{$vvv.carrier}</option>
{/foreach}
  </select>
  </td>

  <td id="acc_track_{$m_id}_{$invoice_number}_box_1" style="padding-right: 10px;">
  <select id="tracking_shipping_{$m_id}_{$invoice_number}_box_0" name="groups[{$m_id}][tracking_shipper][{$invoice_number}][0]" {* {if $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if} *} style="width: 100%;">
  <option value="">select carrier</option>

{*
{foreach from=$tracking_links item=vvv key=linkid}
  <option value="{$linkid}">{$vvv.shipping}</option>
{/foreach}
*}

  </select>
  </td>
  <td id="acc_track_{$m_id}_{$invoice_number}_box_2" style="padding-right: 5px;">
  <input type="text" name="groups[{$m_id}][tracking_number][{$invoice_number}][0]" value="" size="40" {* {if $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly" {/if} *} />
  </td>

  <td width="30">
{*{if !($v.allow_dispatch_off_working_hours_functionality_enabled eq "Y")}*}
{include file="buttons/multirow_add.tpl" mark="acc_track_`$m_id`_`$invoice_number`"}
{*{/if}*}
  </td>
</tr>

</table>





{*
<script type="text/javascript" language="JavaScript 1.2">
<!--
func_recalculate_manufacturer_invoices_data('{$m_id}','{$invoice_number}');
-->
</script>
*}

{/if}

<br />
<div style="BACKGROUND-COLOR: {if $invoice.status eq "A"}#F2A3A8;{elseif $invoice.status eq "U"}#ffd44c;{elseif $invoice.status eq "R"}#d9ead3;{/if} color: #000000;">
<B>Invoice status: {$invoice_memo_statuses[$invoice.status]}</B>
</div>

{if $invoice.invoice_total eq "0.00"}
<br />
<div align="left">
<input type="button" value="Delete invoice" onclick="javascript: $('#mode_accounting_page').val('delete_invoice'); $('#certain_mid').val('{$m_id}'); $('#certain_invoice_number').val('{$invoice_number}'); this.form.submit();" />
</div>
{/if}

{/foreach} {* // foreach from=$v.invoices item=invoice key=invoice_number *}



{if !$static}
<br />
{*
<input type="button" value="Update" onclick="javascript: $('#mode_accounting_page').val('accounting_apply'); $('#certain_mid').val('{$m_id}'); this.form.submit();" />
&nbsp;
*}
<div align="right">
<input type="button" value="Additional invoice received" onclick="javascript: $('#mode_accounting_page').val('additional_invoice_received'); $('#certain_mid').val('{$m_id}'); this.form.submit();" />
</div>
{/if}

{/if} {* // if $v.invoices ne "" *}






{if $v.memos ne ""}

<br />
<br />
<hr />
<br />
{foreach from=$v.memos item=memo key=memo_number}

{if $memo.memo_received ne "N"}

<br />
<br />
<div align="center"><h1  style="color: #550000;">Credit memo # {$order.order_prefix}{$order.orderid}_{$v.code}-C-{$memo_number}</h1></div>

<a target="_blank" style="color: green;" href="manufacturers.php?manufacturerid={$m_id}&distributor_section=3">{$v.group_name} credit memo</a>
{include file="main/subheader.tpl" title=""}

<table cellpadding="3" cellspacing="1">
<tr class="TableHead">
  <td width="350">Credit memo description</td>
  <td width="40">Amount</td>
</tr>

<tr>
<td>
<input name="manufacturer_memos_data[{$m_id}][{$memo_number}][memo_descr]" size="18" style="width: 90%;" value="{$memo.memo_descr}" />
</td>
<td align="right">
<span id="ref_to_us_{$m_id}_{$memo_number}">{include file="currency2.tpl" value=$memo.ref_to_us}</span>
</td>
</tr>

<tr>
<td>
HST refunded
</td>
<td align="center">
  <input id="manufacturer_memos_data_ref_to_us_HST_{$m_id}_{$memo_number}" name="manufacturer_memos_data[{$m_id}][{$memo_number}][ref_to_us_HST]" size="8" value="{$memo.ref_to_us_HST}" onkeyup="func_recalculate_manufacturer_memos_data('{$m_id}','{$memo_number}')" onchange="func_recalculate_manufacturer_memos_data('{$m_id}','{$memo_number}')" {if $memo.status eq "R"}readonly="readonly"{/if} />
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>

<tr>
<td>
<span style="font-weight: bold; font-size: 14px;">Credit memo total</span>
</td>
<td align="center">
  <input style="font-size: 14px; font-weight: bold;" id="manufacturer_memos_data_ref_to_us_total_{$m_id}_{$memo_number}" name="manufacturer_memos_data[{$m_id}][{$memo_number}][ref_to_us_total]" size="6" value="{$memo.ref_to_us_total}" onkeyup="func_recalculate_manufacturer_memos_data('{$m_id}','{$memo_number}')" onchange="func_recalculate_manufacturer_memos_data('{$m_id}','{$memo_number}')" {if $memo.status eq "R"}readonly="readonly"{/if} />
</td>
</tr>

</table>

<br />






        <input type="hidden" id="row_max_index_{$m_id}_{$memo_number}" name="row_max_index_{$m_id}_{$memo_number}" value="{if $all_distributor_memo_links.$m_id.distributor_memo_links.count_links_to_distributor_memos}{$all_distributor_memo_links.$m_id.distributor_memo_links.count_links_to_distributor_memos}{else}1{/if}" />
        <table cellpadding="0" cellspacing="0">
        {if $all_distributor_memo_links.$m_id.distributor_memo_links.$memo_number}
                {foreach from=$all_distributor_memo_links.$m_id.distributor_memo_links.$memo_number item="distributor_memo_link" key=key name="depforeach"}

                <tr id="distributor_memo_link_row_{$m_id}_{$memo_number}_{$key}">
                        <td style="padding: 2px 0px;">

Link to distributor credit memo&nbsp;<input type="text" size="40" name="links_to_distributor_memos[{$m_id}][{$memo_number}][{$key}][link_to_distributor_memo]" value="{$distributor_memo_link.link_to_distributor_memo|escape}" />

&nbsp;<a href="javascript: void(0);" onclick="javascript: add_distributor_memo_link_row('{$key}', '{$m_id}', '{$memo_number}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
&nbsp;<a href="javascript: void(0);" onclick="javascript: remove_distributor_memo_link_row('{$key}', '{$m_id}', '{$memo_number}');"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_remove_row|escape:'javascript'}" /></a>

&nbsp;&nbsp;<a style="color: #3A3AFF; font-weight: normal;" href='{$distributor_memo_link.link_to_distributor_memo}' target="_blank">View credit memo</a>

                        </td>
                </tr>

                {/foreach}
        {else}
                <tr id="distributor_memo_link_row_{$m_id}_{$memo_number}_1">
                        <td style="padding: 2px 0px;">
                                Link to distributor credit memo&nbsp;<input type="text" size="40" name="links_to_distributor_memos[{$m_id}][{$memo_number}][1][link_to_distributor_memo]" value="" />
&nbsp;<a href="javascript: void(0);" onclick="javascript: add_distributor_memo_link_row(1, '{$m_id}', '{$memo_number}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>

                        </td>
                </tr>
        {/if}
        </table>






{if $order_manufacturers[$m_id].d_bulk_or_individual_order_payments eq "distributor_may_charge_for_several_orders_at_once"}
  <table cellpadding="0" cellspacing="0">
  <tr>
  <td>This credit memo is a part of the total transaction in the amount of&nbsp;</td>
  <td>
  <input type="text" name="ref_to_us_part_of_transaction[{$m_id}][{$memo_number}]" value="{$memo.ref_to_us_part_of_transaction}" size="6" />
  </td>
  </tr>
  </table>
{/if}

  {if $memo.full_reconciliation_info ne "" && $memo.status eq "R"}
<br />
(REF) Invoice payment in the amount of ({$memo.full_reconciliation_info.amount_csv_abs|price_format}) taken on {$memo.full_reconciliation_info.date_csv|date_format:"%d-%b-%G"}:<br />
{$memo.full_reconciliation_info.description_csv}
  {/if}


{/if}

<br />
<div style="BACKGROUND-COLOR: {if $memo.status eq "A"}#F2A3A8;{elseif $memo.status eq "U"}#ffd44c;{elseif $memo.status eq "R"}#d9ead3;{/if} color: #000000;">
<B>Credit memo status: {$invoice_memo_statuses[$memo.status]}</B>
</div>

{/foreach} {* // foreach from=$v.memos item=memo key=memo_number *}



{if !$static}
<br />
<div align="right">
<input type="button" value="Additional credit memo received" onclick="javascript: $('#mode_accounting_page').val('additional_memo_received'); $('#certain_mid').val('{$m_id}'); this.form.submit();" />
</div>
{/if}

{/if} {* // if $v.memos ne "" *}


{if !$static && ($v.memos ne "" || $v.invoices ne "")}
<br />
<br />
<hr />
<br />
<div align="center">
<input style="font-size: 14px;" type="button" value="Update" onclick="javascript: $('#mode_accounting_page').val('accounting_apply'); $('#certain_mid').val('{$m_id}'); this.form.submit();" />
{if $order_manufacturers[$m_id].distributor_charges_for_each_order_twice_and_split_invoices == 'Y' && count($v.invoices) == 1}
<span style="color: #f00000; line-height:25px; position: absolute; right: 21px; font-size: 13px; font-weight: bold;">!Invoice could be splitted after Update. That's OK</span>
{/if}
</div>
{/if}


</div>
{/foreach}

</div>
