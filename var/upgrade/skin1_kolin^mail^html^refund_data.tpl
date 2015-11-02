{* $Id: refund_data.tpl,v 1.0 2011/11/15 17:20:44 kate Exp $ *}
<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
    <td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">{$lng.txt_refund_issued_for_items}</font></td>
</tr>
</table>

<table cellspacing="0" cellpadding="3" width="100%" border="1">
<tr>
    <th width="60" bgcolor="#cccccc">{$lng.lbl_sku}</th>
    <th bgcolor="#cccccc">{$lng.lbl_product}</th>
    {if $order.extra.tax_info.display_cart_products_tax_rates eq "Y" and $_userinfo.tax_exempt ne "Y"}
        <th nowrap="nowrap" bgcolor="#cccccc">
            {if $order.extra.tax_info.product_tax_name ne ""}
                {$order.extra.tax_info.product_tax_name}
            {else}
                {$lng.lbl_tax}
            {/if}
        </th>
    {/if}
    <th nowrap="nowrap" bgcolor="#cccccc" align="center">{$lng.lbl_item_price|capitalize}</th>
    <th bgcolor="#cccccc">{$lng.lbl_qty_ord|capitalize}</th>
    <th width="60" bgcolor="#cccccc">{$lng.lbl_extended}<br /></th>
</tr>

{foreach from=$order.refund_groups[$manufacturerid].products item=product}
<tr>
    <td align="center">{$product.productcode}</td>
    <td>
        <font style="FONT-SIZE: 11px">
            {$product.product}
            <b>({if $product.fee eq '0'}{$lng.lbl_no_restocking_fee}{else}{$lng.lbl_x_percents_restocking_fee|substitute:"X":$product.fee}{/if})</b>
        </font>
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
    
    <td align="center" nowrap="nowrap">{include file="currency.tpl" value=$product.extra_data.display.price}</td>
    <td align="center">-{$product.ref_qty}</td>
    <td align="center" nowrap="nowrap">
        {math assign="total" equation="amount*price" amount=$product.ref_qty price=$product.extra_data.display.price}
        ({include file="currency.tpl" value=$total})
    </td>
</tr>
{/foreach}

{if $order.refund_groups[$manufacturerid].shipping && $order.refund_groups[$manufacturerid].shipping_gross gt 0}
<tr>
    <td colspan="6" style="text-align: right; padding: 10px;">
        {$lng.lbl_adjustment_to}&nbsp;{$order.refund_groups[$manufacturerid].shipping}: ({include file="currency.tpl" value=$order.refund_groups[$manufacturerid].shipping_gross})
    </td>
</tr>
{/if}

</table>

<table cellspacing="0" cellpadding="0" width="100%" border="0">

<tr>
    <td colspan="2" align="right" bgcolor="#cccccc" height="25">
        <b>{$lng.lbl_total_refund_to}&nbsp;{$order.shipping_groups[$manufacturerid].acc_payment_method}:</b>&nbsp;
        <b>({include file="currency.tpl" value=$order.refund_groups[$manufacturerid].total_gross})</b>&nbsp;&nbsp;
    </td>
</tr>

{if $_userinfo.tax_exempt ne "Y"}

    {if $order.refund_groups[$manufacturerid].extra_data.taxes and $order.extra.tax_info.display_taxed_order_totals eq "Y"}
        {assign var=taxes value=$order.refund_groups[$manufacturerid].extra_data.taxes}
        {foreach key=tax_name item=tax from=$taxes}
        <tr>
            <td align="right" width="100%" height="20"><b>{$lng.lbl_including_tax|substitute:"tax":$tax.tax_display_name}{if $tax.rate_type eq "%"} {$tax.rate_value|formatprice:false:false:1}%{/if}:</b>&nbsp;</td>
            <td align="right">{include file="currency.tpl" value=$tax.tax_cost}&nbsp;&nbsp;&nbsp;</td>
        </tr>
        {/foreach}
    {/if}

{else}

<tr>
    <td align="right" colspan="2" width="100%" height="20">{$lng.txt_tax_exemption_applied}</td>
</tr>
{/if}

</table>
