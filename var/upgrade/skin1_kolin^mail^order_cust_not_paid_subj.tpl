{* $Id: order_cust_not_paid_subj.tpl,v 1.39 2011/01/24 15:18:04 kate Exp $ *}
{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
{config_load file="$skin_config"}{$lng.lbl_re}: { $config.Company.company_name }: {$lng.eml_init_order_customer_subj|substitute:"orderid":$orderid}
