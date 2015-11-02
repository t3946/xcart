{* $Id: history_order.tpl,v 1.83.2.17 2006/12/25 11:53:29 svowl Exp $ *}
{if $current_membership_flag eq 'FS'}
{assign var="membership_static" value="F"}
{else}
{assign var="membership_static" value=""}
{/if}
{include file="main/multirow.tpl"}

<script src="{$SkinDir}/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
{literal}

tinymce.init({
    selector: "textarea.new_editor",
    height: 350,
    resize: "both",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    forced_root_block : false,
    force_br_newlines : true,
    force_p_newlines : false,
    convert_urls: false,
    relative_urls: false
});

{/literal}
//]]>
</script>


<script type="text/javascript">
<!--
multirowInputSets['track'] = [];
multirowInputSets['track'].noCloneContent = 1;
-->
</script>


<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

function func_set_value_to_field(form, fefix_field, field, mnf_id){

        if (!form)
                return;

        var textarea_field = fefix_field + field + '_' + mnf_id;
        var hidden_field = field;
        var textarea_field_value = "";

//	disableEditor(textarea_field, textarea_field); 

        for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].name == textarea_field){
                        textarea_field_value = form.elements[i].value;
                } 
        }

        for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].name == hidden_field){
                        form.elements[i].value = textarea_field_value;
                } 
        }

	form.submit();
}

{/literal}
//]]>
</script>

{assign var="order_details_name" value="Order # `$order.order_prefix``$order.orderid`"}

<table width="100%">
<tr>
<td align="left" width="33%">
{if $usertype ne "C"}
{if $orderid_prev ne ""}<a href="order.php?orderid={$orderid_prev}">&lt;&lt;&nbsp;{$lng.lbl_order} {if $usertype eq 'A' || $usertype eq 'P'}# {/if}{$order_prefix_prev}{$orderid_prev}</a>{/if}
{/if}
</td>

<td align="center" width="*" nowrap="nowrap">
 <table align="center">
 <tr>
 <td>
 {include file="page_title.tpl" title=$order_details_name}
 </td>
 {if $ticket_resolver_link ne ""}
 <td style="font-size: 15px; {* font-weight: bold; *}">
        / <a target="_blank" style="color: #140BFC;{* text-decoration: none;*}" href="{$ticket_resolver_link}">OTRS ticket{if $ticket_resolver_messages ne ""} ({$ticket_resolver_messages}){/if}</a>
 </td>
 {/if}
 <td>
	/ <a target="_blank" style="color: #140BFC" href="https://mail.google.com/mail/u/0/#search/{$order.order_prefix}{$order.orderid}+OR+%22SFP-{$order.orderid}%22">Gmail</a>
 </td>
 </tr>
 </table>
</td>

<td align="right" width="33%">
{if $usertype ne "C"}
{if $orderid_next ne ""}<a href="order.php?orderid={$orderid_next}">{$lng.lbl_order} {if $usertype eq 'A' || $usertype eq 'P'}# {/if}{$order_prefix_next}{$orderid_next}&nbsp;&gt;&gt;</a>{/if}
{/if}
</td>
</tr>
</table>


{if $you_cannot_modify_order eq "Y"}
<br />
  <table width="100%">
  <tr>
  <td align="center" style="border: solid 1px #000000; background: #F4CCCC;">
	{$warning_message}
  </td>
  </tr>
  </table>
{else}
    <table width="100%">
    <tr>
    <td align="center" style="border: solid 1px #000000; background: #D9EAD3;">
    {if $order_unlocked eq "Y"}
	{$unlock_message}
    {else}
	<form action="order.php?orderid={$orderid}" method="post" name="unlockorderform">
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
{/if}
<br /><br />


{$lng.txt_order_details_top_text}

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


{if $usertype ne "C"}
{capture name=compose_email}

<table width="100%" style="background-color: #d9ead3;">
<tr>
<td><B>Compose emails:</B></td>
</tr>
<tr>
<td>

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
                    <select name="select_department" id="select_department" onchange="javascript: $('#td_customer_department').hide(); $('#td_distributor_department').hide(); $('#td_our_customer_service_department').hide(); $('#td_third_party_department').hide(); if (this.value!='') $('#'+this.value).show();">
                        <option value="">Select receiving party</option>
                        <option value="td_customer_department">Compose email to customer</option>
                        <option value="td_distributor_department">Compose email to distributor</option>
                        <option value="td_our_customer_service_department">Compose email to our customer service</option>
                        <option value="td_third_party_department">Compose email to third party</option>
                    </select>
	</td>

        <td id="td_customer_department" nowrap="nowrap" style="display: none;">
                {if $department_full_arr.customer ne ""}
                <form name="customer_department_form" action="#" method="POST">

                    <select name="customer_department" id="customer_department" {* style="width: 70%;" *}>
                        {foreach from=$department_full_arr.customer item=item key=key}
                        <option value="{$item.id}">{$item.template_name}</option>
                        {/foreach}
                    </select>
                    <input type="button" name="Compose" value="Compose" onclick="javascript: $('#td_customer_department').hide(); $('#td_distributor_department').hide(); $('#td_our_customer_service_department').hide(); $('#td_third_party_department').hide(); $('#select_department').val(''); window.open('compose_message.php?orderid={$order.orderid}&department=customer&template_id='+$('#customer_department').val());">
                </form>
                {/if}
        </td>

        <td id="td_distributor_department" nowrap="nowrap" style="display: none;">
                {if $department_full_arr.distributor ne "" && $order_manufacturers ne ""}
                <form name="distributor_department_form" action="#" method="POST">

                    <select name="distributor_department" id="distributor_department" {* style="width: 70%;" *} >

                        {foreach from=$order_manufacturers item=v key=k}

                                <optgroup label="{$v.manufacturer}">

                                {foreach from=$department_full_arr.distributor item=item key=key}
                                        <option value="{$k}-{$item.id}">{$item.template_name}</option>
                                {/foreach}

                        {/foreach}
                    </select>
                    <input type="button" name="Compose" value="Compose" onclick="javascript: $('#td_customer_department').hide(); $('#td_distributor_department').hide(); $('#td_our_customer_service_department').hide(); $('#td_third_party_department').hide(); $('#select_department').val(''); window.open('compose_message.php?orderid={$order.orderid}&department=distributor&mid_templateid='+$('#distributor_department').val());">
                </form>
                {/if}
        </td>

        <td id="td_our_customer_service_department" nowrap="nowrap" style="display: none;">
                {if $department_full_arr.our_customer_service ne ""}
                <form name="our_customer_service_form" action="#" method="POST">

                    <select name="our_customer_service_department" id="our_customer_service_department" {* style="width: 70%;" *}>
                        {foreach from=$department_full_arr.our_customer_service item=item key=key}
                        <option value="{$item.id}">{$item.template_name}</option>
                        {/foreach}
                    </select>
                    <input type="button" name="Compose" value="Compose" onclick="javascript: $('#td_customer_department').hide(); $('#td_distributor_department').hide(); $('#td_our_customer_service_department').hide(); $('#td_third_party_department').hide(); $('#select_department').val(''); window.open('compose_message.php?orderid={$order.orderid}&department=our_customer_service&template_id='+$('#our_customer_service_department').val());">
                </form>
                {/if}
        </td>

        <td id="td_third_party_department" nowrap="nowrap" style="display: none;">
                {if $department_full_arr.third_party ne ""}
                <form name="third_party_form" action="#" method="POST">

                    <select name="third_party_department" id="third_party_department" {* style="width: 70%;" *}>
                        {foreach from=$department_full_arr.third_party item=item key=key}
                        <option value="{$item.id}">{$item.template_name}</option>
                        {/foreach}
                    </select>
                    <input type="button" name="Compose" value="Compose" onclick="javascript: $('#td_customer_department').hide(); $('#td_distributor_department').hide(); $('#td_our_customer_service_department').hide(); $('#td_third_party_department').hide(); $('#select_department').val(''); window.open('compose_message.php?orderid={$order.orderid}&department=third_party&template_id='+$('#third_party_department').val());">
                </form>
                {/if}
        </td>
</tr>

</table>
</td>
</tr>
</table>
{/capture}
{*
{include file="dialog.tpl" title="Email templates" content=$smarty.capture.dialog extra='width="100%"'}
<br />
*}
{/if}


{if $usertype ne "C"}

{if $order.note_is_taken_care_of eq "N" && $order.customer_notes ne ""}
{capture name=customer_notes}

<form name="customer_notes_form" action="order.php" method="POST">
<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="mode" value="note_is_taken_care_of" />
{$order.customer_notes}
<br />
<br />
<input type="submit" value="This note is taken care of, remove it.">
</form>

{/capture}
{/if}



{capture name=order_progress}
 <table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
   <td width="34%" valign="top">
        <table cellspacing="0" cellpadding="0" border="0">
        <tr><td><B>Order date:</B></td><td>{$order.date|date_format:'%d-%b-%Y&nbsp; %H:%M'}</td></tr>
        <tr><td><B>Current date:</B>&nbsp;</td><td>{$current_date|date_format:'%d-%b-%Y&nbsp; %H:%M'}</td></tr>
        <tr><td nowrap="nowrap"><B>Fraud check:</B></td><td><a href="fraud_page.php?orderid={$order.orderid}" target="_blank" style="color: #140BFC">{include file="main/fraud_status.tpl" fraud_status=$order.fraud_status fraud_static="Y"} ({$order.overall_fraud_score})</a></td></tr>
        </table>
   </td>

   <td width="*" valign="top">

        {if $cidev_order_details_TransID ne ""}

		<B>Customer to business payments:</B><br />
                <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id={$cidev_order_details_TransID}" style="color: #1411FF;">Link to PayPal transaction</a>
        {/if}

	{if $link_to_virtual_terminal_transaction ne ""}

		{if $cidev_order_details_TransID eq ""}
		<B>Customer to business payments:</B><br />
		{/if}

		{foreach from=$link_to_virtual_terminal_transaction item=vl key=kl}
			<br />{$vl}
		{/foreach}
	{/if}
   </td>

   <td width="25%" valign="top" align="right">
                 <table cellspacing="0" cellpadding="0" border="0">
                 <tr>
                 <td nowrap="nowrap" valign="top"><B>Add attention tag:</B>&nbsp;</td>
                 <td valign="top">
                 <div style="margin-top: -3px;">
                 <form action="order.php" method="post" name="order_add_additional_tag">
                 <input type="hidden" name="mode" id="mode_additional_tag" value="add_additional_tag" />
                 <input type="hidden" name="orderid" value="{$order.orderid}" />
                 <input type="hidden" name="del_status_id" id="del_status_id" value="" />
                 <select name="additional_tag_status" onchange="javascript: document.order_add_additional_tag.submit();">
                 <option value="">
                        {foreach from=$attention_tags_values item=item key=key}
                            <option value="{$item.status_id}">{$item.status}</option>
                        {/foreach}
                 </select>

		  {if $order.attention_tags ne ""}
			<br />
			<table align="right">
			{foreach from=$order.attention_tags item=item key=key}
			    {if $item.status_id gt 0}
				<tr>
				<td style="background-color: #F4CCCC; color: #000000;">{$item.status}</td>
				<td><a href="javascript: void();" onclick="javascript: $('#mode_additional_tag').val('del_additional_tag'); $('#del_status_id').val('{$item.status_id}'); document.order_add_additional_tag.submit();" style="color: red; font-weight: bold; text-decoration: none;">X</a></td>
				</tr>
			    {/if}
			{/foreach}
			</table>
		  {/if}
                 </td>
                 </tr>
                 </table>

                 </form>
                 </div>
   </td>

  </tr>
 </table>
{/capture}
{*
{include file="dialog.tpl" title="Order ticket" content=$smarty.capture.dialog extra='width="100%"'}
<br />
*}
{/if}


{if $usertype eq 'A' && $order_manufacturers && $current_membership_flag ne 'FS'}
{assign var=found_show_stock_availability_form value=""}
{foreach from=$order_manufacturers item=v key=mnf_id}
 {if $v.dc_status eq 'K' || $v.dc_status eq 'E' || $v.dc_status eq 'M'}
  {assign var=found_show_stock_availability_form value="Y"}
 {/if}
{/foreach}

{if $found_show_stock_availability_form eq "Y"}
 {capture name=stock_request}
  {foreach from=$order_manufacturers item=v key=mnf_id}

   {assign var=show_stock_availability_form value=""}
   {if $v.dc_status eq 'K' || $v.dc_status eq 'E' || $v.dc_status eq 'M'}
        {assign var=show_stock_availability_form value="Y"}
   {/if}

   {if $show_stock_availability_form eq "Y"}

        <a name="information_request_{$mnf_id}"></a>

        <div class="ProductTitle" align="center">{$v.manufacturer}: Stock request</div>
        {include file="customer/main/stock_availability.tpl" o=$order.orderid m=$mnf_id products=$products order=$order admin_area_uses="Y"}
        <br /><br />
        <hr /><br />
   {/if}

  {/foreach}
 {/capture}
<br />
{/if}
{/if}






{assign var=colspan value=10}

<table width="100%">
<tr> 
	<td valign="top" colspan="{$colspan}">

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

	{capture name=order_details}

	{$smarty.capture.order_progress}
	<br />

	{include file="main/order_info_admin.tpl" static=$membership_static}
	{/capture}

	{capture name=customer_info}
	{include file="main/order_info_admin_customer_info.tpl" static=$membership_static}
	{/capture}

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

{*
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

{/if}

{if $active_modules.Anti_Fraud ne ''}
<input type="button" value="{$lng.lbl_af_lookup_address|strip_tags:false|escape}" onclick="javascript: window.open('{$catalogs.admin}/anti_fraud.php?mode=popup&amp;ip={$order.extra.ip}&amp;proxy_ip={$order.extra.proxy_ip}','AFLOOKUP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');" />
{/if}

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

*}

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


{* ---------------- *}
{if $usertype eq 'A' || $usertype eq 'P'}
{capture name=logs}
{include file="main/order_logs.tpl"}
{/capture}
{/if}
{* ---------------- *}




{if $active_modules.RMA ne '' && ($usertype eq  'C' || ($usertype eq 'A' && $current_membership_flag ne 'FS') || ($usertype eq 'P' && $active_modules.Simple_Mode))}
<br />
<a name="returns"></a>
{include file="modules/RMA/add_returns.tpl"}
{/if}


{if $usertype eq 'A' && $order_manufacturers && $current_membership_flag ne 'FS'}
{*
<a name="mnf_notify">
<br /> 
*}

{capture name=email_communications}

{$smarty.capture.compose_email}
<hr />
<br />
<br />

{foreach from=$order_manufacturers item=v key=mnf_id}


 {assign var=show_to_order_entry_operator value=""}
 {assign var=show_request_availability value=""}
 {assign var=show_dispatch_to_distributor value=""}

 {if $v.d_availability_must_be_checked eq "Y" && $v.submit_to_operator eq "through_distributor_website"}
	{if $v.dc_status eq 'T'}
                {assign var=show_request_availability value="Y"}
	{/if}

	{if ($v.cb_status eq 'O' || $v.cb_status eq 'P' || $v.cb_status eq '3' || $v.cb_status eq 'H') && ($v.dc_status eq 'T' || $v.dc_status eq 'K' || $v.dc_status eq 'M')}
        	{assign var=show_to_order_entry_operator value="Y"}
	{/if}

 {elseif $v.d_availability_must_be_checked eq "Y" && $v.submit_to_operator ne "through_distributor_website"}

        {if $v.dc_status eq 'T'}
                {assign var=show_request_availability value="Y"}
        {/if}

	{if ($v.cb_status eq 'O' || $v.cb_status eq 'P' || $v.cb_status eq '3' || $v.cb_status eq 'H') && ($v.dc_status eq 'T' || $v.dc_status eq 'K' || $v.dc_status eq 'M')}
	        {assign var=show_dispatch_to_distributor value="Y"}
	{/if}

 {elseif $v.d_availability_must_be_checked ne "Y" && $v.submit_to_operator eq "through_distributor_website"}

        {if ($v.cb_status eq 'O' || $v.cb_status eq 'P' || $v.cb_status eq '3' || $v.cb_status eq 'H') && ($v.dc_status eq 'T' || $v.dc_status eq 'K' || $v.dc_status eq 'M')}
                {assign var=show_to_order_entry_operator value="Y"}
        {/if}

 {elseif $v.d_availability_must_be_checked ne "Y" && $v.submit_to_operator ne "through_distributor_website"}

	{if ($v.cb_status eq 'O' || $v.cb_status eq 'P' || $v.cb_status eq '3' || $v.cb_status eq 'H') && ($v.dc_status eq 'T' || $v.dc_status eq 'K' || $v.dc_status eq 'M')}
	        {assign var=show_dispatch_to_distributor value="Y"}
	{/if}

 {/if}

{*
 {if $v.actual_shipping_cost eq "0" || ($v.estimated_profit_margin_percent gte $config.Additional_shipping_charge.threshhold_margin)}
*}
 {if $v.actual_shipping_cost eq "0"}
	{assign var=show_request_additional_shipping_charge value=""}
 {else}
	{if $v.additional_shipping_charge gt 0 && ($v.dc_status ne "B" && $v.dc_status ne "S" && $v.dc_status ne "G")}
		{assign var=show_request_additional_shipping_charge value="Y"}
	{else}
		{assign var=show_request_additional_shipping_charge value=""}
	{/if}
 {/if}

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


{if $backorder_decision_request_message ne ""}
  <a name="backorder_decision_request"></a>
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
{*  <textarea rows="20" cols="60" name="mnf_body" style="width: 80%;">{$backorder_decision_request_message}</textarea><br /><br /> *}
{*
  {include file="main/textarea_def.tpl" name="mnf_body" cols="60" rows="30" class="InputWidth" data=$backorder_decision_request_message|replace:"\n":"<br />" width="99%" btn_rows="30"}
*}
  <textarea rows="60" cols="60" name="mnf_body" style="width: 80%;" class="new_editor">{$backorder_decision_request_message|replace:"\n":"<br />"}</textarea>

{*
  <input type="submit" value="Send (Backorder decision request)" />
*}

{*
  <INPUT type="button" value="Send (Backorder decision request)" onclick="disableEditor('mnf_body','mnf_body'); document.decision_request_message_form.submit();">
*}
  <INPUT type="button" value="Send (Backorder decision request)" onclick="tinyMCE.triggerSave(); document.decision_request_message_form.submit();">

  <br /><br />
  <hr /><br />
  </form>
{/if}


 {if $show_request_additional_shipping_charge eq "Y"}
  <a name="request_additional_shipping_charge_{$mnf_id}"></a>
  <form action="order.php" method="post" name="manuf_notify_form_{$mnf_id}">
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
  <input type="text" name="mnf_to" value="{$customer.email}{if $order.po_details && $config.Additional_shipping_charge.po_copy_to_email ne ""}, {$config.Additional_shipping_charge.po_copy_to_email}{else}{if $config.Additional_shipping_charge.copy_to_email ne ""}, {$config.Additional_shipping_charge.copy_to_email}{/if}{/if}" style="width: 80%;" /><br /><br />
  <B>Subject line:</B><br />
  <input type="text" name="d_email_subject_14" value="{$v.additional_shipping_charge_subject_line}" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_message_body}:</B><br />

{*  <textarea rows="20" cols="60" name="mnf_body" style="width: 80%;">{$v.additional_shipping_charge_message}</textarea><br /><br /> *}
{*  <input type="submit" value="Send (Request additional shipping charge)" /> *}


  {* --- *}
{*
  {include file="main/textarea_def.tpl" name="request_additional_shipping_charge_mnf_body_`$mnf_id`" cols="60" rows="30" class="InputWidth" data=$v.additional_shipping_charge_message|replace:"\n":"<br />" width="99%" btn_rows="30"}
*}

  <textarea rows="30" cols="60" name="request_additional_shipping_charge_mnf_body_{$mnf_id}" style="width: 80%;" class="new_editor">{$v.additional_shipping_charge_message|replace:"\n":"<br />"}</textarea>

  <input type="hidden" name="mnf_body" value="" />
  <br /><br />
  <input name="send_email_button" type="button" value="Send (Request additional shipping charge)" onclick="javascript: tinyMCE.triggerSave(); func_set_value_to_field(document.manuf_notify_form_{$mnf_id}, 'request_additional_shipping_charge_', 'mnf_body', {$mnf_id});" /><br /><br />
  {* --- *}


  <input type="button" value="Waive" onclick="javascript: $('#mode_request_additional_shipping_charge').val('waive'); tinyMCE.triggerSave(); this.form.submit();" />
  <br /><br />
  <hr /><br />
  </form>
 {/if}


 {if $show_dispatch_to_distributor eq "Y" && $order.fraud_status eq "C" && $order.shipping_groups.$mnf_id.acc_paymentid ne "" && $order.shipping_groups.$mnf_id.acc_paymentid gt 0}
  <a name="dispatch_to_distributor_{$mnf_id}"></a>
  <form action="order.php" method="post" name="manuf_notifyform_{$mnf_id}">
  <input type="hidden" name="orderid" value="{$order.orderid}" />
  <input type="hidden" name="mnf_id" value="{$mnf_id}" />
  <input type="hidden" name="mode" value="mnf_notify" />
  <input type="hidden" name="show_s3stores_site_in_invoice" value="Y" />
  <input type="hidden" name="cidev_hide_invoice" value="Y" />
  <div class="ProductTitle" align="center">{$v.manufacturer}: Dispatch to distributor</div>
  <B>{$lng.lbl_from}:</B><br />
  <input type="text" name="mnf_from" value="{$config.Company.orders_department}" readonly="readonly" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_to}:</B><br />
  <input type="text" name="mnf_to" value="{$v.email}" style="width: 80%;" /><br /><br />
  <B>Subject line:</B><br />
  <input type="text" name="d_email_subject_14" value="{$v.d_subject_line_8}" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_message_body}:</B><br />
{*  <textarea rows="20" cols="60" name="mnf_body" style="width: 80%;">{$v.mess_body}</textarea><br /><br /> *}
{*  <input type="submit" value="Send (Dispatch to distributor)" /><br /><br /> *}

  {* --- *}

{*
  {include file="main/textarea_def.tpl" name="dispatch_to_distributor_mnf_body_`$mnf_id`" cols="60" rows="30" class="InputWidth" data=$v.mess_body|replace:"\n":"<br />" width="99%" btn_rows="30"}
*}

  <textarea rows="30" cols="60" name="dispatch_to_distributor_mnf_body_{$mnf_id}" style="width: 80%;" class="new_editor">{$v.mess_body|replace:"\n":"<br />"}</textarea>

  <input type="hidden" name="mnf_body" value="" />
  <br /><br />

{$lng.lbl_send_dispatch_to_distributor}<br />
  <table>
  <tr>
  {if $v.d_shipping_options_arr ne ""}
  <td>
        <select name="d_shipping_options_name">
        {foreach from=$v.d_shipping_options_arr item=vv key=kk}
                <option value="{$vv|trademark:$insert_trademark}">{$vv|trademark:$insert_trademark}</option>
        {/foreach}
        </select>
  </td>
  {/if}

  <td>
  <input name="send_email_button" type="button" value="Send (Dispatch to distributor)" onclick="javascript: tinyMCE.triggerSave(); func_set_value_to_field(document.manuf_notifyform_{$mnf_id}, 'dispatch_to_distributor_', 'mnf_body', {$mnf_id});" />
  </td>
  </tr>
  </table>
  <br /><br />
  {* --- *}

  <hr /><br />
  </form>
 {/if}


 {if $show_to_order_entry_operator eq "Y" && $order.fraud_status eq "C"}
  <a name="order_entry_{$mnf_id}"></a>
  <form action="order.php" method="post" name="mnf_notify_form_{$mnf_id}">
  <input type="hidden" name="orderid" value="{$order.orderid}" />
  <input type="hidden" name="mnf_id" value="{$mnf_id}" />
  <input type="hidden" name="mode" value="cidev_send_email_to_operator" />
  <div class="ProductTitle" align="center">{$v.manufacturer}: Order entry</div>
  <br />
  <B>Order entry operator email:</B> {$v.d_order_entry_operator_email}<br />
  <br />
  <B>Subject line:</B><br />
  <input type="text" name="d_email_subject_14" value="{$v.d_order_entry_operator_subject_line_8}" style="width: 80%;" /><br /><br />
  <B>Instructions to order entry operator:</B>
  <br />
{*  <textarea rows="20" cols="60" name="mnf_body" style="width: 80%;">{$v.d_instructions_to_order_entry_operator}</textarea><br /><br /> *}
{*  <input type="submit" value="Submit to order entry operator" /><br /><br /> *}

  {* --- *}
{*
  {include file="main/textarea_def.tpl" name="order_entry_mnf_body_`$mnf_id`" cols="60" rows="30" class="InputWidth" data=$v.d_instructions_to_order_entry_operator|replace:"\n":"<br />" width="99%" btn_rows="30"}
*}

  <textarea rows="30" cols="60" name="order_entry_mnf_body_{$mnf_id}" style="width: 80%;" class="new_editor">{$v.d_instructions_to_order_entry_operator|replace:"\n":"<br />"}</textarea>

  <input type="hidden" name="mnf_body" value="" />
  <br /><br />
  <input name="send_email_button" type="button" value="Submit to order entry operator" onclick="javascript: tinyMCE.triggerSave(); func_set_value_to_field(document.mnf_notify_form_{$mnf_id}, 'order_entry_', 'mnf_body', {$mnf_id});" /><br /><br />
  {* --- *}

  <hr /><br />
  </form>
 {/if}

 {if $show_request_availability eq "Y"}
  <a name="request_availability_{$mnf_id}"></a>
  <form action="order.php" method="post" name="mnf_notifyform_{$mnf_id}">
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
{*  <textarea rows="20" cols="60" name="mnf_body" style="width: 80%;">{$v.d_message_body_14}</textarea> *}
{*  <input type="submit" value="Send (Request availability)" {if $v.good_time_to_send_email_to_distributor ne "Y"}disabled="disabled"{/if} /><br /><br /> *}

  {* --- *}
{*
  {include file="main/textarea_def.tpl" name="request_availability_mnf_body_`$mnf_id`" cols="60" rows="30" class="InputWidth" data=$v.d_message_body_14|replace:"\n":"<br />" width="99%" btn_rows="30"}
*}

<textarea rows="30" cols="60" name="request_availability_mnf_body_{$mnf_id}" style="width: 80%;" class="new_editor">{$v.d_message_body_14|replace:"\n":"<br />"}</textarea>


  <input type="hidden" name="mnf_body" value="" />
  <br /><br />
  <input name="send_email_button" type="button" value="Send (Request availability)" onclick="javascript: tinyMCE.triggerSave(); func_set_value_to_field(document.mnf_notifyform_{$mnf_id}, 'request_availability_', 'mnf_body', {$mnf_id});" {if $v.good_time_to_send_email_to_distributor ne "Y"}disabled="disabled"{/if} /><br /><br />
  {* --- *}

  <hr /><br />
  </form>
 {/if}


{*
 {if $show_stock_availability_form eq "Y"}

	<a name="information_request_{$mnf_id}"></a>

	<div class="ProductTitle" align="center">{$v.manufacturer}: Information request</div>
	{include file="customer/main/stock_availability.tpl" o=$order.orderid m=$mnf_id products=$products order=$order admin_area_uses="Y"}
	<br /><br />
	<hr /><br />
 {/if}
*}

{/foreach}

{if $request_missing_information_message ne ""}
  <a name="request_missing_information"></a>
  <br />
  <form action="order.php" method="post" name="request_missing_information_form">
  <input type="hidden" name="orderid" value="{$order.orderid}" />
  <input type="hidden" name="mode" id="mode_request_missing_information" value="request_missing_information" />
  <div class="ProductTitle" align="center">Request missing information</div>
  <B>{$lng.lbl_from}:</B><br />
  <input type="text" name="mnf_from" value="{$config.Company.orders_department}" readonly="readonly" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_to}:</B><br />
  <input type="text" name="mnf_to" value="{$customer.email}{if $config.Purchase_Order.po_missing_copy_to_email ne ""}, {$config.Purchase_Order.po_missing_copy_to_email}{/if}" style="width: 80%;" /><br /><br />
  <B>Subject line:</B><br />
  <input type="text" name="d_email_subject_14" value="{$request_missing_information_subject_line}" style="width: 80%;" /><br /><br />
  <B>{$lng.lbl_message_body}:</B><br />
  <textarea rows="30" cols="60" name="mnf_body" style="width: 80%;" class="new_editor">{$request_missing_information_message|replace:"\n":"<br />"}</textarea>

  <INPUT type="button" value="Send (Request missing information)" onclick="tinyMCE.triggerSave(); document.request_missing_information_form.submit();">

  <br /><br />
  <hr /><br />
  </form>
{/if}

{/capture}
{/if}

{if $usertype eq 'A' or ($usertype eq "P" and $active_modules.Simple_Mode)}
{*
<br />
<a name="accounting"></a>
*}
{capture name=accounting}
{include file="main/order_accounting.tpl" static=$membership_static}
{/capture}
{/if}

{if ($usertype eq 'A' or $usertype eq 'P') and $active_modules.Google_Checkout ne '' and $order.extra.goid ne ''}
<br />
<a name="gcheckout"></a>
{include file="modules/Google_Checkout/gcheckout_order.tpl"}
{/if}



{if $usertype eq 'A' || $usertype eq 'P'}

<script type="text/javascript">
//<![CDATA[
$(function() {ldelim}
  $('#main_order_tabs-container').tabs(

	{if $smarty.get.tab ne "y"}
		{if $order.note_is_taken_care_of eq "N" && $order.customer_notes ne ""}
			{if $found_show_stock_availability_form eq "Y"}
                                {literal}
                                        {selected: 2}
                                {/literal}
			{else}
				{literal}
					{selected: 1}
				{/literal}
			{/if}
		{else}
                        {if $found_show_stock_availability_form eq "Y"}
                                {literal}
                                        {selected: 1}
                                {/literal}
                        {else}
				{literal}
					{selected: 0}
				{/literal}
			{/if}
		{/if}
	{/if}

  );
{rdelim});
//]]>
</script>

<div id="main_order_tabs-container">
  <ul>
  {foreach from=$main_order_tabs item=tab key=ind}
    <li
	{if $tab.anchor eq "reference"}
		style="float: right"
	{/if}
    >
	<a {if $tab.anchor eq "order_details"}style="color: #580505; font-weight: bold;"{/if} href="#main_order_tabs-{$tab.anchor}">{$tab.title}</a>
    </li>
  {/foreach}
  </ul>

  {foreach from=$main_order_tabs item=tab key=ind}
      <div id="main_order_tabs-{$tab.anchor}">
	{if $tab.anchor eq "reference"}	
		{$config.Reference_tab.reference_text}
	{else}
	        {assign var=tab_section value=$tab.section}
        	{$smarty.capture.$tab_section}
	{/if}
      </div>
  {/foreach}
</div>
{/if}

