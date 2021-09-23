{* $Id: order_cust_not_paid.tpl,v 1.10 2011/01/24 15:00:43 kate Exp $ *}
{config_load file="$skin_config"}
{assign var=max_truncate value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}

{$lng.eml_dear|substitute:"customer":"`$order.firstname` `$order.lastname`"},

{$lng.eml_not_paid_order_customer}

{include file="mail/order_invoice.tpl"}

{include file="mail/signature.tpl"}
