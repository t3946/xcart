{* $Id: order_notification.tpl,v 1.5 2006/03/31 05:51:43 svowl Exp $ *}
{config_load file="$skin_config"}

{if $mnf_operator_notify eq 'Y'}
    <p />{$message_body|replace:"\n":"<br />"}
{else}
    <p />{$lng.eml_dear|substitute:"customer":"`$order.firstname`"},
{/if}

{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
<p />{$order_notification.email_body|substitute:"orderid":$orderid}

{if $order.amazonorderid ne "" && $show_amazon_order eq "Y"}
<p />Amazon order: {$order.amazonorderid}
{/if}

{if $cidev_hide_invoice ne "Y"}
<hr size="1" noshade="noshade" />

{include file="mail/html/order_invoice.tpl"}

{include file="mail/html/signature.tpl"}
{/if}
