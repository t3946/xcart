{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
{config_load file="$skin_config"}{ $config.Company.company_name }: {if $order.status eq 'R'}{$lng.eml_order_cust_refunded_subj|substitute:"orderid":$orderid}{else}{$lng.eml_order_cust_processed_subj|substitute:"orderid":$orderid}{/if}
