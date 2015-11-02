<table width="100%">
<tr>
<td width="49%" valign="top">

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
{include file="dialog.tpl" title="Customer notes" content=$smarty.capture.customer_notes extra='width="100%"'}
<br />
<br />
{/if}

</td>

<td width="*">&nbsp;</td>

<td width="49%" valign="top">

{if $other_customer_orders ne ""}
{capture name=other_orders}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

  $(document).ready(function() {  

        $('#orders_see_more').click(function() {
                $('#div_id_orders_2').toggle('slow', function() {
                // Animation complete.
                });

                $('#div_id_orders_see_more').toggle('slow', function() {
                // Animation complete.
                });
        });

        $('#orders_see_less').click(function() {
                $('#div_id_orders_2').toggle('slow', function() {
                // Animation complete.
                });

                $('#div_id_orders_see_more').toggle('slow', function() {
                // Animation complete.
                });
        });

  });

{/literal}
//]]>
</script>

Completed: {$count_Completed}, Fraud: {$count_Fraud}, In progress: {$count_Open}

<table>
{foreach from=$other_customer_orders item=v_o key=k_o}
{if $k_o lt $show_count_before_see_more}
<tr>
<td>
{assign var="v_o_status_found" value=""}
<a href="order.php?orderid={$v_o.orderid}" target="_blank" style="color: blue;">{$v_o.order_prefix}{$v_o.orderid}</a>{foreach from=$v_o.statuses item=v_o_status key=k_o_status}{if $v_o_status eq "Y"}{if $v_o_status_found eq "Y"}, {else}: {/if}<span style="background: {if $k_o_status eq 'Completed'}#D9EAD3{elseif $k_o_status eq 'Fraud'}Red{elseif $k_o_status eq 'Open'}#F4CCCC{/if};">{if $k_o_status eq "Open"}In progress{else}{$k_o_status}{/if}</span>{assign var="v_o_status_found" value="Y"}{/if}
{/foreach}
</td>
</tr>
{/if}
{/foreach}
</table>

{if $show_see_more eq "Y"}
<div id="div_id_orders_see_more" align="left"><a id="orders_see_more" style="color: blue;" href="javascript: void(0);">see more...</a></div>
{/if}

{if $show_see_more eq "Y"}
<div id="div_id_orders_2" style="display: none;">
<table>
{foreach from=$other_customer_orders item=v_o key=k_o}
{if $k_o gte $show_count_before_see_more}
<tr>
<td>
{assign var="v_o_status_found" value=""}
<a href="order.php?orderid={$v_o.orderid}" target="_blank" style="color: blue;">{$v_o.order_prefix}{$v_o.orderid}</a>{foreach from=$v_o.statuses item=v_o_status key=k_o_status}{if $v_o_status eq "Y"}{if $v_o_status_found eq "Y"}, {else}: {/if}<span style="background: {if $k_o_status eq 'Completed'}#D9EAD3{elseif $k_o_status eq 'Fraud'}Red{elseif $k_o_status eq 'Open'}#F4CCCC{/if};">{if $k_o_status eq "Open"}In progress{else}{$k_o_status}{/if}</span>{assign var="v_o_status_found" value="Y"}{/if}
{/foreach}
</td>
</tr>
{/if}
{/foreach}
</table>

	<div id="div_id_orders_see_less" align="left"><a style="color: blue;" id="orders_see_less" href="javascript: void(0);">see less...</a></div>
</div>
{/if}


{/capture}
{include file="dialog.tpl" title="Other orders from the same customer" content=$smarty.capture.other_orders extra='width="100%"'}
{/if}

</td>
</tr>
</table>

<form action="order.php" method="post" name="ordereditform2">
<input type="hidden" name="mode" value="order_edit_apply" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="send_email" id="send_email2" value="N" />

<a name="customer_info"></a>

{include file="main/subheader.tpl" title=$lng.lbl_customer_info}

<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
  <td width="47%" height="25"><b>{$lng.lbl_contact_information}</b></td>
  <td width="5%">&nbsp;</td>
  <td width="47%" height="25">


{if $order.po_details}<b>{$lng.lbl_po_info}</b>
{*
  <input type="text" name="orig_po" id="orig_po" value="{$order.orig_po|escape}" />
    {if $order.orig_po ne ""}<a target="_blank" href="{$order.orig_po}" style="color: #1F08F8;">{/if}View original PO{if $order.orig_po ne ""}</a>{/if}
*}
{/if}

  </td>
</tr>
<tr>
  <td bgcolor="#000000" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
  <td><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
  <td{if $order.po_details} bgcolor="#000000" height="2"{/if}>{if $order.po_details}<img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" />{/if}</td>
</tr>
<tr>
  <td colspan="3"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
  <td width="47%" valign="top">
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">
{if $customer.default_fields.company}
<tr>
  <td>{$lng.lbl_company}:</td>
  <td width="100%">{if !$static}<input type="text" name="customer_info[company]" value="{$customer.company}" />{else}{$customer.company}{/if}</td>
</tr>
{/if}
{if $customer.default_fields.tax_number}
<tr>
  <td><b>{$lng.lbl_tax_number}:</b></td>
  <td width="100%">{if !$static}<input type="text" name="customer_info[tax_number]" value="{$customer.tax_number}" />{else}{$customer.tax_number}{/if}</td>
</tr>
{/if}
{if $customer.default_fields.title}
<tr> 
<td><b>{$lng.lbl_title}:</b></td>
<td width="100%">{if !$static}<input type="text" name="customer_info[title]" value="{$customer.title}" />{else}{$customer.title}{/if}</td>
</tr>
{/if}
{if $customer.default_fields.firstname}
<tr>
  <td nowrap="nowrap"><b>{$lng.lbl_first_name}:</b></td>
  <td width="100%" nowrap="nowrap">{if !$static}<input type="text" name="customer_info[firstname]" value="{$customer.firstname}" style="width: 55%; {if $order.po_details && $customer.firstname|lower eq 'unknown'}background-color: #F4CCCC;{/if}" />{else}{$customer.firstname}{/if}
&nbsp;<a target="_blank" href="https://www.google.com/#q={$customer.firstname|replace:' ':'+'}" style="color: #1F08F8;">Google FN</a>
&nbsp;<a target="_blank" href="http://www.spokeo.com/search?q={$customer.firstname|replace:' ':'+'}" style="color: #1F08F8;">Spokeo FN</a>
  </td>
</tr>
{/if}
{if $customer.default_fields.lastname}
<tr>
  <td nowrap="nowrap"><b>{$lng.lbl_last_name}:</b></td>
  <td width="100%">{if !$static}<input type="text" name="customer_info[lastname]" value="{$customer.lastname}" />{else}{$customer.lastname}{/if}</td>
</tr>
{/if}
{if $customer.default_fields.phone}
<tr>
  <td><b>{$lng.lbl_phone}:</b></td>
  <td width="100%">
  {if !$static}<input type="text" name="customer_info[phone]" value="{$customer.phone}" style="width: 29%; {if $order.po_details && $customer.phone eq '(000) 000-0000'}background-color: #F4CCCC;{/if}" />{else}{$customer.phone}{/if}
  <b>{$lng.lbl_phone_ext}</b> {if !$static}<input type="text" name="customer_info[phone_ext]" value="{$customer.phone_ext}" style="width: 10%;" maxlength="6" />{else}{$customer.phone_ext}{/if}&nbsp;<a target="_blank" href="https://www.google.com/#q={$google_phone}" style="color: #1F08F8;">Google #</a>
&nbsp;<a target="_blank" href="http://www.spokeo.com/search?q={$google_phone}" style="color: #1F08F8;">Spokeo #</a>
  </td>
</tr>

{if $Telephone_area_code_info ne ""}
<tr>
  <td nowrap="nowrap"><b>Phone area code:</b></td>
  <td width="100%">{$Telephone_area_code_info}</td>
</tr>
{/if}

{/if}
{if $customer.default_fields.fax}
<tr>
  <td><b>{$lng.lbl_fax}:</b></td>
  <td width="100%">{if !$static}<input type="text" name="customer_info[fax]" value="{$customer.fax}" />{else}{$customer.fax}{/if}</td>
</tr>
{/if}
{if $customer.default_fields.email}
<tr>
  <td><b>{$lng.lbl_email}:</b></td>
  <td width="100%" nowrap="nowrap">
  {if !$static}<input type="text" name="customer_info[email]" value="{$customer.email}" style="width: 55%; {if $order.po_details && $customer.email|lower eq 'unknown@unknown.com'}background-color: #F4CCCC;{/if}" />{else}{$customer.email}{/if}
&nbsp;<a target="_blank" href="https://www.google.com/#q={$customer.email}{$fraud_Google_email_search_exclusions}" style="color: #1F08F8;">Google @</a>
&nbsp;<a target="_blank" href="http://www.spokeo.com/email-search/search?e={$customer.email}" style="color: #1F08F8;">Spokeo @</a>
&nbsp;<a target="_blank" href="{$userinfo_site}" style="color: #1F08F8;">WS</a>
 </td>
</tr>
{/if}
{if $customer.default_fields.url}
<tr>
  <td><b>{$lng.lbl_url}:</b></td>
  <td width="100%">{if !$static}<input type="text" name="customer_info[url]" value="{$customer.url}" />{else}{$customer.url}{/if}</td>
</tr>
{/if}
{foreach from=$customer.additional_fields item=v}
{if $v.section eq 'C' || $v.section eq 'P'}
<tr>
  <td><b>{$v.title}:</b></td>
      <td>{$v.value}</td>
</tr>
{/if}
{/foreach}
</table>
  </td>
  <td width="5%">&nbsp;</td>
  <td width="47%" style="vertical-align: top;">
  {if $order.po_details}
  <input type="hidden" name="po_update" value="1" />
  <table cellspacing="0" cellpadding="0" class="customer-info-edit" width="100%">
  <tr>
    <td width="24%"><b>{if $count_po_number gt 1}<font style="color: #FF0000;">{/if}{$lng.lbl_po_number}:{if $count_po_number gt 1}</font>{/if}</b></td>
    <td width="76%"><input type="text" name="po_number" id="po_number" value="{$order.po_details.po_number|escape}" /></td>
  </tr>
{if $count_po_number gt 1 && $used_po_for_the_same_order ne ""}
  <tr>
    <td colspan="2"><b>Orders with the same PO Number:</b> 
      {foreach from=$used_po_for_the_same_order item=v_po key=k_po}
        <a style="color: #1F08F8;" target="_blank" href="order.php?orderid={$v_po.orderid}">{$v_po.order_prefix}{$v_po.orderid}</a>{if $k_po ne $last_index_used_po_for_the_same_order},{/if}
      {/foreach}
    </td>
  </tr>
{/if}
  <tr>
    <td width="24%"><b>{$lng.lbl_company_name}:</b></td>
    <td width="76%"><input type="text" name="po_company_name" id="po_company_name" value="{$order.po_details.company_name|escape}" /></td>
  </tr>

{* --- *}
<tr>
  <td><b>Link to original PO:</b></td>
  <td width="100%" nowrap="nowrap">
  <input type="text" name="orig_po" id="orig_po" value="{$order.orig_po|escape}" style="width: 60%; {if $order.orig_po eq ""}background-color: #F4CCCC;{/if}" />
  {if $order.orig_po ne ""}<a target="_blank" href="{$order.orig_po}" style="color: #1F08F8;">{/if}View original PO{if $order.orig_po ne ""}</a>{/if}
  </td>
</tr>

<tr>
  <td><b>PO issued to:</b></td>
  <td width="100%" nowrap="nowrap">

  <input style="width: 7%;" type="radio" id="po_issued_to" name="po_issued_to" value="S"{if $order.po_issued_to eq "S"} checked="checked"{/if} />{$po_issued_to_arr.S}
&nbsp;&nbsp;&nbsp;
  <input style="width: 7%;" type="radio" id="po_issued_to" name="po_issued_to" value="A"{if $order.po_issued_to eq "A" || $order.po_issued_to eq ""} checked="checked"{/if} /><span {if $order.po_issued_to eq 'A' || $order.po_issued_to eq ""}style="background-color: #F4CCCC;"{/if}>{$po_issued_to_arr.A}</span>

  </td>
</tr>

<tr>
  <td><b>Total shipping charge on original PO:</b></td>
  <td width="100%">
  <input type="text" name="total_shipping_charge_on_orig_po" id="total_shipping_charge_on_orig_po" value="{$order.total_shipping_charge_on_orig_po|escape}" style="width: 20%; {if $order.total_shipping_charge_on_orig_po lte 0}background-color: #F4CCCC;{/if}" />
  </td>
</tr>
{* --- *}

{*
  <tr>
    <td width="24%"><b>Position:</b> </td>
    <td width="76%"><input type="text" name="po_position" id="po_position" value="{$order.po_details.position|escape}" /></td>
  </tr>
*}
  </table>
  {/if}
  </td>
</tr>
</table>

{if $order.po_details}
<br />
<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
  <td width="47%" height="25"><b>Purchase manager</b></td>
  <td width="5%">&nbsp;</td>
  <td width="47%" height="25"><b>Accounts payable</b></td>
</tr>
<tr>
  <td bgcolor="#000000" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
  <td><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
  <td bgcolor="#000000" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
</tr>
<tr>
  <td colspan="3"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
  <td>
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">

  <tr>
    <td width="24%"><b>Full Name:</b> </td>
    <td width="76%"><input style="width: 55%; {if $order.po_details.name_of_purchaser|lower eq 'unknown'}background-color: #F4CCCC;{/if}" type="text" name="name_of_purchaser" id="name_of_purchaser" value="{$order.po_details.name_of_purchaser|escape}" />&nbsp;<a target="_blank" href="https://www.google.com/#q={$order.po_details.name_of_purchaser|replace:' ':'+'}" style="color: #1F08F8;">Google FN</a></td>
  </tr>

  <tr>
    <td width="24%"><b>Phone:</b> </td>
    <td width="76%">
<input type="text" name="purchase_manager_phone" id="purchase_manager_phone" value="{$order.po_details.purchase_manager_phone|escape}" style="width: 29%; {if $order.po_details.purchase_manager_phone eq '(000) 000-0000'}background-color: #F4CCCC;{/if}" />

  <b>{$lng.lbl_phone_ext}</b> <input type="text" name="purchase_manager_phone_ext" id="purchase_manager_phone_ext" value="{$order.po_details.purchase_manager_phone_ext|escape}" style="width: 10%;" maxlength="6" />&nbsp;<a target="_blank" href="https://www.google.com/#q={$google_purchase_manager_phone}" style="color: #1F08F8;">Google phone</a>

    </td>
  </tr>

  {if $purchase_manager_phone_code_info ne ""}
  <tr>
    <td nowrap="nowrap" width="24%"><b>Phone area code:</b></td>
    <td width="76%">{$purchase_manager_phone_code_info}</td>
  </tr>
  {/if}

  <tr>
    <td width="24%"><b>Fax:</b> </td>
    <td width="76%"><input type="text" name="po_fax" id="po_fax" value="{$order.po_details.po_fax|escape}" style="{if $order.po_details.po_fax eq '(000) 000-0000'}background-color: #F4CCCC;{/if}" /></td>
  </tr>

  {if $po_fax_area_code_info ne ""}
  <tr>
    <td nowrap="nowrap" width="24%"><b>Fax area code:</b></td>
    <td width="76%">{$po_fax_area_code_info}</td>
  </tr>
  {/if}

  <tr>
    <td width="24%"><b>Email:</b> </td>
    <td width="76%"><input type="text" name="purchase_manager_email" id="purchase_manager_email" value="{$order.po_details.purchase_manager_email|escape}" style="{if $order.po_details.purchase_manager_email|lower eq 'unknown@unknown.com'}background-color: #F4CCCC;{/if}" /></td>
  </tr>

  </table>
  </td>
  <td><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
  <td>
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">

  <tr>
    <td width="24%"><b>Full Name:</b> </td>
    <td width="76%"><input style="width: 55%; {if $order.po_details.accounts_payable_full_name|lower eq 'unknown'}background-color: #F4CCCC;{/if}" type="text" name="accounts_payable_full_name" id="accounts_payable_full_name" value="{$order.po_details.accounts_payable_full_name|escape}" />&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$order.po_details.accounts_payable_full_name|replace:' ':'+'}" style="color: #1F08F8;">Google FN</a></td>
  </tr>

  <tr>
    <td width="24%"><b>Phone:</b> </td>
    <td width="76%">
      <input type="text" name="accounts_payable_phone" id="accounts_payable_phone" value="{$order.po_details.accounts_payable_phone|escape}" style="width: 29%; {if $order.po_details.accounts_payable_phone eq '(000) 000-0000'}background-color: #F4CCCC;{/if}" />
  <b>{$lng.lbl_phone_ext}</b> <input type="text" name="accounts_payable_phone_ext" id="accounts_payable_phone_ext" value="{$order.po_details.accounts_payable_phone_ext|escape}" style="width: 10%;" maxlength="6" />&nbsp;<a target="_blank" href="https://www.google.com/#q={$google_accounts_payable_phone}" style="color: #1F08F8;">Google phone</a>
    </td>
  </tr>

  {if $accounts_payable_phone_code_info ne ""}
  <tr>
    <td nowrap="nowrap" width="24%"><b>Phone area code:</b></td>
    <td width="76%">{$accounts_payable_phone_code_info}</td>
  </tr>
  {/if}

  <tr>
    <td width="24%"><b>Fax:</b></td>
    <td width="76%"><input type="text" name="accounts_payable_fax" id="accounts_payable_fax" value="{$order.po_details.accounts_payable_fax|escape}" style="{if $order.po_details.accounts_payable_fax eq '(000) 000-0000'}background-color: #F4CCCC;{/if}" /></td>
  </tr>

  {if $accounts_payable_fax_code_info ne ""}
  <tr>
    <td nowrap="nowrap" width="24%"><b>Fax area code:</b></td>
    <td width="76%">{$accounts_payable_fax_code_info}</td>
  </tr>
  {/if}

  <tr>
    <td width="24%"><b>Email:</b></td>
    <td width="76%"><input type="text" name="accounts_payable_email" id="accounts_payable_email" value="{$order.po_details.accounts_payable_email|escape}" style="{if $order.po_details.accounts_payable_email|lower eq 'unknown@unknown.com'}background-color: #F4CCCC;{/if}" /></td>
  </tr>

  </table>
  </td>
</tr>
</table>
{/if}

<br />
<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
  <td width="47%" height="25"><b>{$lng.lbl_shipping_address}</b>&nbsp;<a target="_blank" href="https://www.google.com/#q={$google_shipping_address}" style="color: #1F08F8;">Google this address</a>&nbsp;&nbsp;<a target="_blank" href="http://www.spokeo.com/search?q={$spokeo_shipping_address}" style="color: #1F08F8;">Spokeo this address</a></td>
  <td width="5%">&nbsp;</td>
  <td width="47%" height="25"><b>{$lng.lbl_billing_address}</b>&nbsp;<a target="_blank" href="https://www.google.com/#q={$google_billing_address}" style="color: #1F08F8;">Google this address</a>&nbsp;&nbsp;<a target="_blank" href="http://www.spokeo.com/search?q={$spokeo_billing_address}" style="color: #1F08F8;">Spokeo this address</a></td>
</tr>
<tr>
  <td bgcolor="#000000" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
  <td><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
  <td bgcolor="#000000" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
</tr>
<tr>
  <td colspan="3"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
  <td>
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">
{if $customer.default_fields.s_firstname}
  <tr>
    <td><b>{$lng.lbl_first_name}:</b> </td>
    <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="customer_info[s_firstname]" value="{$customer.s_firstname}" />{else}{$customer.s_firstname}{/if}
&nbsp;<a target="_blank" href="https://www.google.com/#q={$customer.s_firstname|replace:' ':'+'}+{$customer.s_zipcode|replace:' ':'+'}" style="color: #1F08F8;">Google FN + zip code</a>
    </td>
  </tr>
{/if}
{if $customer.default_fields.s_lastname}
  <tr>
    <td><b>{$lng.lbl_last_name}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_lastname]" value="{$customer.s_lastname}" />{else}{$customer.s_lastname}{/if}</td>
  </tr>
{/if}
{foreach from=$customer.additional_fields item=v}
{if $v.section eq 'S'}
  <tr>
    <td>{if $v.title ne "Company"}<b>{/if}{$v.title}:{if $v.title ne "Company"}</b>{/if}</td>
        <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="additional_fields[{$v.fieldid}]" value="{$v.value}" />{else}{$v.value}{/if}
{if $v.title eq "Company"}&nbsp;<a target="_blank" href="https://www.google.com/#q={$s_company_company|replace:' ':'+'}" style="color: #1F08F8;">Google company</a>{/if}
        </td>
  </tr>
{/if}
{/foreach}
{if $customer.default_fields.s_address}
  <tr>
    <td><b>{$lng.lbl_address}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_address]" value="{$customer.s_address}" />{else}{$customer.s_address}{/if}</td>
  </tr>
  <tr>
    <td nowrap="nowrap">{$lng.lbl_address_2}: </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_address_2]" value="{$order.s_address_2}" />{else}{$order.s_address_2}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_city}
  <tr>
    <td><b>{$lng.lbl_city}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_city]" value="{$customer.s_city}" />{else}{$customer.s_city}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_county && $config.General.use_counties eq 'Y'}
  <tr>
    <td><b>{$lng.lbl_county}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_county]" value="{$customer.s_county}" />{else}{$customer.s_countyname}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_state}
  <tr>
    <td><b>{$lng.lbl_state}:</b> </td>
    <td width="100%">{if !$static}
{include file="main/states.tpl" states=$states name="customer_info[s_state]" default=$customer.s_state default_country=$customer.s_country|default:$config.General.default_country country_name="customer_info[s_country]"}
{else}{$customer.s_statename}{/if}

&nbsp; <B>Abbreviation:</B> {$customer.s_state}

    </td>
  </tr>
{/if}
{if $customer.default_fields.s_country}
  <tr>
    <td><b>{$lng.lbl_country}:</b> </td>
    <td width="100%">{if !$static}
<select name="customer_info[s_country]" id="customer_info_s_country" size="1">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}"{if $customer.s_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $customer.s_country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
{/section}
{if $customer.default_fields.s_state}
{include file="main/register_states.tpl" state_name="customer_info[s_state]" country_name="customer_info[s_country]" county_name="customer_info[s_county]" state_value=$customer.s_state county_value=$customer.s_county country_id="customer_info_s_country"}
{/if}
</select>
{else}{$customer.s_countryname}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_zipcode}
  <tr>
    <td><b>{$lng.lbl_zip_code}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_zipcode]" value="{$customer.s_zipcode}" style="width: 50%;" />{else}{$customer.s_zipcode}{/if}
&nbsp;<a style="color: blue;" href="javascript: void(0);" onclick="javascript: window.open('orders.php?fast_search=Y&posted_data[s_zipcode]={$order.s_zipcode}&mode=');">{$order.s_zipcode}</a>
    </td>
  </tr>
{/if}
  </table>
  </td>
  <td>&nbsp;</td>
  <td>
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">
{if $customer.default_fields.b_firstname}
  <tr>
    <td><b>{$lng.lbl_first_name}:</b> </td>
    <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="customer_info[b_firstname]" value="{$customer.b_firstname}" />{else}{$customer.b_firstname}{/if}
&nbsp;<a target="_blank" href="https://www.google.com/#q={$customer.b_firstname|replace:' ':'+'}+{$customer.b_zipcode|replace:' ':'+'}" style="color: #1F08F8;">Google FN + zip code</a>
    </td>
  </tr>
{/if}
{if $customer.default_fields.b_lastname}
  <tr>
    <td><b>{$lng.lbl_last_name}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_lastname]" value="{$customer.b_lastname}" />{else}{$customer.b_lastname}{/if}</td>
  </tr>
{/if}
{foreach from=$customer.additional_fields item=v}
{if $v.section eq 'B'}
  <tr>
    <td>{if $v.title ne "Company"}<b>{/if}{$v.title}:{if $v.title ne "Company"}</b>{/if}</td>
        <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="additional_fields[{$v.fieldid}]" value="{$v.value}" />{else}{$v.value}{/if}
{if $v.title eq "Company"}&nbsp;<a target="_blank" href="https://www.google.com/#q={$b_company_company|replace:' ':'+'}" style="color: #1F08F8;">Google company</a>{/if}
    </td>
  </tr>
{/if}
{/foreach}
{if $customer.default_fields.b_address}
  <tr>
    <td><b>{$lng.lbl_address}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_address]" value="{$customer.b_address}" />{else}{$customer.b_address}{/if}</td>
  </tr>
  <tr>
    <td nowrap="nowrap">{$lng.lbl_address_2}: </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_address_2]" value="{$customer.b_address_2}" />{else}{$customer.b_address_2}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_city}
  <tr>
    <td><b>{$lng.lbl_city}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_city]" value="{$customer.b_city}" />{else}{$customer.b_city}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_county && $config.General.use_counties eq 'Y'}
  <tr>
    <td><b>{$lng.lbl_county}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_county]" id="customer_info_b_county" value="{$customer.b_county}" />{else}{$customer.b_countyname}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_state}
  <tr>
    <td><b>{$lng.lbl_state}:</b> </td>
    <td width="100%">{if !$static}
{include file="main/states.tpl" states=$states name="customer_info[b_state]" default=$customer.b_state default_country=$customer.b_country|default:$config.General.default_country country_name="customer_info[b_country]"}
{else}{$customer.b_statename}{/if}

&nbsp; <B>Abbreviation:</B> {$customer.b_state}

    </td>
  </tr>
{/if}
{if $customer.default_fields.b_country}
  <tr>
    <td><b>{$lng.lbl_country}:</b> </td>
    <td width="100%">{if !$static}
<select name="customer_info[b_country]" id="customer_info_b_country" size="1">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}"{if $customer.b_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $customer.b_country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
{/section}
</select>
{if $customer.default_fields.b_state}
{include file="main/register_states.tpl" state_name="customer_info[b_state]" country_name="customer_info[b_country]" county_name="customer_info[b_county]" state_value=$customer.b_state county_value=$customer.b_county country_id="customer_info_b_country"}
{/if}
{else}{$customer.b_countryname}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_zipcode}
  <tr>
    <td><b>{$lng.lbl_zip_code}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_zipcode]" value="{$customer.b_zipcode}" />{else}{$customer.b_zipcode}{/if}</td>
  </tr>
{/if}

  <tr>
    <td><b>Customer's IP:</b> </td>
    <td width="100%">&nbsp;{$order.extra.ip_info}</td>
  </tr>

  </table>
      </td>
</tr>

{assign var="is_header" value=""}
{foreach from=$customer.additional_fields item=v}
{if $v.section eq 'A'}
{if $is_header eq ''}
<tr>
<td colspan="3">&nbsp;</td>
</tr>
<tr>
<td width="45%" height="25"><b>{$lng.lbl_additional_information}</b></td>
<td colspan="2" width="55%">&nbsp;</td>
</tr>
<tr>
<td bgcolor="#000000" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
<td colspan="2" width="55%"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
<td colspan="3"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
<td><table cellspacing="0" cellpadding="0" width="100%" border="0">
{assign var="is_header" value="E"}
{/if}
<tr valign="top">
<td>{if $v.title ne "Company"}<b>{/if}{$v.title}{if $v.title ne "Company"}</b>{/if}</td>
  <td width="100%">{if !$static}<input type="text" name="additional_fields[{$v.fieldid}]" value="{$v.value}" />{else}{$v.value}{/if}</td>
</tr>
{/if}
{/foreach}
{if $is_header eq 'E'}
</table></td>
<td colspan="2" width="55%">&nbsp;</td>
</tr>
{/if}

</table>

<br />

{if !$static}
<input type="submit" value="{$lng.lbl_apply_changes|escape}" {if $order.amazonorderid ne "" || $order.allow_dispatch_off_working_hours_functionality_enabled_found eq "Y"}disabled="disabled" style="border: 1px solid #ff0000" {/if} />
{if $current_membership_flag ne 'FS'}
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="{$lng.lbl_apply_changes_send_email|escape}" onclick="javascript: $('#send_email2').val('Y'); this.form.submit();" {if $order.amazonorderid ne "" || $order.allow_dispatch_off_working_hours_functionality_enabled_found eq "Y"}disabled="disabled" style="border: 1px solid #ff0000" {/if} />
{/if}
{/if}

{*
{if $cidev_order_details_TransID ne ""}
  &nbsp;&nbsp;&nbsp;&nbsp; <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id={$cidev_order_details_TransID}" style="color: #1411FF;">Link to PayPal transaction</a>
{/if}
*}

</form>
