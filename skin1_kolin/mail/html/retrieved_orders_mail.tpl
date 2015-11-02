{* $Id: retrieved_orders.tpl,v 1.0 2010/10/08 14:02:43 kate Exp $ *}
{config_load file="$skin_config"}

{if $orders}
	{foreach from=$orders item=order name="forders"}

		{if !$order.shipping_groups}
			<p />{include file="mail/html/order_invoice.tpl" order=$order.order userinfo=$order.userinfo products=$order.products giftcerts=$order.giftcerts retrieve=Y}
			{include file="mail/html/signature.tpl"}
		{else}
			<p />{include file="mail/html/order_customer_shipped.tpl" order=$order.order userinfo=$order.userinfo products=$order.products giftcerts=$order.giftcerts shipping_groups=$order.shipping_groups retrieve=Y}
		{/if}
		{if !$smarty.foreach.forders.last}
			<hr />
		{/if}
	{/foreach}
{else}
	{$lng.txt_no_retrieved_orders|replace:"email":"`$email`"}
{/if}

{include file="mail/html/signature.tpl"}
