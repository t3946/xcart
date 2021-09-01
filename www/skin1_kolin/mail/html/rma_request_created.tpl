{* $Id: rma_request_created.tpl,v 1.7 2006/03/31 05:51:43 svowl Exp $ *}
{include file="mail/html/mail_header.tpl"}
<p />{$lng.eml_rma_request_created|substitute:"creator":"`$userinfo.firstname` `$userinfo.lastname`"}<br />
<br />
{$lng.eml_return_requests}:<br />
<table border="0">
<tr>
	<td>{$lng.lbl_returnid}</td>
	<td>{$lng.lbl_product}</td>
	<td>{$lng.lbl_product_options}</td>
	<td>{$lng.lbl_quantity}</td>
</tr>
{foreach from=$returns item=v}
<tr>
	<td>{$v.returnid}</td>
	<td>{$v.product.product}</td>
	<td>{include file="modules/Product_Options/display_options.tpl" options=$v.product.product_options}</td>
	<td>{$v.amount}</td>
</tr>
{/foreach}
</table>
<br />
{include file="mail/html/signature.tpl"}
