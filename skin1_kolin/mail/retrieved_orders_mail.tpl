{* $Id: retrieved_orders.tpl,v 1.0 2010/10/08 14:02:43 kate Exp $ *}
{config_load file="$skin_config"}
{if $orders}
	{foreach from=$orders item=order name="orders"}
		{if !$order.shipping_groups}
			{include file="mail/order_invoice.tpl" order=$order.order userinfo=$order.userinfo products=$order.products giftcerts=$order.giftcerts}
			{include file="mail/signature.tpl"}
		{else}
			{include file="mail/order_customer_shipped.tpl" order=$order.order userinfo=$order.userinfo products=$order.products giftcerts=$order.giftcerts shipping_groups=$order.shipping_groups retrieve=Y}
		{/if}
		{if !$smarty.foreach.orders.last}
-	-
		{/if}
	{/foreach}
{else}
	{$lng.txt_no_retrieved_orders|replace:"email":"`$email`"}
{/if}

{include file="mail/signature.tpl"}
