{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
{config_load file="$skin_config"}{ $config.Company.operating_company_name }: {$lng.eml_init_order_customer_subj|substitute:"orderid":$orderid}
