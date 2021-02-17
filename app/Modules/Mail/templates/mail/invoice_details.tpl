<table cellspacing="0" cellpadding="0" width="100%" border="0">
    <tr>
        <td align="center">
            <span style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">{t 'Products Ordered'}</span>
        </td>
    </tr>
</table>
{set $colspan = 5}

<table cellspacing="0" cellpadding="3" width="100%" border="1">
    <tr>
        <th width="60" bgcolor="#cccccc" align="center">{t 'SKU'}</th>
        <th
            {if $this_is_printable_version == "Y"}
                {if $order.has_backordered_status}
                    width="170"
                {else}
                    width="240"
                {/if}
            {else}
                width="*"
            {/if} align="center" bgcolor="#cccccc">{t 'Product'}
        </th>
        <th width="50" nowrap="nowrap" bgcolor="#cccccc" align="center">{t 'Item price'}</th>
        <th width="50" nowrap="nowrap" bgcolor="#cccccc" align="center">{t 'Qty ord'}</th>
        <th nowrap="nowrap" width="50" bgcolor="#cccccc" align="center">{t 'Extended'}
            <br/>
        </th>
    </tr>

    {foreach $order->groups as $k => $group}
        {set $distributor = $group->manufacturer}
        {set $shipping = $group->shippingModel}
        {set $order_details = $group->detail_models}
        {if $shipping}
        <tr>
            <td colspan="{$colspan}">
                <b>
                    {t 'The items below are shipped from'} {$distributor->m_city},
                    {if $site_config.show_full_state_country === 'Y'}{$distributor->state_model}{else}{$distributor->m_state}{/if},
                    {if $site_config.show_full_state_country === 'Y'}{$distributor->country_model}{else}{$distributor->m_country}{/if} {t 'by'} {$shipping->getFrontendName()} {t 'shipping'}, {$group->shipping_gross|site_currency}
                </b>
            </td>
        </tr>
        {/if}
        {foreach $order_details as $order_detail}
            {set $product = $order_detail->product_model}
            <tr>
                <td align="center">{$product->productcode}</td>
                <td>
                    <span style="font-size: 11px"><a href="https:{$product->getAbsoluteUrl(true)}">{$product->getFrontendName()}</a></span>
                    {include "mail/_parts/_product_options.tpl"}
                </td>
                <td align="center" nowrap="nowrap">{$order_detail->price|site_currency}</td>
                <td align="center">{$order_detail->amount}</td>
                <td align="right" nowrap="nowrap">{($order_detail->price * $order_detail->amount)|site_currency}</td>
            </tr>
        {/foreach}
        <tr>
            <td colspan="{$colspan}">
                <b>{t 'Payment status'}:</b>&nbsp;{$group->cb_status_model}
                <br/>
                {if ($group->cb_status != 'A' &&  $group->cb_status != 'D')}
                    <b>{t 'Shipping status'}:</b> {$group->dc_status_model}
                {/if}
            </td>
        </tr>
        {foreach $group->trackings as $tr}
            <tr>
                <td colspan="{$colspan}" style="padding: 10px;">
                    {$distributor} products have been shipped {if $tr->shipping_date}on {$tr->shipping_date|date_format:'%B %d, %Y'}{/if} by {$tr->carrier}.
                    <br/>
                    {set $link = $tr->getLink()}
                    {if $tr->tracknum}
                        The tracking number is {if $link}<a href="{$link}">{/if}{$tr->tracknum}{if $link}</a>{/if}
                    {else}
                        {$link}
                    {/if}
                    <br/>
                </td>
            </tr>
        {/foreach}
    {/foreach}
</table>

<table cellspacing="0" cellpadding="0" width="100%" border="0">
    <tr>
        <td align="right" width="100%" height="20"><b>{t 'Total'}:</b>&nbsp;</td>
        <td align="right" nowrap="nowrap">{$order->subtotal|site_currency}</td>
    </tr>
    {if $order->discount > 0}
        <tr>
            <td align="right" width="100%" height="20"><b>{t 'Discount'}:</b>&nbsp;</td>
            <td align="right" nowrap="nowrap">{$order->discount|site_currency}</td>
        </tr>
    {/if}

    {if $order->coupon}
        <tr>
            <td align="right" width="100%" height="20"><b>{t 'Coupon Savings'}:</b>&nbsp;</td>
            <td align="right" nowrap="nowrap">{$order->coupon_discount|site_currency}</td>
        </tr>
    {/if}

    {if $order->coupon_discount > 0}
        <tr>
            <td align="right" width="100%" height="20"><b>{t 'Discounted Total'}:</b>&nbsp;</td>
            <td align="right"
                nowrap="nowrap">{($order->total - $this->coupon_discount)|site_currency}</td>
        </tr>
    {/if}

    {if $config.disable_shipping != 'Y'}
        <tr>
            <td align="right" width="100%" height="20"><b>{t 'Total Shipping Cost'}:</b></td>
            <td align="right" nowrap="nowrap">{$order->shipping_cost|site_currency}</td>
        </tr>
    {/if}

    {set $order_taxes = $order->getTaxes()}
    {if $order_taxes}
        {foreach $order_taxes as $tax_name => $tax_rate}
            <tr>
                <td align="right" width="100%" height="20"><b>{t 'Total'} {$tax_name}:</b></td>
                <td align="right" nowrap="nowrap">{$tax_rate|site_currency}</td>
            </tr>
        {/foreach}
    {/if}

    <tr>
        <td colspan="2">
            <hr style="width:600px; margin: 0; border: 0 none; border-bottom: 1px solid #999999;">
        </td>
    </tr>

    <tr>
        <td align="right" width="100%" bgcolor="#cccccc" height="25"><b>{t 'Grand Total'}:</b>&nbsp;
        </td>
        <td align="right" bgcolor="#cccccc" height="25" nowrap="nowrap">
            <b>{$order->total|site_currency}</b>
        </td>
    </tr>

</table>