{* $Id: refund_notification.tpl,v 1.0 2011/11/15 15:43:43 kate Exp $ *}
{config_load file="$skin_config"}

<p />{$lng.eml_dear|substitute:"customer":"`$order.firstname` `$order.lastname`"},

{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
<p />{$order_notification.email_body|substitute:"orderid":$orderid}

<hr size="1" noshade="noshade" />

{include file="mail/html/order_invoice.tpl" ref_notify="Y"}

{include file="mail/html/signature.tpl"}
