<div style="font-size: 15px; font-weight: bold; margin: 15px;" align="center">Fraud check for <a style="color: #550000;" href="order.php?orderid={$orderid}">order # {$order.order_prefix}{$orderid}</a></div>


{if $you_cannot_modify_order eq "Y"}
{* <br /> *}

 {if $warning_message ne ""}

  <table width="100%">
  <tr>
  <td align="center" style="border: solid 1px #000000; background: #F4CCCC;">
        {$warning_message}
  </td>
  </tr>
  </table>
  <br />
 {/if}

{else}
    <table width="100%">
    <tr>
    <td align="center" style="border: solid 1px #000000; background: #D9EAD3;">
    {if $order_unlocked eq "Y"}
        {$unlock_message}
    {else}
        <form action="fraud_page.php?orderid={$orderid}" method="post" name="unlockorderform">
        <input type="hidden" name="mode" value="" id="id_mode_unlock" />
        {$lock_message}<input type="button" value="Unlock it now" onclick="javascript: $('#id_mode_unlock').val('unlock_order'); this.form.submit();" />.

        {if $count_locked_orders gt "1"}
                <input type="button" value="Unlock all orders locked by me" onclick="javascript: $('#id_mode_unlock').val('unlock_orders'); this.form.submit();" />
        {/if}

        </form>
    {/if}
    </td>
    </tr>
    </table>
    <br />
{/if}

{capture name=dialog}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

function func_show_full_info(code){

	$('#div_'+code).toggle();

	var button_value = $('#button_'+code).val();

	if (button_value == "[+]"){
		$('#button_'+code).val("[-]");
	} else {
		$('#button_'+code).val("[+]");
	}
}

{/literal}
//]]>
</script>


<form name="fraudform" action="fraud_page.php" method="post">

<input type="hidden" name="mode" value="" id="mode" />
<input type="hidden" name="orderid" value="{$orderid}" />

<table width="100%" style="background-color: #000000;" cellpadding="1" cellspacing="1">
<tr style="background-color: #cccccc;">
<td><B>Fraud check question</B></td>
<td><B>Manual action</B></td>
<td align="right"><B>Bare fraud score</B></td>
<td align="right"><B>Importance factor</B></td>
<td align="right"><B>Fraud score</B></td>
</tr>

{*
<tr>
<td colspan="6">
<hr />
</td>
</tr>
*}

{if $fraud_checks ne ""}
{foreach from=$fraud_checks item=item key=key}
<tr

{assign var="bold_arr_index" value="-1"}
{if $item.bare_fraud_score ne ""}
	{if $item.auto eq "Y"}
		{if $item.fraud_result eq "positive"}
			style="background-color: #D9EAD3;"
			{assign var="bold_arr_index" value="2"}
		{elseif $item.fraud_result eq "negative"}
			style="background-color: #F4CCCC;"
			{assign var="bold_arr_index" value="0"}
		{else}
			style="background-color: #FFF2CC;"
			{assign var="bold_arr_index" value="1"}
		{/if}
	{else}
		{if $item.manual_action eq ""}
			style="background-color: #FFFFFF;"
		{elseif $item.manual_action eq "Y"}	
			style="background-color: #D9EAD3;"
			{assign var="bold_arr_index" value="2"}
		{elseif $item.manual_action eq "N"}
			style="background-color: #F4CCCC;"
			{assign var="bold_arr_index" value="0"}
		{/if}
	{/if}
{else}
	style="background-color: #FFFFFF;" 
{/if}
>
<td>
<div align="right"><I>Question code: {$item.question_code}</I></div>
{$item.question_template_body}

{if $item.question_code eq "CHECK_OK_ORDERS_FOR_EMAIL" || $item.question_code eq "CHECK_FULLNAMES_FOR_EMAIL" || $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_IP" || $item.question_code eq "CHECK_DIFFERENT_BILLINGS_FOR_IP" || $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_PHONE" || $item.question_code eq "CHECK_DIFFERENT_BILLINGSS_FOR_PHONE" || $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_EMAIL" || $item.question_code eq "CHECK_DIFFERENT_BILLINGS_FOR_EMAIL" || $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_CARD" || $item.question_code eq "CHECK_DIFFERENT_BILLING_FOR_SHIPPING"}
<div id="div_{$item.question_code}" style="display: none;">
<table>
<tr><td>Order #</td><td>Order details</td></tr>

{if $item.question_code eq "CHECK_OK_ORDERS_FOR_EMAIL"}
	{if $item.additional_info ne ""}
		{foreach from=$item.additional_info key=k item=v}
			<tr>
			<td><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
                        <td>{$v.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$v.cb_status_name}/{$v.dc_status_name}</td>
			</tr>
		{/foreach}
	{/if}
{elseif $item.question_code eq "CHECK_FULLNAMES_FOR_EMAIL"}
        {if $item.additional_info ne ""}
                {foreach from=$item.additional_info key=k item=v}
                        <tr>
                        <td><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
                        <td>{$v.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$v.firstname}</td>
                        </tr>
                {/foreach}
        {/if}
{elseif $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_IP"}
        {if $item.additional_info ne ""}
                {foreach from=$item.additional_info key=k item=v}
                        <tr>
                        <td><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
                        <td>{$v.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$v.s_address1},{if $v.s_address2 ne ""}{$v.s_address2},{/if} {$v.s_city}, {$v.s_state}, {$v.s_country}, {$v.s_zipcode}</td>
                        </tr>
                {/foreach}
        {/if}
{elseif $item.question_code eq "CHECK_DIFFERENT_BILLINGS_FOR_IP"}
        {if $item.additional_info ne ""}
                {foreach from=$item.additional_info key=k item=v}
                        <tr>
                        <td><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
                        <td>{$v.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$v.b_address1},{if $v.b_address2 ne ""}{$v.b_address2},{/if} {$v.b_city}, {$v.b_state}, {$v.b_country}, {$v.b_zipcode}</td>
                        </tr>
                {/foreach}
        {/if}
{elseif $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_PHONE"}
        {if $item.additional_info ne ""}
                {foreach from=$item.additional_info key=k item=v}
                        <tr>
                        <td><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
                        <td>{$v.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$v.s_address1},{if $v.s_address2 ne ""}{$v.s_address2},{/if} {$v.s_city}, {$v.s_state}, {$v.s_country}, {$v.s_zipcode}</td>
                        </tr>
                {/foreach}
        {/if}
{elseif $item.question_code eq "CHECK_DIFFERENT_BILLINGSS_FOR_PHONE"}
        {if $item.additional_info ne ""}
                {foreach from=$item.additional_info key=k item=v}
                        <tr>
                        <td><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
                        <td>{$v.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$v.b_address1},{if $v.b_address2 ne ""}{$v.b_address2},{/if} {$v.b_city}, {$v.b_state}, {$v.b_country}, {$v.b_zipcode}</td>
                        </tr>
                {/foreach}
        {/if}
{elseif $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_EMAIL"}
        {if $item.additional_info ne ""}
                {foreach from=$item.additional_info key=k item=v}
                        <tr>
                        <td><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
                        <td>{$v.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$v.s_address1},{if $v.s_address2 ne ""}{$v.s_address2},{/if} {$v.s_city}, {$v.s_state}, {$v.s_country}, {$v.s_zipcode}</td>
                        </tr>
                {/foreach}
        {/if}
{elseif $item.question_code eq "CHECK_DIFFERENT_BILLINGS_FOR_EMAIL"}
        {if $item.additional_info ne ""}
                {foreach from=$item.additional_info key=k item=v}
                        <tr>
                        <td><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
                        <td>{$v.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$v.b_address1},{if $v.b_address2 ne ""}{$v.b_address2},{/if} {$v.b_city}, {$v.b_state}, {$v.b_country}, {$v.b_zipcode}</td>
                        </tr>
                {/foreach}
        {/if}
{elseif $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_CARD"}
        {if $item.additional_info ne ""}
                {foreach from=$item.additional_info key=k item=v}
                        <tr>
                        <td><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
                        <td>{$v.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$v.s_address1},{if $v.s_address2 ne ""}{$v.s_address2},{/if} {$v.s_city}, {$v.s_state}, {$v.s_country}, {$v.s_zipcode}</td>
                        </tr>
                {/foreach}
        {/if}
{elseif $item.question_code eq "CHECK_DIFFERENT_BILLING_FOR_SHIPPING"}
        {if $item.additional_info ne ""}
                {foreach from=$item.additional_info key=k item=v}
                        <tr>
                        <td><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
                        <td>{$v.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$v.b_address1},{if $v.b_address2 ne ""}{$v.b_address2},{/if} {$v.b_city}, {$v.b_state}, {$v.b_country}, {$v.b_zipcode}</td>
                        </tr>
                {/foreach}
        {/if}
{/if}

</table>
</div>
{/if}

</td>
<td nowrap="nowrap">
<input type="hidden" name="posted_data[{$key}][question_code]" value="{$item.question_code}" />

{if $item.auto eq "Y"}
	Auto
{else}
	<input type="radio" name="posted_data[{$key}][manual_action]" value="Y"{if $item.manual_action eq "Y"} checked="checked"{/if} />Yes
	<br />
	<input type="radio" name="posted_data[{$key}][manual_action]" value="N"{if $item.manual_action eq "N"} checked="checked"{/if} />No
{/if}
</td>
<td nowrap="nowrap" align="right">{if $item.bare_fraud_score eq "" || ($item.auto ne "Y" && $item.manual_action eq "")}To be calculated{else}{$item.bare_fraud_score}{/if}</td>
<td nowrap="nowrap" align="right">
{if $item.importance_factor_arr ne ""}
	{foreach from=$item.importance_factor_arr item=vv key=kk}
		{if $kk eq $bold_arr_index}<B>{/if}{$vv}{if $kk eq $bold_arr_index}</B>{/if}{if $kk lt 2}, {/if}
	{/foreach}
{else}
{$item.importance_factor}
{/if}
</td>
<td nowrap="nowrap" align="right">

{if $item.question_code eq "CHECK_OK_ORDERS_FOR_EMAIL" || $item.question_code eq "CHECK_FULLNAMES_FOR_EMAIL" || $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_IP" || $item.question_code eq "CHECK_DIFFERENT_BILLINGS_FOR_IP" || $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_PHONE" || $item.question_code eq "CHECK_DIFFERENT_BILLINGSS_FOR_PHONE" || $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_EMAIL" || $item.question_code eq "CHECK_DIFFERENT_BILLINGS_FOR_EMAIL" || $item.question_code eq "CHECK_DIFFERENT_SHIPPINGS_FOR_CARD" || $item.question_code eq "CHECK_DIFFERENT_BILLING_FOR_SHIPPING"}

<input type="button" value="[+]" id="button_{$item.question_code}" onclick="javascript: func_show_full_info('{$item.question_code}');">

<br />
<br />
{/if}

{if $item.bare_fraud_score eq "" || ($item.auto ne "Y" && $item.manual_action eq "")}To be calculated{else}{$item.fraud_score}{/if}
</td>
</tr>
{*
<tr><td colspan="6"><hr /></td></tr>
*}
{/foreach}
{/if}

<tr style="background-color: #FFFFFF;">
<td colspan="4" align="right"><B>Overall fraud score:</B></td>
<td align="right">{if $overall_fraud_score eq 0}0{else}{$overall_fraud_score|default:"To be calculated"}{/if}</td>
</tr>

{* <tr><td colspan="6"><hr /></td></tr> *}

<tr style="background-color: #FFFFFF;">
<td colspan="5" align="right"><B>Current fraud check status:</B> {include file="main/fraud_status.tpl" fraud_status=$order.fraud_status fraud_static="Y"}</td>
</tr>

{assign var="for_all_paymentid_shown" value=""}
{foreach from=$order.shipping_groups item=v key=m_id name=groups}

{if $for_all_paymentid_shown eq ""}
{assign var="for_all_paymentid_shown" value="Y"}
<tr style="background-color: #FFFFFF;">
<td colspan="5">

<table width="100%">
<tr>

<td>
{if $cidev_order_details_TransID ne ""}<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id={$cidev_order_details_TransID}" style="color: #1411FF;">Link to PayPal transaction</a>{/if}
</td>

<td align="center">
<B>Payment:</B> {$order.payment_method}
</td>

<td align="right">
 <B>{$lng.lbl_processor}:</B>
  <select name="groups[{$m_id}][paymentid]">
  <option value="0"{if $v.acc_paymentid eq 0} selected="selected"{/if}></option>
  {foreach from=$all_processors item=ps key=pid}
  <option value="{$pid}"{if $pid eq $v.acc_paymentid} selected="selected"{/if}>{$ps.payment_method}</option>
  {/foreach}
  </select>
</td>
</tr>
</table>

</td>
</tr>
{else}
<input type="hidden" name="groups[{$m_id}][paymentid]" value="0">
{/if}
{/foreach}

<tr style="background-color: #FFFFFF;">
<td colspan="5">
<a name="buttons"></a>
<input type="button" value="Apply changes and update fraud scores" onclick="javascript: $('#mode').val('apply_changes_and_update_fraud_scores'); document.fraudform.submit();">
<input type="button" value="Don't apply changes and close this window" onclick="javascript: window.close();">
</td>
</tr>

</table>

<br />
{capture name=dialog}

<table width="100%">
<tr>
<td><B>Change fraud check status to:</B> {include file="main/fraud_status.tpl" fraud_status=$order.fraud_status}</td>

<td align="right">
<input type="button" value="Apply changes, update fraud scores and change fraud check status" onclick="javascript: $('#mode').val('apply_changes_and_update_fraud_scores_and_change_fraud_check_status'); document.fraudform.submit();">
</td>
</tr>
</table>
{/capture}
{include file="dialog.tpl" title="Fraud check expert section" content=$smarty.capture.dialog extra='width="100%"'}

</form>

{/capture}
{include file="dialog.tpl" title="Fraud check questions" content=$smarty.capture.dialog extra='width="100%"'}
