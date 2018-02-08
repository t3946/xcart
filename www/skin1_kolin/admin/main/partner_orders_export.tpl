{* $Id: partner_orders_export.tpl,v 1.2 2004/06/29 06:47:30 svowl Exp $ *}
{section name=ri loop=$report}
{$report[ri].order_prefix}{$report[ri].orderid}{$delimiter}{$report[ri].login}{$delimiter}{$report[ri].firstname}{$delimiter}{$report[ri].lastname}{$delimiter}{$report[ri].b_address}{$delimiter}{$report[ri].b_address_2}{$delimiter}{$report[ri].b_city}{$delimiter}{$report[ri].b_state}{$delimiter}{$report[ri].b_country}{$delimiter}{$report[ri].subtotal}{$delimiter}{$report[ri].commissions}{$delimiter}{$report[ri].paid}
{/section}
