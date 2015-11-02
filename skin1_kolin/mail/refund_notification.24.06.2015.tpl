{* $Id: refund_notification.tpl,v 1.0 2011/11/15 15:43:43 kate Exp $ *}
{config_load file="$skin_config"}
{assign var=max_truncate value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}

{$lng.eml_dear|substitute:"customer":"`$order.firstname` `$order.lastname`"},

{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
{$order_notification.email_body|substitute:"orderid":$orderid}

{include file="mail/order_invoice.tpl" ref_notify="Y"}

{include file="mail/signature.tpl"}
