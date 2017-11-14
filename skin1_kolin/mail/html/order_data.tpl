{* $Id: order_data.tpl,v 1.29.2.5 2006/08/28 06:16:44 max Exp $ *}
<table cellspacing="0" cellpadding="0" width="100%" border="0">

<tr>
<td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">{$lng.lbl_products_ordered}</font></td>
</tr>

</table>

{if $order.has_backordered_status}
    {assign var=colspan value="8"}
{else}
    {assign var=colspan value="6"}
{/if}

<table cellspacing="0" cellpadding="3" width="100%" border="1">

<tr>
<th width="60" bgcolor="#cccccc" align="center">{$lng.lbl_sku}</th>
<th {if $this_is_printable_version eq "Y"}{if $order.has_backordered_status}width="170"{else}width="240"{/if}{else}width="*"{/if} align="center" bgcolor="#cccccc">{$lng.lbl_product}</th>
{if $order.extra.tax_info.display_cart_products_tax_rates eq "Y" and $_userinfo.tax_exempt ne "Y"}
<th nowrap="nowrap" align="center" bgcolor="#cccccc">{if $order.extra.tax_info.product_tax_name ne ""}{$order.extra.tax_info.product_tax_name}{else}{$lng.lbl_tax}{/if}</th>
{/if}
<th width="50" nowrap="nowrap" bgcolor="#cccccc" align="center">{$lng.lbl_item_price|capitalize}</th>
<th width="50" nowrap="nowrap" bgcolor="#cccccc" align="center">{$lng.lbl_qty_ord|capitalize}</th>
{if $order.has_backordered_status}
    <th width="50" bgcolor="#cccccc" nowrap="nowrap" align="center">{$lng.lbl_qty_ship|capitalize}</th>
    <th width="50" bgcolor="#cccccc" nowrap="nowrap" align="center">{$lng.lbl_qty_back|capitalize}</th>
{/if}
<th nowrap="nowrap" width="50" bgcolor="#cccccc" align="center">{$lng.lbl_extended}<br /> {* <img height="1" src="{$ImagesDir}/spacer.gif" width="50" border="0" alt="" style="height: 2px;max-height: 2px;" /> *} </th>
</tr>

{foreach from=$order.shipping_groups item=v key=k name="shgrform"}
{if $v.products}
<tr>
<td colspan="{$colspan}">
    {assign var="oManufacturer" value=$v.oOrderGroup->getManufacturerEntity()}
<b>{$v.group_name} {$lng.lbl_items} ({$lng.lbl_delivery_from_by|substitute:"CITY":$oManufacturer->getField('m_city'):"STATE":$oManufacturer->getField('m_state'):"COUNTRY":$oManufacturer->getField('m_country')} {$v.frontend_name|default:$v.shipping|trademark:''}, {include file="currency.tpl" value=$v.shipping_cost.gross|default:"0"}):</b>
</td>
</tr>
{/if}
{foreach from=$v.products item=product}
<tr>
<td align="center">{if $type eq 'A' || $type eq 'P'}<a href="{if $provider_notification eq 'Y'}{$product.links.provider}{else}{$product.links.admin}{/if}">{$product.productcode}</a>{else}{$product.productcode}{/if}</td>
<td><font style="FONT-SIZE: 11px"><a href="{$product.links.customer}">{$product.oProduct->getTitle()}</a></font>
{if $product.product_options ne '' && $active_modules.Product_Options}
<table>

<tr>
<td valign="top"><b>{$lng.lbl_options}:</b></td> 
<td>{include file="modules/Product_Options/display_options.tpl" options=$product.product_options options_txt=$product.product_options_txt force_product_options_txt=$product.force_product_options_txt}</td>
</tr>

</table>
{/if}
{if $active_modules.Egoods and $product.download_key and ($order.cb_status eq "P" or $order.dc_status eq "C")}
<br />
<a href="{$catalogs.customer}/download.php?id={$product.download_key}" class="SmallNote" target="_blank">{$lng.lbl_download}</a>
{/if}
</td>
{if $order.extra.tax_info.display_cart_products_tax_rates eq "Y" and $_userinfo.tax_exempt ne "Y"}
<td align="center">
{foreach from=$product.extra_data.taxes key=tax_name item=tax}
{if $tax.tax_value gt 0}
{if $order.extra.tax_info.product_tax_name eq ""}{$tax.tax_display_name} {/if}
{if $tax.rate_type eq "%"}{$tax.rate_value|formatprice:false:false:1}%{else}{include file="currency.tpl" value=$tax.rate_value}{/if}<br />
{/if}
{/foreach}
</td>
{/if}
<td align="center" nowrap="nowrap">{include file="currency.tpl" value=$product.oOrderDetail->getPrice()}</td>
<td align="center">{$product.oOrderDetail->getAmount()}</td>
{if $order.has_backordered_status}
    <td align="center">
        {if $v.dc_status eq 'B' || $v.dc_status eq 'G' || $v.dc_status eq 'S'}
            {$product.ship}
        {else}
            -
        {/if}
    </td>
    <td align="center">
        {if $v.dc_status eq 'B' || $v.dc_status eq 'G' ||$v.dc_status eq 'S' }
            {$product.back}
        {else}
            -
        {/if}
    </td>
{/if}
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$product.oOrderDetail->getTotalProductPrice()}&nbsp;&nbsp;</td>
</tr>
{/foreach}
{if $v.products}
<tr>
    <td colspan="{$colspan}">
        <b>{$lng.lbl_payment_status|cat:":"}</b>&nbsp;{include file="main/order_status.tpl" status=$v.oOrderGroup->getOrderGroupStatusCB()|default:$order.cb_status mode="static" status_type="CB"}<br />
        {if ($v.oOrderGroup->getOrderGroupStatusCB() != 'A' &&  $v.oOrderGroup->getOrderGroupStatusCB() != 'D')}
        <b>{$lng.lbl_shipping_status|cat:":"}</b>&nbsp;{include file="main/order_status.tpl" status=$v.oOrderGroup->getOrderGroupStatusDC()|default:$order.dc_status mode="static" status_type="DC"}
        {/if}
    </td>
</tr>
{/if}
{if $show_shipping_groups neq 'N'}
    {if $v.tracking}
        {foreach from=$v.tracking item=tr}
            <tr>
                <td colspan="{$colspan}" style="padding: 10px;">
		    {assign var="current_carrier_id" value=$tr.carrier_id}
                    {assign var="full_shipper" value="by `$tracking_links_carrier[$current_carrier_id].carrier`"}
		    {if $tracking_links[$tr.linkid].shipping ne ""}
                    	{assign var="full_shipper" value="`$full_shipper` `$tracking_links[$tr.linkid].shipping`"}
		    {/if}
		    {if $tr.ship_date ne ""}
			{assign var="full_shipper" value="on `$tr.ship_date` `$full_shipper`"}
		    {/if}

                    {if $tr.tracknum ne ""}

                        {$lng.eml_order_shipped|substitute:"shipper":$full_shipper|substitute:"distributor":$v.group_name}<br />
                        {$lng.lbl_tracking_number_is} 
{if $tracking_links_carrier[$current_carrier_id].link ne ""}<a href="{$tracking_links_carrier[$current_carrier_id].link|substitute:"tracknum":$tr.tracknum}">{/if}{$tr.tracknum}{if $tracking_links_carrier[$current_carrier_id].link ne ""}</a>{/if}
<br />
{*
                        <a href="{$tracking_links[$tr.linkid].link|substitute:"tracknum":$tr.tracknum}">{$tracking_links[$tr.linkid].link|substitute:"tracknum":$tr.tracknum}</a>
*}
                    {else}
                        {$lng.eml_order_shipped_nolink|substitute:"shipper":$full_shipper|substitute:"distributor":$v.group_name}<br />
 
			{if $tracking_links_carrier[$current_carrier_id].link ne ""}
{*	                       <a href="{$tracking_links[$tr.linkid].link}">link</a> *}
				{$tracking_links_carrier[$current_carrier_id].link}
			{/if}
                    {/if}
                </td>
            </tr>
        {/foreach}
    {/if}
    {if !$smarty.foreach.shgrform.last}
        <tr>
            <td colspan="6" style="border: none;">&nbsp;</td>
        </tr>
    {/if}
{/if}
{/foreach}

{if $giftcerts ne ''}
{foreach from=$giftcerts item=gc}
<tr>
    <td colspan="6">
        <b>{$lng.lbl_items} ({$lng.lbl_delivery_by} {if $gc.send_via eq "E"}{$lng.lbl_email}{else}{$lng.lbl_gc_postal_mail}{/if}, {include file="currency.tpl" value=$v.shipping_cost.gross|default:"0"}):</b>
    </td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td nowrap="nowrap">
{$lng.lbl_gift_certificate}: {$gc.gcid}<br />
<div style="padding-left: 10px; white-space: nowrap;">
{if $gc.send_via eq "P"}
{$lng.lbl_gc_send_via_postal_mail}<br />
{$lng.lbl_mail_address}: {$gc.recipient_firstname} {$gc.recipient_lastname}<br />
{$gc.recipient_address}, {$gc.recipient_city},<br />
{if $gc.recipient_countyname ne ''}{$gc.recipient_countyname} {/if}{$gc.recipient_state} {$gc.recipient_country}, {$gc.recipient_zipcode}<br />
{$lng.lbl_phone}: {$gc.recipient_phone} {if $gc.recipient_phone_ext ne ""}{$lng.lbl_phone_ext}: {$gc.recipient_phone_ext}{/if}
{else}
{$lng.lbl_recipient_email}: {$gc.recipient_email}
{/if}
</div>
	</td>
{if $order.extra.tax_info.display_cart_products_tax_rates eq "Y" and $_userinfo.tax_exempt ne "Y"}
	<td align="center">&nbsp;-&nbsp;</td>
{/if}
	<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$gc.amount}&nbsp;&nbsp;</td>
	<td align="center">1</td>
	<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$gc.amount}&nbsp;&nbsp;</td>
</tr>
<tr>
    <td colspan="6">
        <b>{$lng.lbl_payment_status|cat:":"}</b>&nbsp;{include file="main/order_status.tpl" status=$order.cb_status mode="static" status_type="CB"}<br />
        <b>{$lng.lbl_shipping_status|cat:":"}</b>&nbsp;{include file="main/order_status.tpl" status=$order.dc_status mode="static" status_type="DC"}
    </td>
</tr>
{/foreach}
{/if}

</table>

{*Retail trust table*}
{if $oOrder}
    {assign var=aRetailTrustOrderDetails value=$oOrder->getOrderDetailsWithRetailTrust()}
    {if $aRetailTrustOrderDetails}

        <table cellspacing="0" cellpadding="0" width="100%" border="0">

            <tr>
                <td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">{$lng.lbl_retailtrust_ordered}</font></td>
            </tr>

        </table>
        <table cellspacing="0" cellpadding="3" width="100%" border="1">
            <tr>
                <th width="60" bgcolor="#cccccc" align="center">{$lng.lbl_sku}</th>
                <th width="*" align="center" bgcolor="#cccccc">{$lng.lbl_product}</th>
                <th width="50" nowrap="nowrap" bgcolor="#cccccc" align="center">{$lng.lbl_item_price|capitalize}</th>
                <th width="50" nowrap="nowrap" bgcolor="#cccccc" align="center">{$lng.lbl_qty_ord|capitalize}</th>
                <th nowrap="nowrap" width="50" bgcolor="#cccccc" align="center">{$lng.lbl_extended}</th>
            </tr>
            {foreach from=$aRetailTrustOrderDetails item=oRetailTrustOrderDetail}
                {assign var=oRetailTrustProduct value=$oRetailTrustOrderDetail->getOrderDetailProduct()}
                <tr>
                <td align="center">{$oRetailTrustProduct->getSKURetailTrust()}</td>
                <td><a href="{$oRetailTrustProduct->getURL()}" target="_blank" style="FONT-SIZE: 11px">{$oRetailTrustProduct->getProductName()}</a></td>
                <td align="center">{include file="currency.tpl" value=$oRetailTrustOrderDetail->calculateRetailTrustPricePerProduct()}</td>
                <td align="center">{$oRetailTrustOrderDetail->getAmount()}</td>
                <td align="center">{include file="currency.tpl" value=$oRetailTrustOrderDetail->getRetailTrustGross()}</td>
                </tr>
            {/foreach}
        </table>
    {/if}
{/if}

<table cellspacing="0" cellpadding="0" width="100%" border="0">

<tr>
<td align="right" width="100%" height="20"><b>{$lng.lbl_total}:</b>&nbsp;</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$order.display_subtotal}</td>
</tr>

{if $order.discount gt 0}
<tr>
<td align="right" width="100%" height="20"><b>{$lng.lbl_discount}:</b>&nbsp;</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$order.discount}</td>
</tr>
{/if}

{if $order.coupon and $order.coupon_type ne "free_ship"}
<tr>
<td align="right" width="100%" height="20"><b>{$lng.lbl_coupon_saving}:</b>&nbsp;</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$order.coupon_discount}</td>
</tr>
{/if}

{if $order.discounted_subtotal ne $order.subtotal}
<tr>
<td align="right" width="100%" height="20"><b>{$lng.lbl_discounted_total}:</b>&nbsp;</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$order.display_discounted_subtotal}</td>
</tr>
{/if}

{if $config.Shipping.disable_shipping ne 'Y'}
<tr>
<td align="right" width="100%" height="20"><b>{$lng.lbl_total_shipping_cost}:</b>&nbsp;</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$order.display_shipping_cost}</td>
</tr>
{/if}

{if $order.coupon and $order.coupon_type eq "free_ship"}
<tr>
<td align="right" width="100%" height="20"><b>{$lng.lbl_coupon_saving}:</b>&nbsp;</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$order.coupon_discount}</td>
</tr>
{/if}

{if $order.applied_taxes and $order.extra.tax_info.display_taxed_order_totals ne "Y"}
{foreach key=tax_name item=tax from=$order.applied_taxes}
<tr>
<td align="right" width="100%" height="20"><b>{$tax.tax_display_name}{if $tax.rate_type eq "%"} {$tax.rate_value|formatprice:false:false:3}%{/if}:</b>&nbsp;</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$tax.tax_cost}</td>
</tr>
{/foreach}
{/if}

{if $order.payment_surcharge ne 0}
<tr>
<td align="right" width="100%" height="20"><b>{if $order.payment_surcharge gt 0}{$lng.lbl_payment_method_surcharge}{else}{$lng.lbl_payment_method_discount}{/if}:</b>&nbsp;</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$order.payment_surcharge}</td>
</tr>
{/if}


{if $order.giftcert_discount gt 0}
<tr>
<td align="right" width="100%" height="20"><b>{$lng.lbl_giftcert_discount}:</b>&nbsp;</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$order.giftcert_discount}</td>
</tr>
{/if}


{if $order.additional_fee ne ''}
{foreach from=$order.additional_fee item=v_f key=k_f}
<tr>
<td align="right" width="100%" height="20"><b>{$v_f.additional_fee_name}:</b>&nbsp;</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$v_f.additional_fee_value}</td>
</tr>
{/foreach}
{/if}

{if $oOrder && $oOrder->getOrderRetailTrustGross() > 0}
    <tr>
        <td align="right" width="100%" height="20"><b>{$lng.lbl_retailtrust_ordered_total}:</b>&nbsp;</td>
        <td align="right" nowrap="nowrap">{include file="currency.tpl" value=$oOrder->getOrderRetailTrustGross()}</td>
    </tr>
{/if}

<tr>
<td colspan="2"> {* <img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" style="height: 2px;max-height: 2px;" /> *} <hr style="width:100%;margin: 0px; border: 0 none; border-bottom: 1px solid #999999;"></td>
</tr>

<tr>
<td align="right" width="100%" bgcolor="#cccccc" height="25"><b>{$lng.lbl_grand_total|capitalize}:</b>&nbsp;</td>
<td align="right" bgcolor="#cccccc" height="25" nowrap="nowrap">
{if $oOrder}
    <b>{include file="currency.tpl" value=$oOrder->getOrderTotalGross()}</b>
{/if}
</td>
</tr>

{if $_userinfo.tax_exempt ne "Y"}

{if $order.applied_taxes and $order.extra.tax_info.display_taxed_order_totals eq "Y"}
{foreach key=tax_name item=tax from=$order.applied_taxes}
<tr>
<td align="right" width="100%" height="20"><b>{$lng.lbl_including_tax|substitute:"tax":$tax.tax_display_name}{if $tax.rate_type eq "%"} {$tax.rate_value|formatprice:false:false:3}%{/if}:</b>&nbsp;</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$tax.tax_cost}</td>
</tr>
{/foreach}
{/if}

{else}

<tr>
<td align="right" colspan="2" width="100%" height="20">{$lng.txt_tax_exemption_applied}</td>
</tr>

{/if}

</table>

{if $order.applied_giftcerts}
<br />
<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
	<td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">{$lng.lbl_applied_giftcerts}</font></td>
</tr>
</table>

<table cellspacing="1" cellpadding="0" width="100%" border="0">

<tr>
<th width="60" bgcolor="#cccccc">{$lng.lbl_giftcert_ID}</th>
<th bgcolor="#cccccc">{$lng.lbl_giftcert_cost}</th>
</tr>

{foreach from=$order.applied_giftcerts item=gc}
<tr>
<td align="center">{$gc.giftcert_id}</td>
<td align="right" nowrap="nowrap">{include file="currency.tpl" value=$gc.giftcert_cost}</td>
</tr>
{/foreach}

</table>
{/if}

{if $order.extra.special_bonuses ne ""}
{include file="mail/html/special_offers_order_bonuses.tpl" bonuses=$order.extra.special_bonuses}
{/if}

