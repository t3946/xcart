{* order_invoice_mnf.tpl, random *}
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
		<table cellspacing="0" cellpadding="2" width="100%">
		<tr>
			<td width="30">&nbsp;</td>
			<td valign="top">
<font style="FONT-SIZE: 28px"><b style="text-transform: uppercase;">{$lng.lbl_invoice}</b></font>
<br /><br />
<b>{$lng.lbl_date}:</b> {$order.date|date_format:$config.Appearance.datetime_format}<br /><b>{$lng.lbl_order_id}:</b> {$order.order_prefix}{$order.orderid}<br />{if $show_shipping eq 'Y'}<b>{$lng.lbl_delivery}:</b><br />{foreach from=$order.shipping_groups item=v key=k}{if $k eq $manufacturerid}{$v.frontend_name|default:$v.shipping|trademark:''}{/if}{/foreach}{else}<br />{/if}<br /><br /><br /><br /><br />
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
		</td>
	</tr>
	</table>
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
		<td><img src="{$ImagesDir}/spacer.gif" alt="" /></td>
	</tr>
	<tr>
		<td bgcolor="#000000"> {* <img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /> *} <hr style="width:100%;margin: 0px;" /></td>
	</tr>
	<tr>
		<td><img src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
	</tr>
	</table>
	<br />
	<table cellspacing="0" cellpadding="0" width="45%" border="0">
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
		<td nowrap="nowrap"><b>{$lng.lbl_first_name}:</b></td>
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
		<td>{$order.phone}</td>
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
	<br />
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
		<td height="25"><b>{$lng.lbl_shipping_address}</b></td>
	</tr>
	<tr>
		<td bgcolor="#000000" height="2"> {* <img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /> *} <hr style="width:100%;margin: 0px;" /></td>
	</tr>
	<tr>
		<td><img src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
	</tr>
	<tr>
		<td>
		<table cellspacing="0" cellpadding="0" width="45%" border="0">
{if $_userinfo.default_fields.s_firstname}
		<tr>
			<td><b>{$lng.lbl_first_name}:</b> </td>
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
		</table>
        </td>
	</tr>

{assign var="is_header" value=""}
{foreach from=$_userinfo.additional_fields item=v}
{if $v.section eq 'A'}
{if $is_header eq ''}
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td height="25"><b>{$lng.lbl_additional_information}</b></td>
</tr>
<tr>
	<td bgcolor="#000000" height="2"> {* <img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /> *} <hr style="width:100%;margin: 0px;" /></td>
	<td width="55%"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
	<td><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
	<td>
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
{assign var="is_header" value="E"}
{/if}
<tr valign="top">
	<td><b>{$v.title}</b></td>
   	<td>{$v.value}</td>
</tr>
{/if}
{/foreach}
{if $is_header eq 'E'}
	</table>
	</td>
</tr>
{/if}
</table>
<br />
<br />

<table cellspacing="0" cellpadding="0" width="100%" border="0">

<tr>
<td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">{$lng.lbl_products_ordered}</font></td>
</tr>

</table>

<table cellspacing="0" cellpadding="3" width="100%" border="1">

<tr>
<th width="60" bgcolor="#cccccc">{$lng.lbl_sku}</th>
<th bgcolor="#cccccc">{$lng.lbl_product}</th>
<th width="60" bgcolor="#cccccc">{$lng.lbl_qty}</th>
</tr>
{foreach from=$order.shipping_groups item=v key=k}
{if $k eq $manufacturerid}
{if $show_shipping eq 'Y'}
<tr>
<td colspan="3">
<b>{$v.group_name} Items (delivery by {$v.frontend_name|default:$v.shipping|trademark:''}):</b>
</td>
</tr>
{/if}
{foreach from=$v.products item=product}

<tr>
<td align="center" style="font-size: 11px; width: 25%;">
{if $email_is_sent_to_operator eq "Y"}
<a href="{$product.links.provider}" style="color: blue;">
{/if}
{$product.productcode}
{if $email_is_sent_to_operator eq "Y"}
</a>
{/if}
</td>
<td><font style="font-size: 11px">
{if $email_is_sent_to_operator eq "Y"}
<a href="{$product.links.customer}" style="color: blue;">
{/if}
{$product.product}
{if $email_is_sent_to_operator eq "Y"}
</a>
{/if}
</font>
{if $product.product_options ne '' && $active_modules.Product_Options}
<table>

<tr>
<td valign="top"><b>{$lng.lbl_options}:</b></td> 
<td>{include file="modules/Product_Options/display_options.tpl" options=$product.product_options options_txt=$product.product_options_txt force_product_options_txt=$product.force_product_options_txt}</td>
</tr>

</table>
{/if}
{if $active_modules.Egoods and $product.download_key and ($order.cb_status eq "P" or $order.dc_status eq "C")}
<br />
<a href="{$catalogs.customer}/download.php?id={$product.download_key}" class="SmallNote" target="_blank">{$lng.lbl_download}</a>
{/if}
</td>
<td align="center" style="font-size: 11px">{$product.amount}</td>
</tr>
{/foreach}
{/if}
{/foreach}
</table>
	</td>
</tr>

{if $show_customer_notes eq "Y" and $order.customer_notes ne ""}

<tr>
	<td colspan="3">
	<br />
	<br />
	<table cellspacing="0" cellpadding="0" width="100%" border="0">

	<tr>
		<td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">{$lng.lbl_customer_notes}</font></td>
	</tr>

	</table>
	<table cellspacing="0" cellpadding="10" width="100%" border="1">
	<tr>
		<td style="height:50px;">{$order.customer_notes}</td>
	</tr>
	</table>
	</td>
</tr>

{/if}

</table>

