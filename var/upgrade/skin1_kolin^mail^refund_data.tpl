{* $Id: refund_data.tpl,v 1.0 2011/11/15 17:20:44 kate Exp $ *}
{$lng.txt_refund_issued_for_items}
-----------------

{foreach from=$order.refund_groups[$manufacturerid].products item=product}
{$lng.lbl_sku|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$product.productcode}
{$lng.lbl_product|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$product.product} ({if $product.fee eq '0'}{$lng.lbl_no_restocking_fee}{else}{$lng.lbl_x_percents_restocking_fee|substitute:"X":$product.fee}{/if})
{$lng.lbl_price|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$product.extra_data.display.price}

{$lng.lbl_qty_ord|capitalize|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}-{$product.ref_qty}
{if $products[prod_num].product_options ne ""}
{$lng.lbl_selected_options}:
{include file="modules/Product_Options/display_options.tpl" options=$product.product_options options_txt=$product.product_options_txt is_plain="Y"}
{/if}
{$lng.lbl_extended|capitalize|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{math assign="total" equation="amount*price" amount=$product.ref_qty price=$product.extra_data.display.price}({include file="currency.tpl" value=$total})

{if $order.extra.tax_info.display_cart_products_tax_rates eq "Y" and $_userinfo.tax_exempt ne "Y"}

{foreach from=$product.extra_data.taxes key=tax_name item=tax}
{if $tax.tax_value gt 0}{$tax.tax_display_name} {if $tax.rate_type eq "%"}{$tax.rate_value|formatprice:false:false:1}%{else}{include file="currency.tpl" value=$tax.rate_value}{/if}{/if}

{/foreach}
{/if}
{/foreach}

{if $order.refund_groups[$manufacturerid].shipping && $order.refund_groups[$manufacturerid].shipping_gross gt 0}
{$lng.lbl_adjustment_to} {$order.refund_groups[$manufacturerid].shipping}: ({include file="currency.tpl" value=$order.refund_groups[$manufacturerid].shipping_gross})
{/if}

{$lng.lbl_total_refund_to} {$order.shipping_groups[$manufacturerid].acc_payment_method}: ({include file="currency.tpl" value=$order.refund_groups[$manufacturerid].total_gross})

{if $_userinfo.tax_exempt ne "Y"}
{if $order.refund_groups[$manufacturerid].extra_data.taxes and $order.extra.tax_info.display_taxed_order_totals eq "Y"}
{assign var=taxes value=$order.refund_groups[$manufacturerid].extra_data.taxes}
{foreach key=tax_name item=tax from=$taxes}
{$lng.lbl_including_tax|substitute:"tax":$tax.tax_display_name}{if $tax.rate_type eq "%"} {$tax.rate_value|formatprice:false:false:1}%{/if}:{include file="currency.tpl" value=$tax.tax_cost}

{/foreach}
{/if}

{else}
{$lng.txt_tax_exemption_applied}
{/if}
