{* $Id: order_cust_not_paid.tpl,v 1.0 2011/01/24 15:25:43 kate Exp $ *}
{config_load file="$skin_config"}
<p />{$lng.eml_dear|substitute:"customer":"`$order.firstname` `$order.lastname`"},

<p />{$lng.eml_not_paid_order_customer}

{include file="mail/html/order_invoice.tpl"}

{include file="mail/html/signature.tpl"}
