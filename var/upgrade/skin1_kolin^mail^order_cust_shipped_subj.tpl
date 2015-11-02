{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
{config_load file="$skin_config"}{ $config.Company.company_name }: {$lng.eml_order_cust_shipped_subj|substitute:"orderid":$orderid}
