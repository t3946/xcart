{* $Id: shipping_information.tpl,v 1.3 2004/05/28 12:21:02 max Exp $ *}
{config_load file="$skin_config"}
{include file="mail/html/mail_header.tpl"}

<P>{$lng.lbl_dear} {$customer.firstname} {$customer.lastname},

<P>
This is confirmation that products you ordered have been shipped to you.
<BR>
Shipping usually takes 1-2 days within US and 1-2 weeks abroads.
<BR>
Your FedEx tracking number is <NUMBER>.
<BR>
Please save it for future.

<P>
<B>{$lng.lbl_products_ordered}:</B>

<P>
<TABLE border="0" cellpadding="3" cellspacing="1" bgcolor="#AAAAAA">
<TR>
<TD bgcolor="#DDDDDD">{$lng.lbl_amount}</TD>
<TD bgcolor="#DDDDDD">{$lng.lbl_price}</TD>
<TD bgcolor="#DDDDDD">{$lng.lbl_product_name}</TD>
</TR>
{section name=prod_num loop=$products}
<TR>
<TD bgcolor="#FFFFFF">{$products[prod_num].amount}</TD>
<TD bgcolor="#FFFFFF">{$products[prod_num].price}</TD>
<TD bgcolor="#FFFFFF">{$products[prod_num].product}</TD>
</TR>
{/section}
</TABLE>

{include file="mail/html/signature.tpl"}

