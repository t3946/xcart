{* $Id: refund_notification_subj.tpl,v 1.0 2011/11/15 15:43:43 kate Exp $ *}
{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
{config_load file="$skin_config"}{$config.Company.operating_company_name}: {$lng.eml_ref_notification_subj|substitute:"orderid":$orderid:"mcode":$manufacturer_code}
