
{include file="main/subheader.tpl" title="Virtual Terminal"}


{capture name=authorize}

<script type="text/javascript">
//<![CDATA[
{literal}

function func_AJAX_authorize_PayPal() {

	var f_name;
	var f_value;
	var cidev_parameters = 'AJAX_SUBMIT=Y';

	$("form[name='vt_form1']").find("input,select,textarea").not('[type="button"]').each(function() {

		f_name = $(this).attr('name');
		f_value = $(this).attr('value');
	
		if (f_name == "mode"){
			cidev_parameters = cidev_parameters + '&mode=authorize';
		}
		else if (f_name != "" && f_value != "") {
			cidev_parameters = cidev_parameters + '&'+f_name+'='+f_value;
		}
	});

//	alert(cidev_parameters);

	cidev_xmlHttp=cidev_createHttpRequestObject();
	if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

		cidev_xmlHttp.onreadystatechange=function(){
			if(cidev_xmlHttp.readyState==4){
				if(cidev_xmlHttp.status==200){
					var paypal_response = cidev_xmlHttp.responseText;

					alert(paypal_response);

					if (paypal_response == "Authorized" || paypal_response == "Faild"){
						$("#AJAX_Please_wait").show();
						$("#AJAX_Authorize_button").hide();
						$("#AJAX_Authorize_button_text").hide();
					}

					var m_id = $("#m_id_for_additional_shipping_status").val();

					if (paypal_response == "Authorized"){
						$("#additional_shipping_status_"+m_id).val("A"); // Authorized
						document.ordereditform1.submit();
					}
					else if (paypal_response == "Faild"){
						$("#additional_shipping_status_"+m_id).val("A");
//						window.location.reload();
					}

				}else{
					cidev_Error('no_server', 'Y');
				}
			}
		};

		var tmp_rand = Math.random();

		cidev_xmlHttp.open('POST','ajax_paypal_vt.php?rand='+tmp_rand,true);
		cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
		cidev_xmlHttp.setRequestHeader('Cache-Control','no-cache');
		cidev_xmlHttp.setRequestHeader('Cache-Control','no-store');
		cidev_xmlHttp.setRequestHeader('Connection','close');
		cidev_xmlHttp.send(cidev_parameters);
	}
	else {
		setTimeout('func_AJAX_authorize_PayPal()', 1000);
	}
}
{/literal}
//]]>
</script>

<div id="AJAX_Authorize_button_text" style="display: none; background-color: #f4cccc;">
{$lng.lb_additional_payment_authorize_message}
</div>


<form action="order.php" method="post" name="vt_form1">
<input type="hidden" name="mode" id="mode" value="" />
<input type="hidden" name="orderid" value="{$orderid}" />

<input type="hidden" name="m_id_for_additional_shipping_status" id="m_id_for_additional_shipping_status" value="" />

<table cellspacing="0" cellpadding="0" align="center">

  <tr>
    <td align="right"><h3 style="color: #000000;">Amount and currency</h3></td>
    <td></td>
  </tr>
  <tr>
    <td align="right"><b>Currency:</b> </td>
    <td>
<select name="paypal_vt[currency]" id="paypal_vt_currency">
<option value="USD">U.S. Dollars</option>
<option value="CAN"> CAN. Dollars</option>
</select>
    </td>
  </tr>
  <tr>
    <td align="right"><b>Grand total:</b> </td>
    <td><input type="text" name="paypal_vt[grand_total]" value="{$order.total}" size="8" id="paypal_vt_grand_total" /></td>
  </tr>


  <tr>
    <td align="right"><h3 style="color: #000000;">Credit card information</h3></td>
    <td></td>
  </tr>
  <tr>
    <td align="right"><b>Cardholder's name:</b> </td>
    <td><input type="text" name="paypal_vt[cardholderl_name]" value="{$customer.b_firstname}" /></td>
  </tr>
  <tr>
    <td align="right"><b>Card number:</b> </td>
    <td><input type="text" name="paypal_vt[card_number]" value="" autocomplete="off" id="paypal_vt_card_number" onkeyup="cidev_check_field_phone_ext('paypal_vt_card_number')" /></td>
  </tr>
  <tr>
    <td align="right"><b>Expiration date:</b> </td>
    <td><input type="text" name="paypal_vt[expiration_month]" value="" placeholder="MM" size="2" maxlength="2" />/<input type="text" name="paypal_vt[expiration_year]" value="" placeholder="YYYY" size="4" maxlength="4" /></td>
  </tr>

  <tr>
    <td nowrap="nowrap" align="right"><b>Security code (CSC):</b><div class="cidev_field_descr">Optional</div> </td>
    <td><input type="text" name="paypal_vt[csc]" value="" size="4" maxlength="4" autocomplete="off" /></td>
  </tr>


  <tr>
    <td align="right"><h3 style="color: #000000;">{$lng.lbl_billing_address}</h3></td>
    <td></td>
  </tr>
  <tr>
    <td align="right"><b>{$lng.lbl_address}:</b> </td>
    <td>{if !$static}<input type="text" name="paypal_vt[b_address]" value="{$customer.b_address}" />{else}{$customer.b_address}{/if}</td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">{$lng.lbl_address_2}: </td>
    <td>{if !$static}<input type="text" name="paypal_vt[b_address_2]" value="{$customer.b_address_2}" />{else}{$customer.b_address_2}{/if}</td>
  </tr>
  <tr>
    <td align="right"><b>{$lng.lbl_city}:</b> </td>
    <td>{if !$static}<input type="text" name="paypal_vt[b_city]" value="{$customer.b_city}" />{else}{$customer.b_city}{/if}</td>
  </tr>
  <tr>
    <td align="right"><b>{$lng.lbl_state}:</b> </td>
    <td>{if !$static}
{include file="main/states.tpl" states=$states name="paypal_vt[b_state]" default=$customer.b_state default_country=$customer.b_country|default:$config.General.default_country country_name="paypal_vt[b_country]"}
{else}{$customer.b_statename}{/if}
    </td>
  </tr>
  <tr>
    <td align="right"><b>{$lng.lbl_country}:</b> </td>
    <td>{if !$static}
<select name="paypal_vt[b_country]" id="paypal_vt_b_country" size="1">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}"{if $customer.b_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $customer.b_country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
{/section}
</select>
{if $customer.default_fields.b_state}
{include file="main/register_states.tpl" state_name="paypal_vt[b_state]" country_name="paypal_vt[b_country]" county_name="paypal_vt[b_county]" state_value=$customer.b_state county_value=$customer.b_county country_id="paypal_vt_b_country"}
{/if}
{else}{$customer.b_countryname}{/if}</td>
  </tr>
  <tr>
    <td align="right"><b>{$lng.lbl_zip_code}:</b> </td>
    <td>{if !$static}<input type="text" name="paypal_vt[b_zipcode]" value="{$customer.b_zipcode}" />{else}{$customer.b_zipcode}{/if}</td>
  </tr>
  <tr>
    <td></td>
    <td>	
	<br />

	<div id="default_Authorize_button">
		<input type="button" value="Authorize" onclick="javascript: submitForm(this, 'authorize');" />
	</div>

	
        <div id="AJAX_Authorize_button" style="display: none;">
{*
<input type="hidden" name="VT_OPENED_FROM_func_check_for_paypal_vt_function" id="VT_OPENED_FROM_func_check_for_paypal_vt_function" value="" />
*}

	        <input type="button" id="btn_Authorize" value="Authorize" onclick="javascript: func_AJAX_authorize_PayPal();" />
        </div>

	<div id="AJAX_Please_wait" style="display: none;">
		<h1>Please wait. <br >Page will be reloaded now ...</h1>
	</div>

    </td>
  </tr>
</table>

</form>
{/capture}
{include file="dialog.tpl" title="Authorization" content=$smarty.capture.authorize extra='width="100%"'}




{if $order_transactions ne ""}
<br />
{capture name=virtual_terminal_transactions}

<form action="order.php" method="post" name="vt_form01">
<input type="hidden" name="mode" id="mode" value="" />
<input type="hidden" name="order_transaction_id" id="order_transaction_id" value="" />
<input type="hidden" name="orderid" value="{$orderid}" />

 <table width="100%">
  <tr>
   <td width="12%"><B>Type</B></td>
   <td width="10%"><B>Date</B></td>
   <td width="15%"><B>Name</B></td>
   <td width="*%"><B>Log</B></td>
  </tr>

 {foreach from=$order_transactions item=v key=k}
  <tr>
   <td>{$v.payment_method}</td>
   <td>{$v.date|date_format:'%d-%b-%Y<br />%H:%M:%S'}</td>
   <td>{$v.firstname} ({$v.login})</td>
   <td>
        Transaction:
        {if $v.transaction_id ne ""}

{if $v.transaction_id_link ne ""}<a target="_blank" style="color: #1411FF;" href="{$v.transaction_id_link|substitute:"trans-id":$v.transaction_id}">{/if}
{if $v.transaction_link_anchor ne ""}{$v.transaction_link_anchor}{else}{$v.transaction_id}{/if}{if $v.transaction_id_link ne ""}</a>{/if}

{if $v.transaction_link_anchor ne ""}({$v.transaction_id}){/if}

{if $v.manual_transaction eq "Y"}
 (Manually added)
{/if}

        {else}
                NONE
        {/if}
        <br />
        transaction_status: <B>{$v.transaction_status}</B><br />
        transaction_currency: {$v.transaction_currency}<br />
        transaction_total: {$v.transaction_amount}

{if $v.issue ne ""}
        <br />
        <B>issue:</B> {$v.issue}
{elseif $v.unserialized_transaction_response.message ne ""}
                        <br />
                        <B>message:</B> {$v.unserialized_transaction_response.message}
{/if}


{if $v.transaction_response ne ""}
<script>
//<![CDATA[
{literal}
$(document).ready(function(){
    $('#show_hide_a_link_{/literal}{$k}{literal}').click(
       function() {
          $(this).text(function(i,text) { return (text == 'Show details') ? 'Hide details' : 'Show details'; });
          $('#transactions_div_{/literal}{$k}{literal}').toggle('slow');
          return false;
       }
    );
});
{/literal}
//]]>
</script>

<br />
<div id="transactions_div_{$k}" style="display: none;"><B>Full log:</B><br />{$v.transaction_response}</div>
<a href="javascript: void(0);" style="color: #1411FF;" onclick="javascript: func_show_hide_log('{$k}');" id="show_hide_a_link_{$k}">Show details</a>

{/if}

   </td>
  </tr>

  <tr>
   <td colspan="4">

    <input type="button" value="Look up payment (Get links)" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 'look_up_payment');" />

    {if $v.unserialized_transaction_response.links ne ""}


	{assign var="show_transaction_amount_field" value="N"}

	{foreach from=$v.unserialized_transaction_response.links item=link key=k_link}

		{if $link.rel eq "self"}
{*
			<input type="button" value="Self" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 'self_transaction');" />
*}
		{elseif $link.rel eq "refund"}
			{assign var="show_transaction_amount_field" value="Y"}
			<input type="button" value="Refund transaction" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 'refund_transaction');" /> 
{*
{$lng.lbl_refund_transaction_txt} - not added yet br />
*}
	
		{elseif $link.rel eq "void"}
			<input type="button" value="Void authorized transaction" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 'void_transaction');" />
{*
 {$lng.lbl_void_transaction_txt} <br />
*}
		{elseif $link.rel eq "capture"}
			{assign var="show_transaction_amount_field" value="Y"}
			<input type="button" value="Capture selected authorized transaction" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 'capture_transaction');" /> 
{*
{$lng.lbl_capture_transaction_txt} <br />
*}
		{elseif $link.rel eq "reauthorize"}
			{assign var="show_transaction_amount_field" value="Y"}
			<input type="button" value="RE-authorize selected transaction" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 're_authorize_transaction');" /> 
{*
{$lng.lbl_re_authorize_transaction_txt}
*}
		{/if}

	{/foreach}

	{if $show_transaction_amount_field eq "Y"}
<input type="text" name="transaction_amount[{$v.id}]" id="transaction_amount_{$v.id}" size="6" value="{$v.transaction_amount}" />
	{/if}

    {/if}

   </td>
  </tr>

  <tr><td colspan="4"><hr /></td></tr>
 {/foreach}

 </table>
</form>

<table align="right" cellspacing="1" cellpadding="1">
<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td>Transactions amount (authorized/pending +captured ) </td>
  <td>&nbsp;</td>
  <td align="right" style="font-size: 10px; {if $count_shipping_groups eq "1"} background-color: {if $order.total eq $order_transactions_totals.authorized_PLUS_captured_totals}green{else}red{/if}; {/if}">{include file="currency2.tpl" value=$order_transactions_totals.authorized_PLUS_captured_totals}</td>
</tr>

<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td>Void total</td>
  <td>&nbsp;</td>
  <td align="right" style="font-size: 10px;">{include file="currency2.tpl" value=$order_transactions_totals.void_total}</td>
</tr>

<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td>Authorized total</td>
  <td>&nbsp;</td>
  <td align="right" style="font-size: 10px;">{include file="currency2.tpl" value=$order_transactions_totals.authorized_total}</td>
</tr>

<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td>Captured total</td>
  <td>&nbsp;</td>
  <td align="right" style="font-size: 10px; {if $count_shipping_groups gt 1}background-color: {if $order.total eq $order_transactions_totals.captured_total}green{else}red{/if};{/if}">{include file="currency2.tpl" value=$order_transactions_totals.captured_total}</td>
</tr>
</table>

{/capture}
{include file="dialog.tpl" title="Transactions" content=$smarty.capture.virtual_terminal_transactions extra='width="100%"'}
{/if}





{if $transaction_logs ne ""}
<br />
{capture name=virtual_terminal}

<form action="order.php" method="post" name="vt_form02">
<input type="hidden" name="mode" id="mode" value="" />
<input type="hidden" name="orderid" value="{$orderid}" />

 <table width="100%">
  <tr>
{*   <td width="12%"><B>Select</B></td>*}
   <td width="12%"><B>Type</B></td>
   <td width="10%"><B>Date</B></td>
   <td width="15%"><B>Name</B></td>
   <td width="*%"><B>Log</B></td>
  </tr>

 {foreach from=$transaction_logs item=v key=k}
  <tr>
{*
   <td>
	{if $v.transaction_id ne ""} 
	<input type="radio" id="transaction_logs_id" name="transaction_logs_id" value="{$v.id}"
		checked="checked"
		{assign var="transaction_id_selected" value="Y"}
	/>
	{/if} 
   </td>
*}
   <td>{$v.payment_method}</td>
   <td>{$v.date|date_format:'%d-%b-%Y<br />%H:%M:%S'}</td>
   <td>{$v.firstname} ({$v.login})</td>
   <td>
	Transaction: 
	{if $v.transaction_id ne ""}

{if $v.transaction_id_link ne ""}<a target="_blank" style="color: #1411FF;" href="{$v.transaction_id_link|substitute:"trans-id":$v.transaction_id}">{/if}
{if $v.transaction_link_anchor ne ""}{$v.transaction_link_anchor}{else}{$v.transaction_id}{/if}{if $v.transaction_id_link ne ""}</a>{/if}

{if $v.transaction_link_anchor ne ""}({$v.transaction_id}){/if}

{if $v.unserialized_transaction_log.FIELD_manual_transaction eq "Y"}
 (Manually added)
{/if}

	{else}
		NONE
	{/if}
	<br />
	transaction_status: <B>{$v.transaction_status}</B><br />
	transaction_currency: {$v.transaction_currency}<br />
	transaction_total: {$v.transaction_total}

{if $v.issue ne ""}
	<br />
	<B>issue:</B> {$v.issue}
{elseif $v.unserialized_transaction_log.message ne ""}
                        <br />
                        <B>message:</B> {$v.unserialized_transaction_log.message}
{/if}


{if $v.transaction_log ne ""}
<script>
//<![CDATA[
{literal}
$(document).ready(function(){
    $('#show_hide_link_{/literal}{$k}{literal}').click(
       function() {
          $(this).text(function(i,text) { return (text == 'Show details') ? 'Hide details' : 'Show details'; });
          $('#transaction_log_div_{/literal}{$k}{literal}').toggle('slow');
          return false;
       }
    );
});
{/literal}
//]]>
</script>

<br />
<div id="transaction_log_div_{$k}" style="display: none;"><B>Full log:</B><br />{$v.transaction_log}</div>
<a href="javascript: void(0);" style="color: #1411FF;" onclick="javascript: func_show_hide_log('{$k}');" id="show_hide_link_{$k}">Show details</a>

{/if}

   </td>
  </tr>
  <tr><td colspan="4"><hr /></td></tr>
 {/foreach}

{*
 {if $transaction_id_selected eq "Y"}
  <tr>
   <td colspan="4">
<input type="button" value="Void selected authorized transaction" onclick="javascript: submitForm(this, 'void_transaction');" /> {$lng.lbl_void_transaction_txt}
<br />
<input type="button" value="Capture selected authorized transaction" onclick="javascript: submitForm(this, 'capture_transaction');" /> {$lng.lbl_capture_transaction_txt}
   </td>
  </tr>
  <tr>
   <td colspan="4">
<input type="text" name="re_authorize_amount" id="re_authorize_amount" size="6" value="" />
<input type="button" value="RE-authorize selected transaction" onclick="javascript: submitForm(this, 're_authorize_transaction');" /> {$lng.lbl_re_authorize_transaction_txt}
   </td>
  </tr>
 {/if}
*}

 </table>
</form>

{/capture}
{include file="dialog.tpl" title="Transaction log" content=$smarty.capture.virtual_terminal extra='width="100%"'}
{/if}


<br />
{capture name=add_manual_transaction}

<form action="order.php" method="post" name="vt_form03">
<input type="hidden" name="mode" id="mode" value="" />
<input type="hidden" name="orderid" value="{$orderid}" />

<table>
     <tr>
       <td>
         <b>Transaction status:</b><br />
            <select name="transaction_status">
            <option value="authorized">Authorized</option>
            <option value="completed">Authorized & Captured</option>
            </select>
       </td>
       <td width="20">&nbsp;</td>
       <td>
           <b>Currency<b>
	    <select name="transaction_currency">
	    <option value="USD">U.S. Dollars</option>
	    <option value="CAN"> CAN. Dollars</option>
	    </select>
	</td>
	<td width="20" colspan="4">&nbsp;</td>
     </tr>	
     <tr>
       <td>
         <b>Payment method:</b><br />
         <select name="paymentid" >
         <option value="0"></option>
         {foreach from=$all_vt_processors item=item_vt key=key_vt}
         <option {if $v.additional_vt_paymentid eq $item_vt.paymentid} selected="selected"{/if} value="{$item_vt.paymentid}">{$item_vt.payment_method}</option>
         {/foreach}
         </select>
       </td>
       <td width="20">&nbsp;</td>
       <td>
           <b>Virtual terminal transaction ID:</b><br />
           <input type="text" name="transaction_id" value="" size="40" />
       </td>
       <td width="20">&nbsp;</td>
       <td>
           <b>AVS code:</b><br />
           <input type="text" name="avs_code" value="" size="1" maxlength="1" />
       </td>
       <td width="20">&nbsp;</td>
       <td>
           <b>Transaction amount:</b><br />
           <input type="text" name="transaction_amount" value="0" size="8" />
       </td>
     </tr>
</table>

<input type="button" value="Add transaction" onclick="javascript: submitForm(this, 'add_manual_transaction');" />

</form>

{/capture}
{include file="dialog.tpl" title="Add manual transaction" content=$smarty.capture.add_manual_transaction extra='width="100%"'}

