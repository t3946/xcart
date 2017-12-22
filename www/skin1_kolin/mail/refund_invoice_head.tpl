{* $Id: refund_invoice_head.tpl,v 1.37.2.1 2011/11/15 17:04:39 kate Exp $ *}

{assign var=cb_status value=$order.shipping_groups[$manufacturerid].cb_status}
{$lng.lbl_date|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.date|date_format:$config.Appearance.datetime_format}
{$lng.lbl_refund|truncate:$max_truncate:"...":true|cat:"#:"|string_format:$max_space}{$order.order_prefix}{$order.orderid}-{$manufacturer_code}
{$lng.lbl_refund_status|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$statuses.CB[$cb_status]}
{$lng.lbl_refunded_to|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.shipping_groups[$manufacturerid].acc_payment_method}
{$lng.lbl_original_order|truncate:$max_truncate:"...":true|cat:"#:"|string_format:$max_space}{$order.order_prefix}{$order.orderid}
