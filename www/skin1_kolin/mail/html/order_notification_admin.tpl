{* $Id: order_notification_admin.tpl,v 1.5 2006/03/31 05:51:43 svowl Exp $ *}
{config_load file="$skin_config"}

{assign var=where value="A"}

{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}

{include file="mail/html/order_invoice.tpl" to_admin="Y"}

<p />

{include file="mail/html/signature.tpl"}
