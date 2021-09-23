{* $Id: rma_decline.tpl,v 1.7 2006/03/31 05:51:43 svowl Exp $ *}
{config_load file="$skin_config"}
{include file="mail/html/mail_header.tpl"}

<p />{$lng.eml_dear|substitute:"customer":"`$userinfo.firstname`"},<br />
<br />
{$lng.eml_rma_return_declined|substitute:"returnid":$return.returnid}<br />
<br />
{$lng.eml_return_request}:<br />
<table border="0">
<tr>
	<td>{$lng.lbl_returnid}</td>
	<td>{$lng.lbl_product}</td>
	<td>{$lng.lbl_product_options}</td>
	<td>{$lng.lbl_quantity}</td>
</tr>
<tr>
	<td>{$return.returnid}</td>
	<td>{$return.product.product}</td>
	<td>{include file="modules/Product_Options/display_options.tpl" options=$return.product.product_options}</td>
	<td>{$return.amount}</td>
</tr>
</table>
<br />
{include file="mail/html/signature.tpl"}
