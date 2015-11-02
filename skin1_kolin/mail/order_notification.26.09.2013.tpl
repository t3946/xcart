{* $Id: order_notification.tpl,v 1.22 2006/03/31 05:51:43 svowl Exp $ *}
{config_load file="$skin_config"}
{assign var=max_truncate value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}

{if $mnf_operator_notify eq 'Y'}{$message_body}{/if}

{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
{$order_notification.email_body|substitute:"orderid":$orderid}

{include file="mail/order_invoice.tpl"}

{include file="mail/signature.tpl"}
