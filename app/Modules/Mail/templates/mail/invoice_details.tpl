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
                    {$distributor->manufacturer} {t 'Items'}
                    ({t 'delivery from'} {$distributor->m_city}, {$distributor->m_state}, {$distributor->m_country} {t 'by'} {$shipping->getFrontendName()}, {$site_currency->symbol_prefix}{$site_currency} {$site_currency->getCurrencyFormat($group->shipping_gross)}):
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
                <td align="center" nowrap="nowrap">{$site_currency->symbol_prefix}{$site_currency} {$site_currency->getCurrencyFormat($order_detail->price)}</td>
                <td align="center">{$order_detail->amount}</td>
                <td align="right" nowrap="nowrap">{$site_currency->symbol_prefix}{$site_currency} {$site_currency->getCurrencyFormat($order_detail->price * $order_detail->amount)}</td>
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
    {/foreach}
</table>

<table cellspacing="0" cellpadding="0" width="100%" border="0">
    <tr>
        <td align="right" width="100%" height="20"><b>{t 'Total'}:</b>&nbsp;</td>
        <td align="right" nowrap="nowrap">{$site_currency->symbol_prefix}{$site_currency} {$site_currency->getCurrencyFormat($order->subtotal)}</td>
    </tr>
    {if $order->discount > 0}
        <tr>
            <td align="right" width="100%" height="20"><b>{t 'Discount'}:</b>&nbsp;</td>
            <td align="right" nowrap="nowrap">{$site_currency->symbol_prefix}{$site_currency} {$site_currency->getCurrencyFormat($order->discount)}</td>
        </tr>
    {/if}

    {if $order->coupon}
        <tr>
            <td align="right" width="100%" height="20"><b>{t 'Coupon Savings'}:</b>&nbsp;</td>
            <td align="right" nowrap="nowrap">{$site_currency->symbol_prefix}{$site_currency} {$site_currency->getCurrencyFormat($order->coupon_discount)}</td>
        </tr>
    {/if}

    {if $order->coupon_discount > 0}
        <tr>
            <td align="right" width="100%" height="20"><b>{t 'Discounted Total'}:</b>&nbsp;</td>
            <td align="right"
                nowrap="nowrap">{$site_currency->symbol_prefix}{$site_currency} {$site_currency->getCurrencyFormat($order->total - $this->coupon_discount)}</td>
        </tr>
    {/if}

    {if $config.disable_shipping != 'Y'}
        <tr>
            <td align="right" width="100%" height="20"><b>{t 'Total Shipping Cost'}:</b></td>
            <td align="right" nowrap="nowrap">{$site_currency->symbol_prefix}{$site_currency} {$site_currency->getCurrencyFormat($order->shipping_cost)}</td>
        </tr>
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
            <b>{$site_currency->symbol_prefix}{$site_currency} {$site_currency->getCurrencyFormat($order->total)}</b>
        </td>
    </tr>

</table>