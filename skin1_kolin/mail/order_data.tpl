{* $Id: order_data.tpl,v 1.42.2.1 2006/08/28 06:16:44 max Exp $ *}
{$lng.lbl_products_ordered}:
-----------------

{foreach from=$order.shipping_groups item=v key=k name="shgrform"}

{if $products}{$v.group_name} {$lng.lbl_items} ({$lng.lbl_delivery_from_by|substitute:"CITY":$v.manufacturer_data.m_city:"STATE":$v.manufacturer_data.m_state:"COUNTRY":$v.manufacturer_data.m_country} {$v.shipping|trademark:''}, {include file="currency.tpl" value=$v.shipping_cost.gross|default:"0"}):{/if}

{section name=prod_num loop=$products}
{$lng.lbl_sku|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$products[prod_num].productcode}
{$lng.lbl_product|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$products[prod_num].product}
{$lng.lbl_qty_ord|capitalize|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$products[prod_num].oOrderDetail->getAmount()}
{if $order.has_backordered_status}
{$lng.lbl_qty_ship|capitalize|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{if $v.dc_status eq 'B' || $v.dc_status eq 'G' || $v.dc_status eq 'S'}{$products[prod_num].ship}
{else}-
{/if}
{$lng.lbl_qty_back|capitalize|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{if $v.dc_status eq 'B' || $v.dc_status eq 'G' ||$v.dc_status eq 'S'}{$products[prod_num].back}
{else}-
{/if}
{/if}
{if $products[prod_num].product_options ne ""}
{$lng.lbl_selected_options}:
{include file="modules/Product_Options/display_options.tpl" options=$products[prod_num].product_options options_txt=$products[prod_num].product_options_txt is_plain="Y"}
{/if}
{$lng.lbl_item_price|capitalize|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$products[prod_num].oOrderDetail->getPrice()}
{if $order.extra.tax_info.display_cart_products_tax_rates eq "Y" and $_userinfo.tax_exempt ne "Y"}

{foreach from=$products[prod_num].extra_data.taxes key=tax_name item=tax}
{if $tax.tax_value gt 0}{$tax.tax_display_name} {if $tax.rate_type eq "%"}{$tax.rate_value|formatprice:false:false:3}%{else}{include file="currency.tpl" value=$tax.rate_value}{/if}{/if}

{/foreach}
{/if}


{/section}

{if $products}
{$lng.lbl_payment_status|cat:":"|string_format:$max_space}{include file="main/order_status.tpl" status=$v.cb_status mode="static" status_type="CB"}
{$lng.lbl_shipping_status|cat:":"|string_format:$max_space}{include file="main/order_status.tpl" status=$v.dc_status mode="static" status_type="DC"}
{/if}

{if $show_shipping_groups neq 'N' && $v.tracking}
{foreach from=$v.tracking item=tr}
{assign var="current_carrier_id" value=$tr.carrier_id}
{assign var="full_shipper" value="by `$tracking_links_carrier[$current_carrier_id].carrier`"}
{if $tracking_links[$tr.linkid].shipping ne ""}
{assign var="full_shipper" value="`$full_shipper` `$tracking_links[$tr.linkid].shipping`"}
{/if}
{if $tr.ship_date ne ""}
{assign var="full_shipper" value="on `$tr.ship_date` `$full_shipper`"}
{/if}
{if $tr.tracknum ne ""}
{$lng.eml_order_shipped|substitute:"shipper":$full_shipper|substitute:"distributor":$v.group_name}
{$lng.lbl_tracking_number_is} {$tr.tracknum}
{* {$tracking_links[$tr.linkid].link|substitute:"tracknum":$tr.tracknum} *}
{$tracking_links_carrier[$current_carrier_id].link|substitute:"tracknum":$tr.tracknum}
{else}
{$lng.eml_order_shipped_nolink|substitute:"shipper":$full_shipper}
{$tracking_links_carrier[$current_carrier_id].link}
{/if}
{/foreach}

{/if}
{/foreach}
{section name=giftcert loop=$giftcerts}
{$lng.lbl_items} ({$lng.lbl_delivery_by} {if $giftcerts[giftcert].send_via eq "E"}{$lng.lbl_email}{else}{$lng.lbl_gc_postal_mail}{/if}, {include file="currency.tpl" value=$v.shipping_cost.gross|default:"0"}):

{$lng.lbl_gift_certificate|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$giftcerts[giftcert].gcid}
{$lng.lbl_amount|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$giftcerts[giftcert].amount}

{$lng.lbl_recipient|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$giftcerts[giftcert].recipient}
{if $giftcerts[giftcert].send_via eq "P"}
{$lng.lbl_gc_send_via_postal_mail}
{$lng.lbl_mail_address|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$giftcerts[giftcert].recipient_firstname} {$giftcerts[giftcert].recipient_lastname}
		{$giftcerts[giftcert].recipient_address}, {$giftcerts[giftcert].recipient_city},
		{if $giftcerts[giftcert].recipient_countyname ne ''}{$giftcerts[giftcert].recipient_countyname} {/if}{$giftcerts[giftcert].recipient_state} {$giftcerts[giftcert].recipient_country}, {$giftcerts[giftcert].recipient_zipcode}
{$lng.lbl_phone|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$giftcerts[giftcert].recipient_phone} {if $gc.recipient_phone_ext ne ""}{$lng.lbl_phone_ext|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}:{$gc.recipient_phone_ext}{/if}
{else}
{$lng.lbl_recipient_email|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$giftcerts[giftcert].recipient_email}
{/if}

{$lng.lbl_payment_status|cat:":"|string_format:$max_space}{include file="main/order_status.tpl" status=$order.cb_status mode="static" status_type="CB"}
{$lng.lbl_shipping_status|cat:":"|string_format:$max_space}{include file="main/order_status.tpl" status=$order.dc_status mode="static" status_type="DC"}

{/section}

{*Retail trust table*}
{if $oOrder}
{assign var=aRetailTrustOrderDetails value=$oOrder->getOrderDetailsWithRetailTrust()}
{if $aRetailTrustOrderDetails}
{$lng.lbl_retailtrust_ordered}:
-------
{foreach from=$aRetailTrustOrderDetails item=oRetailTrustOrderDetail}
{assign var=oRetailTrustProduct value=$oRetailTrustOrderDetail->getOrderDetailProduct()}
{$lng.lbl_sku|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$oRetailTrustProduct->getSKURetailTrust()}
{$lng.lbl_product|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$oRetailTrustProduct->getProductName()}
{$lng.lbl_item_price|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$oRetailTrustOrderDetail->calculateRetailTrustPricePerProduct()}
{$lng.lbl_qty_ord|capitalize|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$oRetailTrustOrderDetail->getAmount()}
{$lng.lbl_extended|capitalize|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$oRetailTrustOrderDetail->getRetailTrustGross()}
{/foreach}


{/if}
{/if}

{$lng.lbl_extended}:
-------
{$lng.lbl_payment_method|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.payment_method}
{$lng.lbl_delivery|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}
{foreach from=$order.shipping_groups item=v key=k}
{$v.shipping|trademark:""}
{/foreach}
{$lng.lbl_subtotal|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$order.display_subtotal}

{if $order.discount gt 0}{$lng.lbl_discount|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$order.discount}{/if}

{if $order.coupon and $order.coupon_type ne "free_ship"}
{$lng.lbl_coupon_saving|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$order.coupon_discount} ({$order.coupon})
{/if}
{if $order.discounted_subtotal ne $order.subtotal}
{$lng.lbl_discounted_total|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$order.display_discounted_subtotal}

{/if}
{$lng.lbl_shipping_cost|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$order.display_shipping_cost}

{if $order.coupon and $order.coupon_type eq "free_ship"}
{$lng.lbl_coupon_saving|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$order.coupon_discount} ({$order.coupon})

{/if}
{if $order.applied_taxes and $order.extra.tax_info.display_taxed_order_totals ne "Y"}
{foreach key=tax_name item=tax from=$order.applied_taxes}
{if $tax.rate_type eq "%"}{assign var="rate_value" value=$tax.rate_value|formatprice:false:false:3}{assign var="tax_display_name" value="`$tax.tax_display_name` `$rate_value`%"}{else}{assign var="tax_display_name" value=$tax.tax_display_name}{/if}{$tax_display_name|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$tax.tax_cost}

{/foreach}
{/if}
{if $order.payment_surcharge ne 0}
{if $order.payment_surcharge gt 0}{$lng.lbl_payment_method_surcharge|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{else}{$lng.lbl_payment_method_discount|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{/if}{include file="currency.tpl" value=$order.payment_surcharge}
{/if}
{if $order.giftcert_discount gt 0}
{$lng.lbl_giftcert_discount|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$order.giftcert_discount}
{/if}

{if $oOrder && $oOrder->getOrderRetailTrustGross() > 0}
{$lng.lbl_retailtrust_ordered_total|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$oOrder->getOrderRetailTrustGross()}

{/if}
{if ($oOrder)}
{$lng.lbl_total|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$oOrder->getOrderTotalGross()}
{/if}

{if $_userinfo.tax_exempt ne "Y"}
{if $order.applied_taxes and $order.extra.tax_info.display_taxed_order_totals eq "Y"}
{$lng.lbl_including}:
{foreach key=tax_name item=tax from=$order.applied_taxes}
{if $tax.rate_type eq "%"}{assign var="rate_value" value=$tax.rate_value|formatprice:false:false:3}{assign var="tax_display_name" value="`$tax.tax_display_name` `$rate_value`%"}{else}{assign var="tax_display_name" value=$tax.tax_display_name}{/if}{$tax_display_name|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$tax.tax_cost}

{/foreach}
{/if}
{else}
{$lng.txt_tax_exemption_applied|strip_tags}
{/if}

{if $order.applied_giftcerts}
{$lng.lbl_applied_giftcerts}:
{section name=gc loop=$order.applied_giftcerts}
    {$order.applied_giftcerts[gc].giftcert_id|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="currency.tpl" value=$order.applied_giftcerts[gc].giftcert_cost}

{/section}
{/if}

{if $order.extra.special_bonuses ne ""}
{include file="mail/special_offers_order_bonuses.tpl" bonuses=$order.extra.special_bonuses}
{/if}

