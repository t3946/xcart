{if $cycle_state eq "" || $cycle_state eq "first"}
<tr class="TableHead TableHeadAccounting">
  <td width="5"><b>OTRS ticket</b></td>
  <td>{$lng.lbl_c2b_payment|upper}</td>
  <td>{$lng.lbl_customer}</td>
  <td></td>
  <td>{$lng.lbl_processor}</td>
  <td colspan="7"><b>Last customer service message</b></td>
</tr>
<tr class="TableHead TableHeadAccounting TableHeadLight">
  <td width="5"><b>#</b></td>
  <td><b>{$lng.lbl_d2c_shipping|upper}</b></td>
  <td>&nbsp;</td>
  <td></td>
  <td><b>{$lng.lbl_payment}</b></td>
  <td colspan="2"><b>Order age</b></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>
<tr class="TableHead TableHeadAccounting TableHeadLight">
  <td width="5"><b>{$lng.lbl_distr}</b></td>
  <td><b>{$lng.lbl_b2d_payment|upper}</b></td>
  <td>&nbsp;</td>
  <td></td>
  <td><b>{$lng.lbl_date}</b></td>
  <td colspan="2"><b>Last activity</b></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>
<tr class="TableHead TableHeadAccounting TableHeadLight">
  <td width="5">&nbsp;</td>
  <td><b>Fraud Check/Assigned</b></td>
  <td>&nbsp;</td>
  <td><b>Total</b></td>
  <td><b>{$lng.lbl_time}</b></td>
  <td colspan="2"><b>New ticket messages</b></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>
{/if}



{foreach from=$order.shipping_groups item=v key=m_id name=groups}

{cycle values=", OrderSheetDark" assign="cycle_class"}
<tr class="OrderSheetCell{$cycle_class}" style="font-weight: bold;">
  <td width="5" style="font-weight: normal;">{if $order.otrs_ticket ne ""}<a href="{$order.otrs_ticket}" target="_blank">OTRS ticket</a>{/if}</td>
  <td>{include file="main/order_status.tpl" status=$v.cb_status mode="static" status_type="CB"}</td>
  <td nowrap="nowrap" class="OrderSheetCommonCell">{$order.firstname}</td>
  <td></td>
  <td>

  {foreach from=$all_processors item=ps key=pid}
	{if $pid eq $v.acc_paymentid}{$ps.payment_method}{/if}
  {/foreach}

  </td>
  <td colspan="7" align="left" style="font-weight: normal;">{$order.last_customer_service_message|truncate:160:'[...]'}</td>
</tr>

<tr class="OrderSheetCell{$cycle_class}">
  <td width="5"><a href="order.php?orderid={$order.orderid}" target="_blank"><b>{$order.order_prefix}{$order.orderid}</b></a></td>
  <td class="OrderSheetGreenCell"><b>{include file="main/order_status.tpl" status=$v.dc_status mode="static" status_type="DC"}</b></td>
  <td>{$order.lastname}</td>
  <td></td>
  <td>{$order.payment_method}</td>
  <td colspan="2" align="left">{$order.order_age_arr.days} days, {$order.order_age_arr.hours}:{$order.order_age_arr.mins}</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>

<tr class="OrderSheetCell{$cycle_class}">
  <td width="5"><a href="order.php?orderid={$order.orderid}" target="_blank">{$v.code}</a></td>
  <td class="OrderSheetGreenCell"><b>{include file="main/order_status.tpl" status=$v.bd_status mode="static" status_type="BD"}</b></td>
  <td>{$order.s_countryname}</td>
  <td></td>
  <td>{$order.date|date_format:"%d-%b-%G"}</td>
  <td colspan="2" align="left">{$order.last_activity_age_arr.days} days, {$order.last_activity_age_arr.hours}:{$order.last_activity_age_arr.mins}</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>

<tr class="OrderSheetCell{$cycle_class} OrderSheetLast">
  <td width="5">{if $static eq 'Y' || $static eq 'O'}{if $smarty.foreach.groups.first}<input type="checkbox" name="orderids[{$order.orderid}]" />{/if}{else}&nbsp;{/if}</td>
  <td>{$fraud_statuses[$order.fraud_status]} / {if $order.ca_status ne ""}{include file="main/order_status.tpl" status=$order.ca_status mode="static" status_type="CA"}{/if}</td>
  <td>&nbsp;</td>
  <td>{include file="currency2.tpl" value=$v.total.gross}</td>
  <td>{$order.date|date_format:"%T"}</td>
  <td colspan="2" align="left"></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>

{/foreach}
