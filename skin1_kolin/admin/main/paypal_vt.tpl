
{include file="main/subheader.tpl" title="Virtual Terminal"}


{capture name=authorize}
<form action="order.php" method="post" name="vt_form1">
<input type="hidden" name="mode" id="mode" value="" />
<input type="hidden" name="orderid" value="{$orderid}" />


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
    <td><input type="text" name="paypal_vt[grand_total]" value="{$order.total}" size="8" /></td>
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
    <td><input type="text" name="paypal_vt[card_number]" value="" autocomplete="off" /></td>
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
	<input type="button" value="Authorize" onclick="javascript: submitForm(this, 'authorize');" />
    </td>
  </tr>
</table>
</form>
{/capture}
{include file="dialog.tpl" title="Authorization" content=$smarty.capture.authorize extra='width="100%"'}


{if $transaction_logs ne ""}
<br />
{capture name=virtual_terminal}

<form action="order.php" method="post" name="vt_form1">
<input type="hidden" name="mode" id="mode" value="" />
<input type="hidden" name="orderid" value="{$orderid}" />
<input type="hidden" name="transaction_logs_id" id="transaction_logs_id" value="" />

 <table width="100%">
  <tr>
   <td width="12%"><B>Select</B></td>
   <td width="12%"><B>Type</B></td>
   <td width="10%"><B>Date</B></td>
   <td width="15%"><B>Name</B></td>
   <td width="*%"><B>Log</B></td>
  </tr>

 {foreach from=$transaction_logs item=v key=k}
  <tr>
   <td>
	<input type="radio" id="transaction_id" name="transaction_id" value="{$v.transaction_id}"
	{if $transaction_id_selected eq "" && $v.transaction_id ne ""} 
		checked="checked"
		{assign var="transaction_id_selected" value="Y"}
	{/if} 
	/>
   </td>
   <td>{$v.payment_method}</td>
   <td>{$v.date|date_format:'%d-%b-%Y'}</td>
   <td>{$v.firstname} ({$v.login})</td>
   <td>
	Transaction: 
	{if $v.transaction_id ne ""}

{if $v.transaction_id_link ne ""}<a target="_blank" style="color: #1411FF;" href="{$v.transaction_id_link|substitute:"trans-id":$v.transaction_id}">{/if}
{if $v.transaction_link_anchor ne ""}
{$v.transaction_link_anchor}
{else}
{$v.transaction_id}
{/if}
{if $v.transaction_id_link ne ""}</a>{/if}

{if $v.transaction_link_anchor ne ""}({$v.transaction_id}){/if}

	{else}
		NONE
	{/if}
	<br />
	transaction_status: <B>{$v.transaction_status}</B><br />
	transaction_currency: {$v.transaction_currency}<br />
	transaction_total: {$v.transaction_total}

{if $v.transaction_log ne ""}
<script>
//<![CDATA[
{literal}
$(document).ready(function(){
    $('#show_hide_link_{/literal}{$v.transaction_id}{literal}').click(
       function() {
          $(this).text(function(i,text) { return (text == 'Show details') ? 'Hide details' : 'Show details'; });
          $('#transaction_log_div_{/literal}{$v.transaction_id}{literal}').toggle('slow');
          return false;
       }
    );
});
{/literal}
//]]>
</script>

<br />
<div id="transaction_log_div_{$v.transaction_id}" style="display: none;"><B>Full log:</B><br />{$v.transaction_log}</div>
<a href="javascript: void(0);" style="color: #1411FF;" onclick="javascript: func_show_hide_log('{$v.transaction_id}');" id="show_hide_link_{$v.transaction_id}">Show details</a>

{/if}

   </td>
  </tr>
  <tr><td colspan="5"><hr /></td></tr>
 {/foreach}

 {if $transaction_id_selected eq "Y"}
  <tr>
   <td colspan="5">
<input type="button" value="Void selected authorized transaction" onclick="javascript: submitForm(this, 'void_transaction');" />
<input type="button" value="Capture selected authorized transaction" onclick="javascript: $('#transaction_logs_id').val('{$v.id}'); submitForm(this, 'capture_transaction');" />
   </td>
  </tr>
  <tr>
   <td colspan="5">
<input type="text" name="re_authorize_amount" id="re_authorize_amount" size="6" value="" />
<input type="button" value="RE-authorize selected transaction" onclick="javascript: submitForm(this, 're_authorize_transaction');" />
   </td>
  </tr>
 {/if}

 </table>
</form>

{/capture}
{include file="dialog.tpl" title="Virtual Terminal" content=$smarty.capture.virtual_terminal extra='width="100%"'}
{/if}

