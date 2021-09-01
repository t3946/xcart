{*
$Id: order_report_csv.tpl, v 1.0.0 2010/04/14 11:55:42 random Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
"{$lng.lbl_manufacturers}:"{$delimiter}"{foreach from=$manufacturers item=mnf name=mnf_loop}{if !$smarty.foreach.mnf_loop.first}, {/if}{$mnf}{/foreach}"
"{$lng.lbl_report_period}:"{$delimiter}"{if $data.date_period ne ''} {$lng.lbl_from} {$data.start_date|date_format:"%d-%b-%Y"} {$lng.lbl_to} {$data.end_date|date_format:"%d-%b-%Y"}{else}{$lng.lbl_all_dates}{/if}"

{$delimiter}{$lng.lbl_c2b_payment|upper}{$delimiter}{$lng.lbl_customer}{$delimiter}{$lng.lbl_net}{$delimiter}{$lng.lbl_processor}{$delimiter}{$lng.lbl_net}{$delimiter}{$lng.lbl_cost_to_us}{$delimiter}{$lng.lbl_shipping}{$delimiter}{$lng.lbl_ref_to_cust}{$delimiter}{$lng.lbl_ref_to_us}{$delimiter}{$lng.lbl_profit}{$delimiter}{$lng.lbl_profit}
#{$delimiter}{$lng.lbl_d2c_shipping|upper}{$delimiter}{$delimiter}{$lng.lbl_gst_in}{$delimiter}{$lng.lbl_payment}{$delimiter}{$lng.lbl_gst_in}{$delimiter}{$lng.lbl_gst_out}{$delimiter}{$lng.lbl_gst_out}{$delimiter}{$delimiter}{$lng.lbl_gst_out}{$delimiter}{$lng.lbl_gst_in}{$delimiter}{$lng.lbl_margin}
{$lng.lbl_distr}{$delimiter}{$lng.lbl_b2d_payment}{$delimiter}{$delimiter}{$lng.lbl_pst_in}{$delimiter}{$lng.lbl_date}{$delimiter}{$lng.lbl_pst_in}{$delimiter}{$lng.lbl_pst_out}{$delimiter}{$lng.lbl_pst_out}{$delimiter}{$lng.lbl_pst_out}{$delimiter}{$lng.lbl_pst_in}{$delimiter}{$lng.lbl_pst_in}
{$delimiter}{$delimiter}{$delimiter}{$lng.lbl_gross}{$delimiter}{$lng.lbl_time}{$delimiter}{$lng.lbl_gross}{$delimiter}{$lng.lbl_cost_to_us}{$delimiter}{$lng.lbl_shipping}{$delimiter}{$lng.lbl_ref_to_cust}{$delimiter}{$lng.lbl_ref_to_us}{$delimiter}{$lng.lbl_profit}

{$delimiter}{$delimiter}{$delimiter}"{include file="currency2.tpl" value=$data.total.net}"{$delimiter}{$delimiter}{section loop=5 name="acc"}"{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].net}"{$delimiter}{/section}"{include file="currency2.tpl" value=$data.total_accounting[5].net show_minus_brackets='Y'}"{$delimiter}{$data.total_margin|price_format}%
{$delimiter}{$delimiter}{$lng.lbl_report_word}{$delimiter}"{include file="currency2.tpl" value=$data.total.gst}"{$delimiter}{$delimiter}{section loop=5 name="acc"}"{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].gst hide_zero='Y'}"{$delimiter}{/section}"{include file="currency2.tpl" value=$data.total_accounting[5].gst show_minus_brackets='Y'}"
{$delimiter}{$delimiter}{$lng.lbl_totals_word}:{$delimiter}"{include file="currency2.tpl" value=$data.total.pst}"{$delimiter}{$delimiter}{section loop=5 name="acc"}"{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].pst hide_zero='Y'}"{$delimiter}{/section}"{include file="currency2.tpl" value=$data.total_accounting[5].pst show_minus_brackets='Y'}"
{$delimiter}{$delimiter}{$delimiter}"{include file="currency2.tpl" value=$data.total.gross}"{$delimiter}{$delimiter}{section loop=5 name="acc"}"{include file="currency2.tpl" value=$data.total_accounting[$smarty.section.acc.index].gross}"{$delimiter}{/section}"{include file="currency2.tpl" value=$data.total_accounting[5].gross show_minus_brackets='Y'}"

{foreach from=$orders item=order}
{foreach from=$order.shipping_groups item=v key=m_id name=groups}
{if ($v.cb_status eq 'P' || $v.dc_status eq 'C' || $v.dc_status eq 'S') && $v.acc_paymentid ne 0}{assign var="show_accounting" value=true}{else}{assign var="show_accounting" value=false}{/if}
{$delimiter}{include file="main/order_status.tpl" status=$v.cb_status mode="static" status_type="CB"}{$delimiter}{$order.firstname}{$delimiter}{include file="currency2.tpl" value=$v.total.net}{$delimiter}{foreach from=$all_processors item=ps key=pid}{if $pid eq $v.acc_paymentid}{$ps.payment_method}{/if}{/foreach}{$delimiter}{if $show_accounting}{section loop=5 name="acc"}{include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].net}{$delimiter}{/section}{include file="currency2.tpl" value=$v.accounting[5].net show_minus_brackets='Y'}{$delimiter}{$v.profit_margin|price_format}%{/if}

{$order.orderid}{$delimiter}{include file="main/order_status.tpl" status=$v.dc_status mode="static" status_type="DC"}{$delimiter}{$order.lastname}{$delimiter}{include file="currency2.tpl" value=$v.total.gst hide_zero='Y'}{$delimiter}{$order.payment_method}{$delimiter}{if $show_accounting}{include file="currency2.tpl" value=$v.accounting[0].gst hide_zero='Y'}{$delimiter}{section start=1 loop=5 name="acc"}{include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].gst hide_zero='Y'}{$delimiter}{/section}{include file="currency2.tpl" value=$v.accounting[5].gst hide_zero='Y' show_minus_brackets='Y'}{/if}

{$v.code}{$delimiter}{include file="main/order_status.tpl" status=$v.bd_status mode="static" status_type="BD"}{$delimiter}{$order.s_countryname}{$delimiter}{include file="currency2.tpl" value=$v.total.pst hide_zero='Y'}{$delimiter}{$order.date|date_format:"%d-%b-%G"}{$delimiter}{if $show_accounting}{include file="currency2.tpl" value=$v.accounting[0].pst hide_zero='Y'}{$delimiter}{section start=1 loop=5 name="acc"}{include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].pst hide_zero='Y'}{$delimiter}{/section}{include file="currency2.tpl" value=$v.accounting[5].pst hide_zero='Y' show_minus_brackets='Y'}{/if}

{$delimiter}{$delimiter}{$delimiter}{include file="currency2.tpl" value=$v.total.gross}{$delimiter}{$order.date|date_format:"%T"}{$delimiter}{if $show_accounting}{include file="currency2.tpl" value=$v.accounting[0].gross}{$delimiter}{section start=1 loop=5 name="acc"}{include file="currency2.tpl" value=$v.accounting[$smarty.section.acc.index].gross hide_zero='Y'}{$delimiter}{/section}{include file="currency2.tpl" value=$v.accounting[5].gross show_minus_brackets='Y'}{/if}


{/foreach}
{/foreach}
