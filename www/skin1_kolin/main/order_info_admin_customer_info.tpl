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

{include file="main/other_customer_orders.tpl"}

</td>
</tr>
</table>

<form action="order.php" method="post" name="ordereditform2">
<input type="hidden" name="mode" value="order_edit_apply" id="mode_ordereditform2" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="send_email" id="send_email2" value="N" />

<a name="customer_info"></a>

{include file="main/subheader.tpl" title=$lng.lbl_customer_info}

<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
  <td width="47%" height="25"><b>{$lng.lbl_contact_information}</b></td>
  <td width="5%">&nbsp;</td>
  <td width="47%" height="25">


{if $order.po_details}

<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
<td>
<b>{$lng.lbl_po_info}</b>
{*
  <input type="text" name="orig_po" id="orig_po" value="{$order.orig_po|escape}" />
    {if $order.orig_po ne ""}<a target="_blank" href="{$order.orig_po}" style="color: #1F08F8;">{/if}View original PO{if $order.orig_po ne ""}</a>{/if}
*}

</td>

<td align="right">

{if $convert_to_regular_order_show_button}
	<input type="button" value="Convert to regular order" onclick="javascript: $('#mode_ordereditform2').val('convert_to_regular_order'); this.form.submit();" />
{/if}

</td>
</tr>
</table>

{/if}

  </td>
</tr>
<tr>
  <td style="font-size:0; height: 2px;"" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
  <td><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
  <td{if $order.po_details} style="font-size:0; height: 2px;"" height="2"{/if}>{if $order.po_details}<img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" />{/if}</td>
</tr>
<tr>
  <td colspan="3"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
  <td width="47%" valign="top">
  <table cellspacing="0" cellpadding="1px" class="customer-info-edit">
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
      {if !$static}
          <input type="text"
                 name="customer_info[phone]"
                 value="{$customer.phone}"
                 style="width: 55%; {if $order.po_details && $customer.phone eq '(000) 000-0000'}background-color: #F4CCCC;{/if}"/>
      {else}
          {$customer.phone}
      {/if}

          <b>{$lng.lbl_phone_ext}</b>
          {if !$static}
              <input type="text"
                     name="customer_info[phone_ext]"
                     value="{$customer.phone_ext}"
                     style="width: 10%;"
                     maxlength="6"/>
          {else}
              {$customer.phone_ext}
          {/if}
          <br>
          <a target="_blank" href="https://www.google.com/#q={$google_phone}" style="color: #1F08F8;">Google #</a>
          &shy;
          <a target="_blank" href="http://www.spokeo.com/search?q={$google_phone}" style="color: #1F08F8;">Spokeo #</a>
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
          {assign var=oPOPipeLine value=$oOrder->getPOPipelineInstance()}
          <input type="hidden" name="po_update" value="1"/>
          <table cellspacing="0" cellpadding="0" class="customer-info-edit" width="100%">
              <tr>
                  <td width="24%"><b>Received by:</b></td>
                  <td width="76%">
                      <select name="purchase_order_received_status">
                        {html_options options=$oPOPipeLine->getRecievedStatuses() selected=$oPOPipeLine->getField('received_by')}
                      </select>
                  </td>
              </tr>
              <tr>
                  <td width="24%"><b>{if $count_po_number gt 1}<span style="color: #FF0000;">{/if}{$lng.lbl_po_number}
                              :{if $count_po_number gt 1}</span>{/if}</b></td>
                  <td width="76%"><input type="text" name="po_number" id="po_number"
                                         value="{$order.po_details.po_number|escape}"/></td>
              </tr>
              {if $count_po_number gt 1 && $used_po_for_the_same_order ne ""}
                  <tr>
                      <td colspan="2"><b>Orders with the same PO Number:</b>
                          {foreach from=$used_po_for_the_same_order item=v_po key=k_po}
                              <a style="color: #1F08F8;" target="_blank"
                                 href="order.php?orderid={$v_po.orderid}">{$v_po.order_prefix}{$v_po.orderid}</a>{if $k_po ne $last_index_used_po_for_the_same_order},{/if}
                          {/foreach}
                      </td>
                  </tr>
              {/if}
              <tr>
                  <td width="24%"><b>{$lng.lbl_company_name}:</b></td>
                  <td width="76%"><input type="text" name="po_company_name" id="po_company_name"
                                         value="{$order.po_details.company_name|escape}"/></td>
              </tr>

              {* --- *}
              <tr>
                  <td><b>Link to original PO:</b></td>
                  <td width="100%" nowrap="nowrap">
                      <input type="text" name="orig_po" id="orig_po" value="{$order.orig_po|escape}"
                             style="width: 60%; {if $order.orig_po eq ""}background-color: #F4CCCC;{/if}"/>
                      {if $oPOPipeLine->getPOId()}
                        <a target="_blank" href="{$oPOPipeLine->getOrderFileLink()}" style="color: #1F08F8;">View original PO</a>
                      {else}
                        {if $order.orig_po ne ""}
                            <a target="_blank" href="{$order.orig_po}" style="color: #1F08F8;">{/if}View original PO{if $order.orig_po ne ""}</a>
                        {/if}
                      {/if}

                  </td>
              </tr>
              <tr>
                  <td>
                      <b>Upload date:</b>
                  </td>
                  <td>
                      {$oPOPipeLine->getUploadDate()}
                  </td>
              </tr>

              <tr>
                  <td><b>PO issued to:</b></td>
                  <td width="100%" nowrap="nowrap">

                      <input style="width: 7%;" type="radio" id="po_issued_to" name="po_issued_to"
                             value="S"{if $order.po_issued_to eq "S"} checked="checked"{/if} />{$po_issued_to_arr.S}
                      &nbsp;&nbsp;&nbsp;
                      <input style="width: 7%;" type="radio" id="po_issued_to" name="po_issued_to"
                             value="A"{if $order.po_issued_to eq "A" || $order.po_issued_to eq ""} checked="checked"{/if} /><span
                              {if $order.po_issued_to eq 'A' || $order.po_issued_to eq ""}style="background-color: #F4CCCC;"{/if}>{$po_issued_to_arr.A}</span>

                  </td>
              </tr>

              <tr>
                  <td><b>Total shipping charge on original PO:</b></td>
                  <td width="100%">
                      <input type="text" name="total_shipping_charge_on_orig_po" id="total_shipping_charge_on_orig_po"
                             value="{$order.total_shipping_charge_on_orig_po|escape}"
                             style="width: 20%; {if $order.total_shipping_charge_on_orig_po lte 0}background-color: #F4CCCC;{/if}"/>
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
  <td style="font-size:0; height: 2px;"" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
  <td><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
  <td style="font-size:0; height: 2px;"" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
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
  <td style="font-size:0; height: 2px;"" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
  <td><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
  <td style="font-size:0; height: 2px;"" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
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
    <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="customer_info[s_firstname]" value="{$oOrder->getShippingFirstName()}" />{else}{$oOrder->getShippingFirstName()}{/if}
&nbsp;<a target="_blank" href="https://www.google.com/#q={$oOrder->getShippingFirstName()|replace:' ':'+'}+{$oOrder->getShippingZipCode()|replace:' ':'+'}" style="color: #1F08F8;">Google FN + zip code</a>
    </td>
  </tr>
{/if}
{if $customer.default_fields.s_lastname}
  <tr>
    <td><b>{$lng.lbl_last_name}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_lastname]" value="{$oOrder->getShippingLastName()}" />{else}{$oOrder->getShippingLastName()}{/if}</td>
  </tr>
{/if}
  <tr>
    <td>Company:</td>
        <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="additional_fields[2]" value="{$oOrder->getShippingCompany()}" />{else}{$oOrder->getShippingCompany()}{/if}
&nbsp;<a target="_blank" href="https://www.google.com/#q={$oOrder->getShippingCompany()|replace:' ':'+'}" style="color: #1F08F8;">Google company</a>
        </td>
  </tr>
{if $customer.default_fields.s_address}
  <tr>
    <td><b>{$lng.lbl_address}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_address]" value="{$oOrder->getShippingAddress()}" />{else}{$oOrder->getShippingAddress()}{/if}</td>
  </tr>
  <tr>
    <td nowrap="nowrap">{$lng.lbl_address_2}: </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_address_2]" value="{$oOrder->getShippingAddress2()}" />{else}{$oOrder->getShippingAddress2()}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_city}
  <tr>
    <td><b>{$lng.lbl_city}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_city]" value="{$oOrder->getShippingCity()}" />{else}{$oOrder->getShippingCity()}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_county && $config.General.use_counties eq 'Y'}
  <tr>
    <td><b>{$lng.lbl_county}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_county]" value="{$oOrder->getShippingCounty()}" />{else}{$oOrder->getShippingCounty()}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_state}
  <tr>
    <td><b>{$lng.lbl_state}:</b> </td>
    <td width="100%">{if !$static}
{include file="main/states.tpl" states=$states name="customer_info[s_state]" default=$oOrder->getShippingState() default_country=$oOrder->getShippingCountry()|default:$config.General.default_country country_name="customer_info[s_country]"}
{else}{$oOrder->getShippingState()}{/if}

&nbsp; <B>Abbreviation:</B> {$oOrder->getShippingState()}

    </td>
  </tr>
{/if}
{if $customer.default_fields.s_country}
  <tr>
    <td><b>{$lng.lbl_country}:</b> </td>
    <td width="100%">{if !$static}
<select name="customer_info[s_country]" id="customer_info_s_country" size="1">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}"{if $oOrder->getShippingCountry() eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $oOrder->getShippingCountry() eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
{/section}
{if $customer.default_fields.s_state}
{include file="main/register_states.tpl" state_name="customer_info[s_state]" country_name="customer_info[s_country]" county_name="customer_info[s_county]" state_value=$oOrder->getShippingState() county_value=$oOrder->getShippingCounty() country_id="customer_info_s_country"}
{/if}
</select>
{else}{$oOrder->getShippingCountry()}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_zipcode}
  <tr>
    <td><b>{$lng.lbl_zip_code}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_zipcode]" value="{$oOrder->s_zipcode}" style="width: 50%;" />{else}{$oOrder->s_zipcode}{/if}
&nbsp;<a style="color: blue;" href="{$xcartApp->router->url('dashboard:search')}?search[customer][zip_code]={$oOrder->s_zipcode}" target="_blank" >{$oOrder->s_zipcode}</a>
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
    <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="customer_info[b_firstname]" value="{$oOrder->getBillingFirstName()}" />{else}{$oOrder->getBillingFirstName()}{/if}
&nbsp;<a target="_blank" href="https://www.google.com/#q={$oOrder->getBillingFirstName()|replace:' ':'+'}+{$oOrder->getBillingZipCode()|replace:' ':'+'}" style="color: #1F08F8;">Google FN + zip code</a>
    </td>
  </tr>
{/if}
{if $customer.default_fields.b_lastname}
  <tr>
    <td><b>{$lng.lbl_last_name}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_lastname]" value="{$oOrder->getBillingLastName()}" />{else}{$oOrder->getBillingLastName()}{/if}</td>
  </tr>
{/if}
  <tr>
    <td>Company:</td>
    <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="additional_fields[1]" value="{$oOrder->getBillingCompany()}" />{else}{$oOrder->getBillingCompany()}{/if}
    &nbsp;<a target="_blank" href="https://www.google.com/#q={$oOrder->getBillingCompany()|replace:' ':'+'}" style="color: #1F08F8;">Google company</a>
    </td>
  </tr>
{if $customer.default_fields.b_address}
  <tr>
    <td><b>{$lng.lbl_address}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_address]" value="{$oOrder->getBillingAddress()}" />{else}{$oOrder->getBillingAddress()}{/if}</td>
  </tr>
  <tr>
    <td nowrap="nowrap">{$lng.lbl_address_2}: </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_address_2]" value="{$oOrder->getBillingAddress2()}" />{else}{$oOrder->getBillingAddress2()}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_city}
  <tr>
    <td><b>{$lng.lbl_city}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_city]" value="{$oOrder->getBillingCity()}" />{else}{$oOrder->getBillingCity()}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_county && $config.General.use_counties eq 'Y'}
  <tr>
    <td><b>{$lng.lbl_county}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_county]" id="customer_info_b_county" value="{$oOrder->getBillingCounty()}" />{else}{$oOrder->getBillingCounty()}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_state}
  <tr>
    <td><b>{$lng.lbl_state}:</b> </td>
    <td width="100%">{if !$static}
{include file="main/states.tpl" states=$states name="customer_info[b_state]" default=$oOrder->getBillingState() default_country=$oOrder->getBillingCountry()|default:$config.General.default_country country_name="customer_info[b_country]"}
{else}{$oOrder->getBillingState()}{/if}

&nbsp; <B>Abbreviation:</B> {$oOrder->getBillingState()}

    </td>
  </tr>
{/if}
{if $customer.default_fields.b_country}
  <tr>
    <td><b>{$lng.lbl_country}:</b> </td>
    <td width="100%">{if !$static}
<select name="customer_info[b_country]" id="customer_info_b_country" size="1">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}"{if $oOrder->getBillingCountry() eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $oOrder->getBillingCountry() eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
{/section}
</select>
{if $customer.default_fields.b_state}
{include file="main/register_states.tpl" state_name="customer_info[b_state]" country_name="customer_info[b_country]" county_name="customer_info[b_county]" state_value=$oOrder->getBillingState() county_value=$oOrder->getBillingCountry() country_id="customer_info_b_country"}
{/if}
{else}{$oOrder->getBillingCountry()}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_zipcode}
  <tr>
    <td><b>{$lng.lbl_zip_code}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_zipcode]" value="{$oOrder->getBillingZipCode()}" />{else}{$oOrder->getBillingZipCode()}{/if}</td>
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
<td style="font-size:0; height: 2px;"" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
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

{*
<input type="submit" value="{$lng.lbl_apply_changes|escape}" {if ($order.amazonorderid ne "" && $order.amazon_fulfillment_channel ne "AFN") || $order.allow_dispatch_off_working_hours_functionality_enabled_found eq "Y"}disabled="disabled" style="border: 1px solid #ff0000" {/if} />
*}
<input type="submit" value="{$lng.lbl_apply_changes|escape}" {if $order.allow_dispatch_off_working_hours_functionality_enabled_found eq "Y"}disabled="disabled" style="border: 1px solid #ff0000" {/if} />
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

<img src="/skin1_kolin/images/spacer_black.gif" height="2" width="100%" >

<form action="{$identity_check_url}" target="_blank">
    <input type="hidden" name="order_id" value="{$oOrder->orderid}">
    <input type="submit" value="Click here for identity check">
</form>