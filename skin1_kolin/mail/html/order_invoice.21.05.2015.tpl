{* $Id: order_invoice.tpl,v 1.37.2.1 2007/01/18 07:38:39 max Exp $ *}
{if $customer ne ''}{assign var="_userinfo" value=$customer}{else}{assign var="_userinfo" value=$userinfo}{/if}
{config_load file="$skin_config"}
{if $is_nomail ne 'Y'}
<p />
{/if}
<table cellspacing="0" cellpadding="0" width="{if $is_nomail eq 'Y'}100%{else}600{/if}" bgcolor="#ffffff">
<tr>
	<td>
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
		<td valign="top"><br /><br /><img src="{$ImagesDir}/S3-Stores-Logo-M.png" alt="" /></td>
		<td width="100%">
        {if $ref_notify eq 'Y'}
            {include file="mail/html/refund_invoice_head.tpl"}
        {else}
		<table cellspacing="0" cellpadding="2" width="100%">
		<tr>
			<td width="30">&nbsp;</td>
			<td valign="top">
<font style="FONT-SIZE: 28px"><b style="text-transform: uppercase;">{$lng.lbl_invoice}</b></font>
<br /><br />
<b>{$lng.lbl_date}:</b> {$order.date|date_format:$config.Appearance.datetime_format}<br /><b>{$lng.lbl_order_id}:</b> {if $type eq 'A'}<a href="http://www.artistsupplysource.com/admin/order.php?orderid={$order.orderid}">{$order.order_prefix}{$order.orderid}</a>{else}{$order.order_prefix}{$order.orderid}{/if}<br /><b>{$lng.lbl_order_status}:</b> {$lng.lbl_please_see_below}<br />
<b>{$lng.lbl_payment_method}:</b><br />{$order.payment_method}<br /><b>{$lng.lbl_delivery}s:</b><br />{if $order.shipping_groups ne '' && $products|@count ne 0}{foreach from=$order.shipping_groups item=v}{if $v.shipping}{$v.shipping|trademark:''}<br />{/if}{/foreach}{/if}
{if $giftcerts}
    {foreach from=$giftcerts item="gc"}
        {if $gc.send_via eq "E"}{$lng.lbl_email}{else}{$lng.lbl_gc_postal_mail}{/if}<br />
    {/foreach}
{/if}
			</td>
			<td valign="bottom" align="right">
<b>{$config.Company.operating_company_name}</b><br />
{$config.Company.location_address},<br />{$config.Company.location_city}{if $config.Company.location_country_has_states}, {$config.Company.location_state_name}{/if}<br />
{$config.Company.location_zipcode}, {$config.Company.location_country_name}<br />
{if $config.Company.company_phone}{$lng.lbl_phone_1_title}: {$config.Company.company_phone}<br />{/if}
{if $config.Company.company_phone_2}{$lng.lbl_phone_2_title}: {$config.Company.company_phone_2}<br />{/if}
{if $config.Company.company_fax}{$lng.lbl_fax}: {$config.Company.company_fax}<br />{/if}
{if $config.Company.orders_department}{$lng.lbl_email}: {$config.Company.orders_department}<br />{/if}
{if $order.applied_taxes}
<br />
{foreach from=$order.applied_taxes key=tax_name item=tax}
{$tax.regnumber}<br />
{/foreach}
{/if}
			</td>
		</tr>
		</table>
        {/if}
		</td>
	</tr>
	<tr>
		<td colspan='2'> {* <img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" style="height: 2px; max-height: 2px;" />*} <hr style="width:100%;margin: 0px; border: 0 none; border-bottom: 1px solid #999999;" /></td>
	</tr>
	</table>
&nbsp;<br /> 
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
		<td width="45%">
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
{if $_userinfo.default_fields.company}
	<tr>
		<td><b>{$lng.lbl_company}:</b></td>
		<td>{$order.company}</td>
	</tr>
{/if}
{if $_userinfo.default_fields.tax_number}
	<tr>
		<td><b>{$lng.lbl_tax_number}:</b></td>
		<td>{$order.tax_number}</td>
	</tr>
{/if}
{if $_userinfo.default_fields.firstname}
	<tr>
		<td nowrap="nowrap" width="40%"><b>{$lng.lbl_first_name}:</b></td>
		<td>{$order.firstname}</td>
	</tr>
{/if}
{if $_userinfo.default_fields.lastname}
	<tr>
		<td nowrap="nowrap"><b>{$lng.lbl_last_name}:</b></td>
		<td>{$order.lastname}</td>
	</tr>
{/if}
{if $_userinfo.default_fields.phone}
	<tr>
		<td><b>{$lng.lbl_phone}:</b></td>
		<td>
			{$order.phone}
			{if $order.phone_ext ne ""}
				<b>{$lng.lbl_phone_ext}</b> {$order.phone_ext}
			{/if}
		</td>
	</tr>
{/if}
{if $_userinfo.default_fields.fax}
	<tr>
		<td><b>{$lng.lbl_fax}:</b></td>
		<td>{$order.fax}</td>
	</tr>
{/if}
{if $_userinfo.default_fields.email}
	<tr>
		<td><b>{$lng.lbl_email}:</b></td>
		<td>{$order.email}</td>
	</tr>
{/if}
{if $_userinfo.default_fields.url}
	<tr>
		<td><b>{$lng.lbl_url}:</b></td>
		<td>{$order.url}</td>
	</tr>
{/if}
{foreach from=$_userinfo.additional_fields item=v}
{if $v.section eq 'C' || $v.section eq 'P'}
	<tr>
		<td><b>{$v.title}:</b></td>
        <td>{$v.value}</td>
	</tr>
	{/if}
{/foreach}
	</table>
		</td>
		<td width="10%">&nbsp;</td>
		<td width="45%" style="vertical-align: top;">
		{if $order.po_details}
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
		<tr>
			<td width="40%"><b>{$lng.lbl_po_number}:</b></td>
			<td>{$order.po_details.po_number}</td>
		</tr>
		<tr>
			<td><b>{$lng.lbl_company_name}:</b></td>
			<td>{$order.po_details.company_name}</td>
		</tr>
{*
		<tr>
			<td><b>Position:</b> </td>
			<td>{$order.po_details.position}</td>
		</tr>
*}
		</table>
		{/if}
		</td>
	</tr>
	</table>
{if $order.po_details}
&nbsp;<br />
        <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tr>
                <td width="45%" height="25"><b>Purchase manager</b><hr style="width:100%;margin: 0px; border: 0 none; border-bottom: 1px solid #999999;" /></td>
                <td width="10%">&nbsp;</td>
                <td width="45%" height="25"><b>Accounts payable</b><hr style="width:100%;margin: 0px; border: 0 none; border-bottom: 1px solid #999999;" /></td>
        </tr>

        <tr>
                <td>
                <table cellspacing="0" cellpadding="0" width="100%" border="0">
                <tr>
                        <td width="40%"><b>Full Name:</b></td>
                        <td>{$order.po_details.name_of_purchaser}</td>
                </tr>
                <tr>
                        <td><b>Phone:</b></td>
                        <td>{$order.po_details.purchase_manager_phone} {if $order.po_details.purchase_manager_phone_ext ne ""}<b>ext</b> {$order.po_details.purchase_manager_phone_ext}{/if}</td>
                </tr>
                <tr>
                        <td><b>Fax:</b></td>
                        <td>{$order.po_details.po_fax}</td>
                </tr>
                <tr>
                        <td><b>Email:</b></td>
                        <td>{$order.po_details.purchase_manager_email}</td>
                </tr>
		</table>
		</td>
		<td width="10%">&nbsp;</td>
                <td>
                <table cellspacing="0" cellpadding="0" width="100%" border="0">
                <tr>
                        <td width="40%"><b>Full Name:</b></td>
                        <td>{$order.po_details.accounts_payable_full_name}</td>
                </tr>
                <tr>
                        <td><b>Phone:</b></td>
                        <td>{$order.po_details.accounts_payable_phone} {if $order.po_details.accounts_payable_phone_ext ne ""}<b>ext</b> {$order.po_details.accounts_payable_phone_ext}{/if}</td>
                </tr>
                <tr>
                        <td><b>Fax:</b></td>
                        <td>{$order.po_details.accounts_payable_fax}</td>
                </tr>
                <tr>
                        <td><b>Email:</b></td>
                        <td>{$order.po_details.accounts_payable_email}</td>
                </tr>
                </table>
                </td>
	</tr>
	</table>
{/if}

&nbsp;<br />
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
		<td width="45%" height="25"><b>{$lng.lbl_shipping_address}</b><hr style="width:100%;margin: 0px; border: 0 none; border-bottom: 1px solid #999999;" /></td>
		<td width="10%">&nbsp;</td>
		<td width="45%" height="25"><b>{$lng.lbl_billing_address}</b><hr style="width:100%;margin: 0px; border: 0 none; border-bottom: 1px solid #999999;" /></td>
	</tr>

	<tr>
		<td>
                <table cellspacing="0" cellpadding="0" width="100%" border="0">
{if $_userinfo.default_fields.s_firstname}
                <tr>
                        <td width="40%"><b>{$lng.lbl_first_name}:</b> </td>
                        <td>{$order.s_firstname}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.s_lastname}
                <tr>
                        <td><b>{$lng.lbl_last_name}:</b> </td>
                        <td>{$order.s_lastname}</td>
                </tr>
{/if}
{foreach from=$_userinfo.additional_fields item=v}
{if $v.section eq 'S'}
                <tr>
                        <td><b>{$v.title}:</b></td>
                <td>{$v.value}</td>
                </tr>
        {/if}
{/foreach}
{if $_userinfo.default_fields.s_address}
                <tr>
                        <td><b>{$lng.lbl_address}:</b> </td>
                        <td>{$order.s_address}<br />{$order.s_address_2}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.s_city}
                <tr>
                        <td><b>{$lng.lbl_city}:</b> </td>
                        <td>{$order.s_city}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.s_county && $config.General.use_counties eq 'Y'}
                <tr>
                        <td><b>{$lng.lbl_county}:</b> </td>
                        <td>{$order.s_countyname}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.s_state}
                <tr>
                        <td><b>{$lng.lbl_state}:</b> </td>
                        <td>{$order.s_statename}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.s_country}
                <tr>
                        <td><b>{$lng.lbl_country}:</b> </td>
                        <td>{$order.s_countryname}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.s_zipcode}
                <tr>
                        <td><b>{$lng.lbl_zip_code}:</b> </td>
                        <td>{$order.s_zipcode}</td>
                </tr>
{/if}
{*foreach from=$_userinfo.additional_fields item=v}
{if $v.section eq 'S'}
                <tr>
                        <td><b>{$v.title}:</b></td>
                <td>{$v.value}</td>
                </tr>
        {/if}
{/foreach*}
                </table>
		</td>
		<td>&nbsp;</td>
		<td>
                <table cellspacing="0" cellpadding="0" width="100%" border="0">
{if $_userinfo.default_fields.b_firstname}
                <tr>
                        <td width="40%"><b>{$lng.lbl_first_name}:</b> </td>
                        <td>{$order.b_firstname}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.b_lastname}
                <tr>
                        <td><b>{$lng.lbl_last_name}:</b> </td>
                        <td>{$order.b_lastname}</td>
                </tr>
{/if}
{foreach from=$_userinfo.additional_fields item=v}
{if $v.section eq 'B'}
                <tr>
                        <td><b>{$v.title}:</b></td>
                <td>{$v.value}</td>
                </tr>
        {/if}
{/foreach}
{if $_userinfo.default_fields.b_address}
                <tr>
                        <td><b>{$lng.lbl_address}:</b> </td>
                        <td>{$order.b_address}<br />{$order.b_address_2}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.b_city}
                <tr>
                        <td><b>{$lng.lbl_city}:</b> </td>
                        <td>{$order.b_city}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.b_county && $config.General.use_counties eq 'Y'}
                <tr>
                        <td><b>{$lng.lbl_county}:</b> </td>
                        <td>{$order.b_countyname}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.b_state}
                <tr>
                        <td><b>{$lng.lbl_state}:</b> </td>
                        <td>{$order.b_statename}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.b_country}
                <tr>
                        <td><b>{$lng.lbl_country}:</b> </td>
                        <td>{$order.b_countryname}</td>
                </tr>
{/if}
{if $_userinfo.default_fields.b_zipcode}
                <tr>
                        <td><b>{$lng.lbl_zip_code}:</b> </td>
                        <td>{$order.b_zipcode}</td>
                </tr>
{/if}
{*foreach from=$_userinfo.additional_fields item=v}
{if $v.section eq 'B'}
                <tr>
                        <td><b>{$v.title}:</b></td>
                <td>{$v.value}</td>
                </tr>
        {/if}
{/foreach*}
                </table>
        </td>
	</tr>

{assign var="is_header" value=""}
{foreach from=$_userinfo.additional_fields item=v}
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
	<td height="2"> {* <img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" style="height: 2px; max-height: 2px;" /> *} <hr style="width:100%;margin: 0px; border: 0 none; border-bottom: 1px solid #999999;" /></td>
	<td colspan="2" width="55%"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" style="height: 2px; max-height: 2px;" /></td>
</tr>
<tr>
	<td colspan="3"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" style="height: 2px; max-height: 2px;" /></td>
</tr>
<tr>
	<td><table cellspacing="0" cellpadding="0" width="100%" border="0">
{assign var="is_header" value="E"}
{/if}
<tr valign="top">
	<td><b>{$v.title}</b></td>
   	<td>{$v.value}</td>
</tr>
{/if}
{/foreach}
{if $is_header eq 'E'}
</table></td>
<td colspan="2" width="55%">&nbsp;</td>
</tr>
{/if}


{if $config.Email.show_cc_info eq "Y" and $show_order_details eq "Y"}

	<tr>
	<td colspan="3">&nbsp;</td>
	</tr>

	<tr>
	<td width="45%" height="25"><b>{$lng.lbl_order_payment_details}</b></td>
	<td colspan="2" width="55%">&nbsp;</td>
	</tr>
	
	<tr>
	<td height="2"> {* <img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" style="height: 2px; max-height: 2px;" /> *} <hr style="width:100%;margin: 0px; border: 0 none; border-bottom: 1px solid #999999;" /></td>
	<td colspan="2"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" style="height: 2px; max-height: 2px;" /></td>
	</tr>
	<tr>
	<td colspan="3"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" style="height: 2px; max-height: 2px;" /></td>
	</tr>

	<tr>
	<td colspan="3">{$order.details|replace:"\n":"<br />"}</td>
	</tr>

{/if}

{if $order.netbanx_reference}
<tr>
	<td colspan="3">NetBanx Reference: {$order.netbanx_reference}</td>
</tr>
{/if}

{if $order.non_us_confirmation eq "Y"}
<tr>
        <td colspan="3">
<br />
{*
<input type="checkbox" checked="checked" name="non_us_confirmation" value="Y" {if $this_is_printable_version ne "Y"} disabled="disabled"{/if}>{$lng.lb_confirmation_of_responsibility_invoice_label}
*}

{if $this_is_printable_version ne "Y"}
<input type="checkbox" checked="checked" name="non_us_confirmation" value="Y" disabled="disabled" />
{else}
[X]
{/if} {$lng.lb_confirmation_of_responsibility_invoice_label}


	</td>
</tr>
{/if}


	</table>
	&nbsp;<br /><br />&nbsp;

{if $this_is_printable_version eq "Y"}
        </td>
</tr>
</table>
{/if}


{if $ref_notify eq 'Y'}
    {include file="mail/html/refund_data.tpl"}
{else}
    {include file="mail/html/order_data.tpl"}
{/if}


{if $this_is_printable_version ne "Y"}
	</td>
</tr>
</table>
{/if}


{if $this_is_printable_version ne "Y"}
	{if $order.customer_notes ne "" && $ref_notify ne 'Y'}
	<table cellspacing="0" cellpadding="0" width="{if $is_nomail eq 'Y'}100%{else}600{/if}" bgcolor="#ffffff">
	<tr>
		<td {* colspan="3" *}>
		<br />
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
			<tr>
			<td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">{$lng.lbl_customer_notes}</font></td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="10" width="100%" style="border: 1px solid;">
			<tr>
			<td style="height:50px;">{$order.customer_notes}</td>
			</tr>
		</table>
		</td>
	</tr>
	</table>
	{/if}

	{if $retrieve ne "Y" && $order.empty_shipping_groups eq 'Y'}
	<table cellspacing="0" cellpadding="0" width="{if $is_nomail eq 'Y'}100%{else}600{/if}" bgcolor="#ffffff">
	<tr>
	<td align="center"><br /><br /><font style="FONT-SIZE:12px">{$lng.txt_thank_you_for_purchase}</font></td>
	</tr>
	</table>
	{/if}
{else}
	<div align="center">
		{if $order.customer_notes ne "" && $ref_notify ne 'Y'}
			<div><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">{$lng.lbl_customer_notes}</font></div>
			<div style="height:50px; border: 1px solid;">{$order.customer_notes}</div>
		{/if}
		{$lng.txt_thank_you_for_purchase}
	</div>
{/if}
