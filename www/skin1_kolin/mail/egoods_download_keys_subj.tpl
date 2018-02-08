{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
{config_load file="$skin_config"}{ $config.Company.company_name }: {$lng.eml_egoods_download_keys_subj|substitute:"orderid":$orderid}
