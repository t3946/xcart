{* $Id: shipping_information.tpl,v 1.9 2004/05/31 10:52:01 max Exp $ *}
{include file="mail/mail_header.tpl"}
{assign var=max_truncate value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}

{$lng.lbl_dear} {$customer.title} {$customer.firstname} {$customer.lastname},

This is confirmation that products you ordered have been shipped to you.
Shipping usually takes 1-2 days within US and 1-2 weeks abroads.
Your FedEx tracking number is <NUMBER>.
Please save it for future.

{$lng.lbl_products_ordered}:
{$lng.lbl_amount} {$lng.lbl_price}    {$lng.lbl_product_name}
------------------------------------------------------------------------------
{section name=prod_num loop=$products}
{$products[prod_num].amount}  {$products[prod_num].price}   {$products[prod_num].product}
{/section}
------------------------------------------------------------------------------

{include file="mail/signature.tpl"}
