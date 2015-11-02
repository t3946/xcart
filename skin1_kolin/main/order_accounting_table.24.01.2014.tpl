{*
$Id: order_accounting_table.tpl, v 1.0.0 2010/04/09 17:03:56 random Exp $
vim: set ts=2 sw=2 sts=2 et:
*}


<script type="text/javascript">
        <!--
                var lbl_add = '{$lng.lbl_add|escape}';
                var lbl_remove_row = '{$lng.lbl_remove_row|escape}';
                var ImagesDir = '{$ImagesDir}';
        -->
</script>

{include file="main/include_js.tpl" src="main/manage_distributor_links.js"}



{if $cycle_state eq ""}
<table cellpadding="3" cellspacing="1" class="OrderSheet">
{/if}
{if $cycle_state eq "" || $cycle_state eq "first"}
<tr class="TableHead TableHeadAccounting">
  <td width="5">&nbsp;</td>
  <td>{$lng.lbl_c2b_payment|upper}</td>
  <td>{$lng.lbl_customer}</td>
  <td>{$lng.lbl_net}</td>
  <td>{$lng.lbl_processor}</td>
  <td>{$lng.lbl_net}</td>
  <td>{$lng.lbl_cost_to_us}</td>
  <td>{$lng.lbl_shipping}</td>
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
  <td>{$lng.lbl_gst_out}</td>
  <td>{$lng.lbl_gst_out}</td>
  <td>{$lng.lbl_gst_out}</td>
  <td>{$lng.lbl_gst_in}</td>
  <td>{$lng.lbl_gst_in}</td>
  <td><strong>{$lng.lbl_margin}</strong></td>
</tr>
<tr class="TableHead TableHeadAccounting TableHeadLight">
  <td width="5"><b>{$lng.lbl_distr}</b></td>
  <td><b>{$lng.lbl_b2d_payment|upper}</b></td>
  <td>&nbsp;</td>
  <td>{$lng.lbl_pst_in}</td>
  <td><b>{$lng.lbl_date}</b></td>
  <td>{$lng.lbl_pst_in}</td>
  <td>{$lng.lbl_pst_out}</td>
  <td>{$lng.lbl_pst_out}</td>
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
  <td>{$lng.lbl_cost_to_us}</td>
  <td>{$lng.lbl_shipping}</td>
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
  <td>{include file="currency2.tpl" value=$data.total.net}</td>
  <td>&nbsp;</td>
  {section loop=5 name="acc"}
  <td {if $data.include_margin_100 eq "Y" && ($smarty.section.acc.index eq "1" || $smarty.section.acc.index eq "2")} {else}style="background-color: #D9EAD3;"{/if}>{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].net}</td>
  {/section}
  <td>{include file="currency2.tpl" value=$data.total_accounting[5].net show_minus_brackets='Y'}</td>
  <td>{$data.total_margin|price_format}%</td>
</tr>
<tr class="OrderSheetCell">
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><strong>{$lng.lbl_report_word}</strong></td>
  <td>{include file="currency2.tpl" value=$data.total.gst hide_zero='Y'}</td>
  <td>&nbsp;</td>
  {section loop=5 name="acc"}
  <td>{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].gst hide_zero='Y'}</td>
  {/section}
  <td>{include file="currency2.tpl" value=$data.total_accounting[5].gst show_minus_brackets='Y'}</td>
  <td></td>
</tr>
<tr class="OrderSheetCell">
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><strong>{$lng.lbl_totals_word}:</strong></td>
  <td>{include file="currency2.tpl" value=$data.total.pst hide_zero='Y'}</td>
  <td>&nbsp;</td>
  {section loop=5 name="acc"}
  <td>{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].pst hide_zero='Y'}</td>
  {/section}
  <td>{include file="currency2.tpl" value=$data.total_accounting[5].pst show_minus_brackets='Y'}</td>
  <td {if $data.include_margin_100 eq "Y"}style="background-color: #D9EAD3;"{/if}>{include file="currency2.tpl" value=$data.real_net}</td>
</tr>
<tr class="OrderSheetCell">
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>{include file="currency2.tpl" value=$data.total.gross}</td>
  <td></td>
  {section loop=5 name="acc"}
  <td>{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].gross}</td>
  {/section}
  <td>{include file="currency2.tpl" value=$data.total_accounting[5].gross show_minus_brackets='Y'}</td>
  <td {if $data.include_margin_100 ne "Y"}style="background-color: #D9EAD3;"{/if}>{include file="currency2.tpl" value=$data.real_pm}%</td>
</tr>
<tr class="OrderSheetCell OrderSheetFirst">
<td colspan="12">&nbsp;</td>
</tr>
{/if}
{/if}
{foreach from=$order.shipping_groups item=v key=m_id name=groups}
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
  <td>{include file="currency2.tpl" value=$v.total.net}</td>
  <td>
  {if !$static || $static eq 'O'}
  <select name="groups{if $static eq 'O'}[{$order.orderid}]{/if}[{$m_id}][paymentid]">
  <option value="0"{if $v.acc_paymentid eq 0} selected="selected"{/if}></option>
  {foreach from=$all_processors item=ps key=pid}
  <option value="{$pid}"{if $pid eq $v.acc_paymentid} selected="selected"{/if}>{$ps.payment_method}</option>
  {/foreach}
  </select>
  {else}
  {foreach from=$all_processors item=ps key=pid}
  {if $pid eq $v.acc_paymentid}{$ps.payment_method}{/if}
  {/foreach}
  {/if}
  </td>
  {if $show_accounting}
  {section loop=5 name="acc"}
    <td{if $v.accounting[$smarty.section.acc.index].filled eq 'Y'} class="FilledAccounting"{/if}>
      {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].net hide_zero='Y'}
    </td>
  {/section}
  <td>{include file="currency2.tpl" value=$v.accounting[5].net show_minus_brackets='Y'}</td>
  <td>{$v.profit_margin|price_format}%</td>
  {else}
  {section loop=7 name="empty_cells"}<td></td>{/section}
  {/if}
</tr>
<tr class="OrderSheetCell{$cycle_class}">
  <td width="5">{if $static eq 'Y' || $static eq 'O'}<a href="order.php?orderid={$order.orderid}" target="_blank">{/if}<b>{$order.order_prefix}{$order.orderid}</b>{if $static}</a>{/if}</td>
  <td class="OrderSheetGreenCell"><b>{include file="main/order_status.tpl" status=$v.dc_status mode="static" status_type="DC"}</b></td>
  <td>{$order.lastname}</td>
  <td>{include file="currency2.tpl" value=$v.total.gst hide_zero='Y'}</td>
  <td>{$order.payment_method}</td>
  {if $show_accounting}
  <td{if $v.accounting[0].filled eq 'Y'} class="FilledAccounting"{/if}>{include file="currency2.tpl" value=$v.accounting[0].gst hide_zero='Y'}</td>
  {section start=1 loop=5 name="acc"}
  <td{if $v.accounting[$smarty.section.acc.index].filled eq 'Y'} class="FilledAccounting"{/if}>
  {if !$static || $static eq 'O'}
    {if $smarty.section.acc.index eq $ACC_REF_TO_CUST}
      {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].gst hide_zero='Y'}
      <input type="hidden"name="groups{if $static eq 'O'}[{$order.orderid}]{/if}[{$m_id}][acc][{$smarty.section.acc.index}][gst]" size="8" value="{$v.accounting[$smarty.section.acc.index].gst|price_format}" />
    {else}
  <input name="groups{if $static eq 'O'}[{$order.orderid}]{/if}[{$m_id}][acc][{$smarty.section.acc.index}][gst]" size="8" value="{$v.accounting[$smarty.section.acc.index].gst|price_format}" />
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
  <td class="OrderSheetGreenCell"><b>{include file="main/order_status.tpl" status=$v.bd_status mode="static" status_type="BD"}</b></td>
  <td>{$order.s_countryname}</td>
  <td>{include file="currency2.tpl" value=$v.total.pst hide_zero='Y'}</td>
  <td>{$order.date|date_format:"%d-%b-%G"}</td>
  {if $show_accounting}
  <td{if $v.accounting[0].filled eq 'Y'} class="FilledAccounting"{/if}>{include file="currency2.tpl" value=$v.accounting[0].pst hide_zero='Y'}</td>
  {section start=1 loop=5 name="acc"}
  <td{if $v.accounting[$smarty.section.acc.index].filled eq 'Y'} class="FilledAccounting"{/if}>
  {if !$static || $static eq 'O'}
    {if $smarty.section.acc.index eq $ACC_REF_TO_CUST}
      {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].pst hide_zero='Y'}
      <input type="hidden" name="groups{if $static eq 'O'}[{$order.orderid}]{/if}[{$m_id}][acc][{$smarty.section.acc.index}][pst]" size="8" value="{$v.accounting[$smarty.section.acc.index].pst|price_format}" />
    {else}
  <input name="groups{if $static eq 'O'}[{$order.orderid}]{/if}[{$m_id}][acc][{$smarty.section.acc.index}][pst]" size="8" value="{$v.accounting[$smarty.section.acc.index].pst|price_format}" />
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
  <td>{include file="currency2.tpl" value=$v.total.gross}</td>
  <td>{$order.date|date_format:"%T"}</td>
  {if $show_accounting}
  <td{if $v.accounting[0].filled eq 'Y'} class="FilledAccounting"{/if}>{include file="currency2.tpl" value=$v.accounting[0].gross}</td>
  {section start=1 loop=5 name="acc"}
  <td{if $v.accounting[$smarty.section.acc.index].filled eq 'Y'} class="FilledAccounting"{/if}>
  {if !$static || $static eq 'O'}
    {if $smarty.section.acc.index eq $ACC_REF_TO_CUST}
      {include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].gross hide_zero='Y'}
      <input type="hidden" name="groups{if $static eq 'O'}[{$order.orderid}]{/if}[{$m_id}][acc][{$smarty.section.acc.index}][gross]" size="8" value="{$v.accounting[$smarty.section.acc.index].gross|price_format}" />
    {else}
  <input name="groups{if $static eq 'O'}[{$order.orderid}]{/if}[{$m_id}][acc][{$smarty.section.acc.index}][gross]" size="8" value="{$v.accounting[$smarty.section.acc.index].gross|price_format}" />
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



<tr class="OrderSheetCell{$cycle_class}">
<td colspan="12" align="left">

        <input type="hidden" id="row_max_index_{$m_id}" name="row_max_index_{$m_id}" value="{if $all_distributor_links.$m_id.count_links_to_distributor_invoices}{$all_distributor_links.$m_id.count_links_to_distributor_invoices}{else}1{/if}" />

        <table cellpadding="0" cellspacing="0">
        {if $all_distributor_links.$m_id.distributor_links}
                {foreach from=$all_distributor_links.$m_id.distributor_links item="distributor_link" key=key name="depforeach"}

                <tr id="distributor_link_row_{$m_id}_{$key}">
                        <td style="padding: 2px 0px;">

Link to distributor invoice&nbsp;<input type="text" size="40" name="links_to_distributor_invoices[{$m_id}][{$key}][link_to_distributor_invoice]" value="{$distributor_link.link_to_distributor_invoice|escape}" />

&nbsp;<a href="javascript: void(0);" onclick="javascript: add_distributor_link_row('{$key}', '{$m_id}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
&nbsp;<a href="javascript: void(0);" onclick="javascript: remove_distributor_link_row('{$key}', '{$m_id}');"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_remove_row|escape:'javascript'}" /></a>

&nbsp;&nbsp;<a style="color: #3A3AFF; font-weight: normal;" href='{$distributor_link.link_to_distributor_invoice}' target="_blank">View invoice</a>

                        </td>
                </tr>

                {/foreach}
        {else}
                <tr id="distributor_link_row_{$m_id}_1">
                        <td style="padding: 2px 0px;">
                                Link to distributor invoice&nbsp;<input type="text" size="40" name="links_to_distributor_invoices[{$m_id}][1][link_to_distributor_invoice]" value="" />
&nbsp;<a href="javascript: void(0);" onclick="javascript: add_distributor_link_row(1, '{$m_id}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>

                        </td>
                </tr>
        {/if}

        </table>

<br />
</td>
</tr>




{/foreach}

{if $cycle_state eq ""}
</table>
{/if}
