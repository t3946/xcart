{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
{config_load file="$skin_config"}{$orderid}: online reply to availability check request
