{* $Id: order_notification.tpl,v 1.5 2006/03/31 05:51:43 svowl Exp $ *}
{config_load file="$skin_config"}

{if $mnf_operator_notify eq 'Y'}
    <p />{$message_body}
{else}
    <p />{$lng.eml_dear|substitute:"customer":"`$order.firstname` `$order.lastname`"},
{/if}

{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
<p />{$order_notification.email_body|substitute:"orderid":$orderid}

<hr size="1" noshade="noshade" />

{include file="mail/html/order_invoice.tpl"}

{include file="mail/html/signature.tpl"}
