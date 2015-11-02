{* $Id: history_order.tpl,v 1.83.2.17 2006/12/25 11:53:29 svowl Exp $ *}
{if $current_membership_flag eq 'FS'}
{assign var="membership_static" value="F"}
{else}
{assign var="membership_static" value=""}
{/if}
{include file="main/multirow.tpl"}
<script type="text/javascript">
<!--
multirowInputSets['track'] = [];
multirowInputSets['track'].noCloneContent = 1;
-->
</script>

{assign var="order_details_name" value="Order # `$order.order_prefix``$order.orderid` detailes"}

<table width="100%">
<tr>
<td align="left" width="33%">
{if $usertype ne "C"}
{if $orderid_prev ne ""}<a href="order.php?orderid={$orderid_prev}">&lt;&lt;&nbsp;{$lng.lbl_order} {if $usertype eq 'A' || $usertype eq 'P'}# {/if}{$order_prefix_prev}{$orderid_prev}</a>{/if}
{/if}
</td>

<td align="center" width="*">
{include file="page_title.tpl" title=$order_details_name}
</td>

<td align="right" width="33%">
{if $usertype ne "C"}
{if $orderid_next ne ""}<a href="order.php?orderid={$orderid_next}">{$lng.lbl_order} {if $usertype eq 'A' || $usertype eq 'P'}# {/if}{$order_prefix_next}{$orderid_next}&nbsp;&gt;&gt;</a>{/if}
{/if}
</td>
</tr>
</table>

{$lng.txt_order_details_top_text}

<br /><br />

{if $usertype eq 'A' && $is_merchant_password ne 'Y' && $config.Security.blowfish_enabled eq 'Y'}
{capture name=dialog}
<form action="{$catalogs.admin}/merchant_password.php" method="post" name="mpasswordform">
<input type="hidden" name="orderid" value="{$orderid}" />
{$lng.txt_enter_merchant_password_note}
<br /><br />
<table>
<tr>
	<td><font class="VertMenuItems">{$lng.lbl_merchant_password}</font></td>
	<td><input type="password" name="mpassword" size="16" /></td>
	<td><input type="submit" value="{$lng.lbl_enter_mpassword|strip_tags:false|escape}" /></td>
</tr>
</table>
</form>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_enter_merchant_password content=$smarty.capture.dialog extra='width="100%"'}
<br />
{/if}

{capture name=dialog}

{if $usertype ne "C"}

<script type="text/javascript">
//<![CDATA[
{literal}

function send_compose(department, template_id){

	if (department == 'customer'){
		{/literal}
		{foreach from=$department_full_arr.customer item=item key=key}
		{literal}
		if (template_id == "{/literal}{$item.id}{literal}"){
			var subject_line = '{/literal}{$item.subject_line}{literal}';
			var send_to_email_customer = '{/literal}{$customer.email}{literal}';
			var send_to_email = '{/literal}{$item.send_to_email}{literal}';
			var message_body = '{/literal}{$item.message_body}{literal}';

			window.open('https://mail.google.com/mail/?view=cm&fs=1&to='+send_to_email_customer+','+send_to_email+'&su='+subject_line+'&body='+message_body+'');

		}
		{/literal}
		{/foreach}
		{literal}
	}

        if (department == 'our_customer_service'){
                {/literal}
                {foreach from=$department_full_arr.our_customer_service item=item key=key}
                {literal}
                if (template_id == "{/literal}{$item.id}{literal}"){
                        var subject_line = '{/literal}{$item.subject_line}{literal}';
                        var send_to_email = '{/literal}{$item.send_to_email}{literal}';
                        var message_body = '{/literal}{$item.message_body}{literal}';

                        window.open('https://mail.google.com/mail/?view=cm&fs=1&to='+send_to_email+'&su='+subject_line+'&body='+message_body+'');

                }
                {/literal}
                {/foreach}
                {literal}
        }

}

function explode (delimiter, string, limit) {

  if ( arguments.length < 2 || typeof delimiter === 'undefined' || typeof string === 'undefined' ) return null;
  if ( delimiter === '' || delimiter === false || delimiter === null) return false;
  if ( typeof delimiter === 'function' || typeof delimiter === 'object' || typeof string === 'function' || typeof string === 'object'){
    return { 0: '' };
  }
  if ( delimiter === true ) delimiter = '1';

  // Here we go...
  delimiter += '';
  string += '';

  var s = string.split( delimiter );


  if ( typeof limit === 'undefined' ) return s;

  // Support for limit
  if ( limit === 0 ) limit = 1;

  // Positive limit
  if ( limit > 0 ){
    if ( limit >= s.length ) return s;
    return s.slice( 0, limit - 1 ).concat( [ s.slice( limit - 1 ).join( delimiter ) ] );
  }

  // Negative limit
  if ( -limit >= s.length ) return [];

  s.splice( s.length + limit );
  return s;
}


function send_compose_distributor(mid_template_id){

	var mid_template_id_arr = explode('-', mid_template_id);
	var mid = mid_template_id_arr[0];
	var template_id = mid_template_id_arr[1];


        {/literal}
        {foreach from=$order_manufacturers item=item key=key}
        {literal}
                if (mid == "{/literal}{$key}{literal}"){
                        var send_to_email = '{/literal}{$item.compose_email_to_distributor}{literal}';
                }
        {/literal}
        {/foreach}
        {literal}

	{/literal}
        {foreach from=$department_full_arr.distributor item=item key=key}
        {literal}
                if (template_id == "{/literal}{$item.id}{literal}"){
                        var subject_line = '{/literal}{$item.subject_line}{literal}';
                        var message_body = '{/literal}{$item.message_body}{literal}';
			var send_to_email2 = '{/literal}{$item.send_to_email}{literal}';
                        window.open('https://mail.google.com/mail/?view=cm&fs=1&to='+send_to_email+','+send_to_email2+'&su='+subject_line+'&body='+message_body+'');
                }
        {/literal}
        {/foreach}
	{literal}
}

{/literal}
//]]>
</script>


<table width="100%" cellspacing="0" cellpadding="0" border="0">

<tr>
	<td width="37%"><I>Compose email to customer</I></td>
	<td width="36%"><I>Compose email to distributor</I></td>
	<td width="*"><I>Compose email to our customer service</I></td>
</tr>

<tr>
	<td nowrap="nowrap">
		{if $department_full_arr.customer ne ""}
		<form name="customer_department_form" action="#" method="POST">

		    <select name="customer_department" id="customer_department" style="width: 70%;">
	        	{foreach from=$department_full_arr.customer item=item key=key}
	        	<option value="{$item.id}">{$item.template_name}</option>
		        {/foreach}
		    </select>
		    <input type="button" name="Compose" value="Compose" onclick="javascript: send_compose('customer', $('#customer_department').val());">
		</form>
		{/if}
	</td>

	<td nowrap="nowrap">
                {if $department_full_arr.distributor ne "" && $order_manufacturers ne ""}
                <form name="distributor_department_form" action="#" method="POST">

                    <select name="distributor_department" id="distributor_department" style="width: 70%;">

			{foreach from=$order_manufacturers item=v key=k}

				<optgroup label="{$v.manufacturer}">

	                        {foreach from=$department_full_arr.distributor item=item key=key}
        	                	<option value="{$k}-{$item.id}">{$item.template_name}</option>
                	        {/foreach}

			{/foreach}
                    </select>
                    <input type="button" name="Compose" value="Compose" onclick="javascript: send_compose_distributor($('#distributor_department').val());">
                </form>
                {/if}
	</td>

	<td nowrap="nowrap">
                {if $department_full_arr.our_customer_service ne ""}
                <form name="our_customer_service_form" action="#" method="POST">

                    <select name="our_customer_service_department" id="our_customer_service_department" style="width: 70%;">
                        {foreach from=$department_full_arr.our_customer_service item=item key=key}
                        <option value="{$item.id}">{$item.template_name}</option>
                        {/foreach}
                    </select>
                    <input type="button" name="Compose" value="Compose" onclick="javascript: send_compose('our_customer_service', $('#our_customer_service_department').val());">
                </form>
                {/if}
	</td>
</tr>

</table>
<br />
{/if}


{assign var=colspan value=10}

<table width="100%">
<tr> 
	<td valign="top" colspan="{$colspan}">

{if $usertype ne "C"}
 <table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
   <td width="25%" valign="top">
	Order date: {$order.date|date_format:'%d-%m-%Y&nbsp; %H:%M:%S'}
	<br />
	Current date: {$current_date|date_format:'%d-%m-%Y&nbsp; %H:%M:%S'}
   </td>

   <td width="25%" valign="top">
	Order source: <a href="{$customer.referer}" target="_blank">Referral link</a>
   </td>

   <td style="font-size: 12px;" width="25%" valign="top">
    <a target="_blank" style="color: #140BFC" href="https://mail.google.com/mail/u/0/#search/{$order.order_prefix}{$order.orderid}">Gmail communication</a>
   </td>

   <td width="25%" align="right" valign="top">
    {if $ca_statuses ne ""}
	Currently assigned to<br />
        <form action="order.php" method="post" name="order_ca_status">
        <input type="hidden" name="mode" value="change_ca_status" />
        <input type="hidden" name="orderid" value="{$order.orderid}" />
        <select name="ca_status" onchange="javascript: document.order_ca_status.submit();">
        <option value="">
                {foreach from=$ca_statuses item=item key=key}
                    <option value="{$item.code}" 
                      {if $item.code eq $order.ca_status} 
                        selected="selected"
                      {/if}
                    >{$item.name}</option>
                {/foreach}
        </select>
        </form>

    {/if}
   </td>
  </tr>
 </table>
 <br />
{/if}


{if $usertype eq "C"}
<p class="prev-next-links">
{if $orderid_prev ne ""}<a href="order.php?orderid={$orderid_prev}">&lt;&lt;&nbsp;{$lng.lbl_order} {if $usertype eq 'A' || $usertype eq 'P'}# {/if}{$order_prefix_prev}{$orderid_prev}</a>{/if}
{if $orderid_next ne ""}{if $orderid_prev ne ""} | {/if}<a href="order.php?orderid={$orderid_next}">{$lng.lbl_order} {if $usertype eq 'A' || $usertype eq 'P'}# {/if}{$order_prefix_next}{$orderid_next}&nbsp;&gt;&gt;</a>{/if}
</p>

<table cellspacing="1" cellpadding="2" class="ButtonsRow">
<tr>
{if $usertype eq "P" || $usertype eq 'A'}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_print_order target="_blank" href="order.php?orderid=`$order.orderid`&mode=printable"}</td>
{/if}
{if $active_modules.RMA ne '' && $current_membership_flag ne 'FS'} 
{if ($usertype eq  'C' || $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode)) && $return_products ne ''}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_create_return href="#returns"}</td>
{/if}
{if ($usertype eq  'C' || $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode)) && $order.is_returns}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_order_returns href="returns.php?mode=search&search[orderid]=`$order.orderid`"}</td>
{/if}
{/if}
{if $active_modules.Shipping_Label_Generator ne '' && ($usertype eq 'A' || $usertype eq 'P')} 
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_shipping_label href="generator.php?orderid=`$order.orderid`"}</td>
{/if}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_print_invoice target="_blank" href="order.php?orderid=`$order.orderid`&mode=invoice"}</td>
{if ($usertype eq "A" or ($usertype eq "P" and $active_modules.Simple_Mode)) and $active_modules.Advanced_Order_Management}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_modify href="order.php?orderid=`$order.orderid`&mode=edit"}</td>
{/if}
</tr>
</table>
{/if}

<p />
{if $usertype eq "C"}
<hr />
{include file="mail/html/order_invoice.tpl" is_nomail='Y'}
{elseif $usertype eq "A" || ($usertype eq 'P' && $active_modules.Simple_Mode)}
{include file="main/order_info_admin.tpl" static=$membership_static}
{else}
{include file="main/order_info.tpl"}
{/if}
	</td>
</tr>
<tr>
	<td height="1" valign="top">
<script type="text/javascript">
<!--
var details_mode = false;
var details_fields_labels = new Object();
{foreach from=$order_details_fields_labels key=dfield item=dlabel}
details_fields_labels["{$dfield|escape:javascript}"] = "{$dlabel|escape:javascript}";
{/foreach}
-->
</script>
{include file="main/include_js.tpl" src="main/history_order.js"}
<form action="order.php" method="post" name="ordernotesform">
<input type="hidden" name="send_email" value="N" />

{if $usertype ne "C"}
<p />
{$lng.lbl_customer_notes}:<br />
<textarea name="customer_notes" cols="70" rows="8" style="width: 520px;"{if $current_membership_flag eq 'FS'} readonly="readonly"{/if}>{$order.customer_notes}</textarea>
<p />
{/if}

{if $usertype eq "A" or ($usertype eq "P" and $active_modules.Simple_Mode)}

{if $order.extra.ip ne ''}
<p />
{$lng.lbl_ip_address}: {$order.extra.ip}{if $order.extra.proxy_ip ne ''} ({$order.extra.proxy_ip}){/if}<br />
{if $active_modules.Stop_List ne ''}
{if $order.blocked eq 'Y'}
<font class="Star">{$lng.lbl_ip_address_blocked}</font><br />
{else}
<input type="button" value="{$lng.lbl_block_ip_address|strip_tags:false|escape}" onclick="javascript: self.location='order.php?mode=block_ip&amp;orderid={$orderid}'" />
{/if}
{/if}
{* $active_modules.Stop_List ne '' *}

{/if}

{if $active_modules.Anti_Fraud ne ''}
<input type="button" value="{$lng.lbl_af_lookup_address|strip_tags:false|escape}" onclick="javascript: window.open('{$catalogs.admin}/anti_fraud.php?mode=popup&amp;ip={$order.extra.ip}&amp;proxy_ip={$order.extra.proxy_ip}','AFLOOKUP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');" />
{/if}{* $active_modules.Anti_Fraud ne '' *}

<p />
{$lng.lbl_order_details}:
{if !$order.details_encrypted}
<div style="text-align: right; width: 520px; padding-bottom: 3px;">
<a id="view_mode" href="javascript: void(0);" onclick="javascript: switch_details_mode(false, this, document.getElementById('edit_mode'));" style="font-weight: bold;">{$lng.lbl_view_mode}</a>
&nbsp;&nbsp;&nbsp;
<a id="edit_mode" href="javascript: void(0);" onclick="javascript: switch_details_mode(true, this, document.getElementById('view_mode'));">{$lng.lbl_edit_mode}</a>
</div>
{/if}
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<textarea id="details_view" cols="70" style="color: #666666; background-color:#EEEEEE; width: 520px;" readonly="readonly" rows="12"{if $order.details_encrypted} disabled="disabled"{/if}>{$order.details|func_order_details_translate|escape:quotes}</textarea>
</td>
{if $cidev_order_details_TransID ne ""}
<td>
&nbsp; <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id={$cidev_order_details_TransID}" style="color: #1411FF;">Link to PayPal transaction</a>
</td>
{/if}
</tr>
</table>
{if $order.details_encrypted eq ''}
<textarea id="details_edit" style="display: none; width: 520px;" name="details" cols="70" rows="12">{$order.details|escape:quotes}</textarea>
{/if}
{/if}

{if $usertype ne "C"}
<p />
{$lng.lbl_order_notes}:<br />
<textarea name="notes" cols="70" style="width: 520px;" rows="8">{$order.notes}</textarea>
{/if}

{if $usertype eq "A" || $usertype eq "P"}
<p />
<input type="submit" value="{$lng.lbl_apply_changes|strip_tags:false|escape}" />
{if $current_membership_flag ne 'FS'}
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" value="{$lng.lbl_apply_changes_send_email|strip_tags:false|escape}" onclick="javascript: document.ordernotesform.send_email.value = 'Y'; document.ordernotesform.submit();" /><br />
{/if}
{if $usertype neq "A"}
{$lng.txt_apply_changes}	
{/if}
{/if}

{if $active_modules.Special_Offers ne "" && ($usertype eq "A" or $usertype eq "P")}
<br /><br /><br />
{include file="modules/Special_Offers/order_extra_data.tpl" data=$order.extra}
{/if}

{if ($usertype eq "A" or ($usertype eq "P" and $active_modules.Simple_Mode)) && $active_modules.Anti_Fraud}
<br /><br /><br />
{include file="modules/Anti_Fraud/extra_data.tpl" data=$order.extra.Anti_Fraud}
{/if}

{if ($usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode)) && $order.is_egood ne '' && $active_modules.Egoods}
<p />
<input type="button" value="{if $order.is_egood eq 'Y'}{$lng.lbl_prolong_ttl|strip_tags:false|escape}{else}{$lng.lbl_regenerate_ttl|strip_tags:false|escape}{/if}" onclick="javascript: self.location='order.php?mode=prolong_ttl&amp;orderid={$orderid}'" /><br />
{$lng.txt_prolong_ttl}
{/if}

<input type="hidden" name="mode" value="status_change" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
</form>

{if $usertype eq "P" and $order.dc_status ne "C"}
<br />
<form action="order.php" method="post">
<input type="hidden" name="mode" value="complete_order" />
<input type="submit" value="{$lng.lbl_complete_order|strip_tags:false|escape}" /><br />
{$lng.txt_complete_order}
<input type="hidden" name="orderid" value="{$order.orderid}" />
</form>
{/if}

{if $active_modules.Order_Tracking ne "" and $order.tracking ne ""}

<br /><br /><br />

{include file="main/subheader.tpl" title=$lng.lbl_tracking_order}

{assign var="postal_service" value=$order.shipping|truncate:3:"":true}
{$lng.lbl_tracking_number}: {$order.tracking}
<br /><br />

{if $postal_service eq "UPS"}
{include file="modules/Order_Tracking/ups.tpl"}
{elseif $postal_service eq "USP"}
{include file="modules/Order_Tracking/usps.tpl"}
{elseif $postal_service eq "Fed"}
{include file="modules/Order_Tracking/fedex.tpl"}
{elseif $postal_service eq "Aus"}
{include file="modules/Order_Tracking/australia_post.tpl"}
{/if}

{/if}

	</td>
</tr>
</table>
{/capture}
{include file="dialog.tpl" title=$order_details_name content=$smarty.capture.dialog extra='width="100%"'}
{if $active_modules.RMA ne '' && ($usertype eq  'C' || ($usertype eq 'A' && $current_membership_flag ne 'FS') || ($usertype eq 'P' && $active_modules.Simple_Mode))}

<br />
<a name="returns"></a>
{include file="modules/RMA/add_returns.tpl"}
{/if}

{if $usertype eq 'A' && $order_manufacturers && $current_membership_flag ne 'FS'}
<br />
<a name="mnf_notify"></a>

{capture name=dialog}
{foreach from=$order_manufacturers item=v key=mnf_id}


 {assign var=show_to_order_entry_operator value=""}
 {assign var=show_request_availability value=""}
 {assign var=show_dispatch_to_distributor value=""}

 {if $v.d_availability_must_be_checked eq "Y" && $v.submit_to_operator eq "through_distributor_website"}
	{if $v.dc_status eq 'T'}
                {assign var=show_to_order_entry_operator value=""}
                {assign var=show_request_availability value="Y"}
                {assign var=show_dispatch_to_distributor value=""}
	{/if}

	{if $v.dc_status eq 'K'}
                {assign var=show_to_order_entry_operator value="Y"}
                {assign var=show_request_availability value=""}
                {assign var=show_dispatch_to_distributor value=""}
	{/if}

 {elseif $v.d_availability_must_be_checked eq "Y" && $v.submit_to_operator ne "through_distributor_website"}

        {if $v.dc_status eq 'T'}
                {assign var=show_to_order_entry_operator value=""}
                {assign var=show_request_availability value="Y"}
                {assign var=show_dispatch_to_distributor value=""}
        {/if}

        {if $v.dc_status eq 'K' || $v.dc_status eq 'M'}
                {assign var=show_to_order_entry_operator value=""}
                {assign var=show_request_availability value=""}
                {assign var=show_dispatch_to_distributor value="Y"}
        {/if}

 {elseif $v.d_availability_must_be_checked ne "Y" && $v.submit_to_operator eq "through_distributor_website"}

        {if $v.dc_status eq 'T'}
                {assign var=show_to_order_entry_operator value="Y"}
                {assign var=show_request_availability value=""}
                {assign var=show_dispatch_to_distributor value=""}
        {/if}

 {elseif $v.d_availability_must_be_checked ne "Y" && $v.submit_to_operator ne "through_distributor_website"}

        {if $v.dc_status eq 'T'}
                {assign var=show_to_order_entry_operator value=""}
                {assign var=show_request_availability value=""}
                {assign var=show_dispatch_to_distributor value="Y"}
        {/if}

 {/if}


 {if $v.actual_shipping_cost eq "0"}
	{assign var=show_request_additional_shipping_charge value=""}
 {else}
	{if $v.additional_shipping_charge gt 0 && ($v.dc_status ne "B" && $v.dc_status ne "S" && $v.dc_status ne "G")}
		{assign var=show_request_additional_shipping_charge value="Y"}
	{else}
		{assign var=show_request_additional_shipping_charge value=""}
	{/if}
 {/if}


{*
 {assign var=show_request_additional_shipping_charge value=""} 
 {if $v.dc_status eq "M"}
	{assign var=show_request_additional_shipping_charge value="Y"}
 {/if}
*}

 {assign var=show_stock_availability_form value=""}
 {if $v.dc_status eq 'K' || $v.dc_status eq 'E'}
	{assign var=show_stock_availability_form value="Y"}
 {/if}


{*
<br />For testing:
<br />show_to_order_entry_operator = '{$show_to_order_entry_operator}'
<br />show_request_availability = '{$show_request_availability}'
<br />show_dispatch_to_distributor = '{$show_dispatch_to_distributor}'
<br /><br /><br />
*}

 {if $show_to_order_entry_operator eq "Y"}
  <form action="order.php" method="post" name="1_mnfnotifyform_{$mnf_id}">
  <input type="hidden" name="orderid" value="{$order.orderid}" />
  <input type="hidden" name="mnf_id" value="{$mnf_id}" />
  <input type="hidden" name="mode" value="cidev_send_email_to_operator" />

  <div class="ProductTitle" align="center">{$v.manufacturer}: Order entry</div>
  <br />
  <B>Order entry operator email:</B> {$v.d_order_entry_operator_email}<br />
  <br />
  <B>Instructions to order entry operator:</B>
  <br />
  <textarea rows="20" cols="60" name="mnf_body" style="width: 80%;">{$v.d_instructions_to_order_entry_operator}</textarea><br /><br />
  <input type="submit" value="Submit to order entry operator" /><br /><br />
  <hr /><br />
  </form>
 {/if}


 {if $show_request_availability eq "Y"}
  <form action="order.php" method="post" name="2_mnfnotifyform_{$mnf_id}">
  <input type="hidden" name="orderid" value="{$order.orderid}" />
  <input type="hidden" name="mnf_id" value="{$mnf_id}" />
  <input type="hidden" name="mode" value="mnf_notify" />
  <input type="hidden" name="set_status_K" value="Y" />

  <div class="ProductTitle" align="center">{$v.manufacturer}: Request availability</div>
  <B>{$lng.lbl_from}:</B><br />
  <input type="text" name="mnf_from" value="{$config.Company.orders_department}" readonly="readonly" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_to}:</B><br />
  <input type="text" name="mnf_to" value="{$v.d_send_to_email_14}" style="width: 80%;" /><br /><br />
  <B>Subject line:</B><br />
  <input type="text" name="d_email_subject_14" value="{$v.d_email_subject_14}" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_message_body}:</B><br />
  <textarea rows="20" cols="60" name="mnf_body" style="width: 80%;">{$v.d_message_body_14}</textarea><br /><br />
  <input type="submit" value="Send (Request availability)" /><br /><br />
  <hr /><br />
  </form>
 {/if}


 {if $show_dispatch_to_distributor eq "Y"}
  <form action="order.php" method="post" name="3_mnfnotifyform_{$mnf_id}">
  <input type="hidden" name="orderid" value="{$order.orderid}" />
  <input type="hidden" name="mnf_id" value="{$mnf_id}" />
  <input type="hidden" name="mode" value="mnf_notify" />
  <input type="hidden" name="show_s3stores_site_in_invoice" value="Y" />

  <div class="ProductTitle" align="center">{$v.manufacturer}: Dispatch to distributor</div>
  <B>{$lng.lbl_from}:</B><br />
  <input type="text" name="mnf_from" value="{$config.Company.orders_department}" readonly="readonly" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_to}:</B><br />
  <input type="text" name="mnf_to" value="{$v.email}" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_message_body}:</B><br />
  <textarea rows="20" cols="60" name="mnf_body" style="width: 80%;">{$v.mess_body}</textarea><br /><br />
  <input type="submit" value="Send (Dispatch to distributor)" /><br /><br />
  <hr /><br />
  </form>
 {/if}


 {if $show_request_additional_shipping_charge eq "Y"} 
  <form action="order.php" method="post" name="4_mnfnotifyform_{$mnf_id}">
  <input type="hidden" name="orderid" value="{$order.orderid}" />
  <input type="hidden" name="mnf_id" value="{$mnf_id}" />
  <input type="hidden" name="mode" id="mode_request_additional_shipping_charge" value="request_additional_shipping_charge" /> 

  <div class="ProductTitle" align="center">{$v.manufacturer}: Request additional shipping charge</div>
  <br />

  <table>
  <tr>
  <td align="right">
  Estimated profit = 
  </td>
  <td>
  {if $v.estimated_profit_abs ne ""}<span style="color: #FF0000;">(${$v.estimated_profit_abs})</span>{else}${$v.estimated_profit}{/if} 
  </td>
  <td>
  =  {if $v.estimated_profit_margin_percent_abs ne ""}<span style="color: #FF0000;">({$v.estimated_profit_margin_percent_abs}%)</span>{else}{$v.estimated_profit_margin_percent}%{/if}
  </td>
  </tr>

  <tr>
  <td align="right">
  Estimated profit after additional payment = 
  </td>
  <td>
  {if $v.estimated_profit_after_additional_payment_abs ne ""}<span style="color: #FF0000;">(${$v.estimated_profit_after_additional_payment_abs})</span>{else}${$v.estimated_profit_after_additional_payment}{/if} 
  </td>
  <td>
= {if $v.estimated_profit_margin_after_additional_payment_percent_abs ne ""}<span style="color: #FF0000;">({$v.estimated_profit_margin_after_additional_payment_percent_abs}%)</span>{else}{$v.estimated_profit_margin_after_additional_payment_percent}%{/if}
  </td>
  </tr>
  </table>
  <br /><br />
  <B>{$lng.lbl_from}:</B><br />
  <input type="text" name="mnf_from" value="{$config.Company.orders_department}" readonly="readonly" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_to}:</B><br />
  <input type="text" name="mnf_to" value="{$customer.email}{if $config.Additional_shipping_charge.copy_to_email ne ""}, {$config.Additional_shipping_charge.copy_to_email}{/if}" style="width: 80%;" /><br /><br />
  <B>Subject line:</B><br />
  <input type="text" name="d_email_subject_14" value="{$v.additional_shipping_charge_subject_line}" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_message_body}:</B><br />
  <textarea rows="20" cols="60" name="mnf_body" style="width: 80%;">{$v.additional_shipping_charge_message}</textarea><br /><br />
  <input type="submit" value="Send (Request additional shipping charge)" />
  <input type="button" value="Waive" onclick="javascript: $('#mode_request_additional_shipping_charge').val('waive'); this.form.submit();" />
  <br /><br />
  <hr /><br />
  </form>
 {/if}


 {if $show_stock_availability_form eq "Y"}
	<div class="ProductTitle" align="center">{$v.manufacturer}: Information request</div>
	{include file="customer/main/stock_availability.tpl" o=$order.orderid m=$mnf_id products=$products order=$order admin_area_uses="Y"}
	<br /><br />
	<hr /><br />
 {/if}

{/foreach}


{if $backorder_decision_request_message ne ""}
  <br />
  <form action="order.php" method="post" name="decision_request_message_form">
  <input type="hidden" name="orderid" value="{$order.orderid}" />
  <input type="hidden" name="mode" id="mode_backorder_decision_request" value="backorder_decision_request" />

  <div class="ProductTitle" align="center">Backorder decision request</div>
  <B>{$lng.lbl_from}:</B><br />
  <input type="text" name="mnf_from" value="{$config.Company.orders_department}" readonly="readonly" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_to}:</B><br />
  <input type="text" name="mnf_to" value="{$customer.email}{if $config.backorder_decision_request.backorder_copy_to_email ne ""}, {$config.backorder_decision_request.backorder_copy_to_email}{/if}" style="width: 80%;" /><br /><br />
  <B>Subject line:</B><br />
  <input type="text" name="d_email_subject_14" value="{$backorder_decision_request_subject_line}" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_message_body}:</B><br />
  <textarea rows="20" cols="60" name="mnf_body" style="width: 80%;">{$backorder_decision_request_message}</textarea><br /><br />
  <input type="submit" value="Send (Backorder decision request)" />
  <br /><br />
  <hr /><br />
  </form>
{/if}


{/capture}
{include file="dialog.tpl" title="Distributor communications" content=$smarty.capture.dialog extra='width="100%"'}

{/if}

{if $usertype eq 'A' or ($usertype eq "P" and $active_modules.Simple_Mode)}
<br />
<a name="accounting"></a>
{include file="main/order_accounting.tpl" static=$membership_static}
{/if}

{if ($usertype eq 'A' or $usertype eq 'P') and $active_modules.Google_Checkout ne '' and $order.extra.goid ne ''}

<br />
<a name="gcheckout"></a>
{include file="modules/Google_Checkout/gcheckout_order.tpl"}
{/if}
