{if $cycle_state eq "" || $cycle_state eq "first"}
<tr class="TableHead TableHeadAccounting">
  <td width="5">{*<b>OTRS ticket</b>*}</td>
  <td><b>Fraud Check</b></td>
  <td><b>OTRS ticket</b>{*{$lng.lbl_customer}*}</td>
  <td></td>
  <td>{$lng.lbl_processor}</td>
  <td colspan="7"><b>Last customer service message</b></td>
</tr>
<tr class="TableHead TableHeadAccounting TableHeadLight">
  <td width="5"><b>#</b></td>
  <td><b>{$lng.lbl_c2b_payment|upper}</b></td>
  <td><b>Assigned to</b></td>
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
  <td><b>{$lng.lbl_d2c_shipping|upper}</b></td>
  <td><b>{$lng.lbl_customer}</b></td>
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
  <td><b>{$lng.lbl_b2d_payment|upper}</b></td>
  <td><b>Country</b></td>
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

{if $tmp_rows_counter eq "0"}
        {assign var="cycle_class" value="class='OrderSheetDark'"}
{else}
        {assign var="cycle_class" value="class='TableSubHead_new'"}
{/if}


{foreach from=$order.shipping_groups item=v key=m_id name=groups}

<tr {$cycle_class} style="font-weight: bold;">
  <td align="center" width="5" style="font-weight: normal;">{*{if $order.otrs_ticket ne ""}<a href="{$order.otrs_ticket}" target="_blank">OTRS ticket</a>{/if}*}</td>
  <td align="center">{$fraud_statuses[$order.fraud_status]} ({$order.overall_fraud_score}) {*{include file="main/order_status.tpl" status=$v.cb_status mode="static" status_type="CB"}*}</td>
  <td align="center" nowrap="nowrap" class="OrderSheetCommonCell">{if $order.otrs_ticket ne ""}<a style="color: blue;" href="{$order.otrs_ticket}" target="_blank">OTRS ticket</a>{/if}{*{$order.firstname}*}</td>
  <td></td>
  <td align="center">

  {foreach from=$all_processors item=ps key=pid}
	{if $pid eq $v.acc_paymentid}{$ps.payment_method}{/if}
  {/foreach}

  </td>
  <td colspan="7" align="left" style="font-weight: normal;">{$order.last_customer_service_message|truncate:160:'[...]'}</td>
</tr>

<tr {$cycle_class}>
  <td width="5" align="center"><a style="color: blue;" href="order.php?orderid={$order.orderid}" target="_blank"><b>{$order.order_prefix}{$order.orderid}</b></a></td>
  <td class="OrderSheetGreenCell" align="center"><b>{include file="main/order_status.tpl" status=$v.cb_status mode="static" status_type="CB"}</b>{*<b>{include file="main/order_status.tpl" status=$v.dc_status mode="static" status_type="DC"}</b>*}</td>
  <td align="center">{if $order.ca_status ne ""}<b>{include file="main/order_status.tpl" status=$order.ca_status mode="static" status_type="CA"}</b>{/if}{*{$order.lastname}*}</td>
  <td align="center"></td>
  <td align="center">{$order.payment_method}</td>
  <td colspan="2" align="left">{$order.order_age_arr.days} days, {$order.order_age_arr.hours}:{$order.order_age_arr.mins} hours</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>

<tr {$cycle_class}>
  <td align="center" width="5">{*<a href="order.php?orderid={$order.orderid}" target="_blank">*}{$v.code}{*</a>*}</td>
  <td align="center" class="OrderSheetGreenCell"><b>{include file="main/order_status.tpl" status=$v.dc_status mode="static" status_type="DC"}</b>{*<b>{include file="main/order_status.tpl" status=$v.bd_status mode="static" status_type="BD"}</b>*}</td>
  <td align="center">{$order.firstname}{*{$order.s_countryname}*}</td>
  <td align="center"></td>
  <td align="center">{$order.date|date_format:"%d-%b-%G"}</td>
  <td colspan="2" align="left">{$order.last_activity_age_arr.days} days, {$order.last_activity_age_arr.hours}:{$order.last_activity_age_arr.mins} hours</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>

<tr {$cycle_class}>
  <td width="5" align="center">{if $static eq 'Y' || $static eq 'O'}{if $smarty.foreach.groups.first}<input type="checkbox" name="orderids[{$order.orderid}]" />{/if}{else}&nbsp;{/if}</td>
  <td align="center" class="OrderSheetGreenCell"><b>{include file="main/order_status.tpl" status=$v.bd_status mode="static" status_type="BD"}</b>{*{$fraud_statuses[$order.fraud_status]} / {if $order.ca_status ne ""}{include file="main/order_status.tpl" status=$order.ca_status mode="static" status_type="CA"}{/if}*}</td>
  <td align="center">{$order.s_countryname}</td>
  <td align="center">{include file="currency2.tpl" value=$v.total.gross}</td>
  <td align="center">{$order.date|date_format:"%T"}</td>
  <td colspan="2" align="left"></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>

{/foreach}
