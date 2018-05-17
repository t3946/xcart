<table cellspacing="0" cellpadding="0" width="100%" border="0">
    <tr>
        <td align="center">
            <span style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">Products ordered</span>
        </td>
    </tr>
</table>
{set $colspan = 6}

<table cellspacing="0" cellpadding="3" width="100%" border="1">
    <tr>
        <th width="60" bgcolor="#cccccc" align="center">SKU</th>
        <th
            {if $this_is_printable_version == "Y"}
                {if $order.has_backordered_status}
                    width="170"
                {else}
                    width="240"
                {/if}
            {else}
                width="*"
            {/if} align="center" bgcolor="#cccccc">Product
        </th>
        <th width="50" nowrap="nowrap" bgcolor="#cccccc" align="center">Item price</th>
        <th width="50" nowrap="nowrap" bgcolor="#cccccc" align="center">Qty ord</th>
        <th nowrap="nowrap" width="50" bgcolor="#cccccc" align="center">Extended
            <br/>
        </th>
    </tr>

    {foreach $order->groups as $k => $group}
        {set $distributor = $group->manufacturer}
        {set $shipping = $group->shippingModel}
        {set $order_details = $group->detail_models}
        <tr>
            <td colspan="{$colspan}">
                <b>
                    {$distributor->manufacturer} Items
                    (delivery from {$distributor->m_city}, {$distributor->m_state}, {$distributor->m_country} by {$shipping->getFrontendName()}, US$ {$group->shipping_gross}):
                </b>
            </td>
        </tr>
        {foreach $order_details as $order_detail}
            {set $product = $order_detail->product_model}
            <tr>
                <td align="center">{$product->productcode}</td>
                <td>
                    <span style="font-size: 11px"><a href="{$product->getAbsoluteUrl(true)}">{$product->getFrontendName()}</a></span>
                        {if $order_detail->product_options}
                            <table>
                                <tr>
                                    <td valign="top"><b>Options:</b></td>
                                    <td>
                                        {*{include file="modules/Product_Options/display_options.tpl" options=$product.product_options options_txt=$product.product_options_txt force_product_options_txt=$product.force_product_options_txt}*}
                                    </td>
                                </tr>
                            </table>
                        {/if}
                </td>
                <td align="center" nowrap="nowrap">US$ {$order_detail->price|number_format:2}</td>
                <td align="center">{$order_detail->amount}</td>
                <td align="right" nowrap="nowrap">US$ {$order_detail->price * $order_detail->amount|number_format:2}</td>
            </tr>
        {/foreach}
        <tr>
            <td colspan="{$colspan}">
                <b>Payment status:</b>&nbsp;{$group->cb_status_model}
                <br/>
                {if ($group->cb_status != 'A' &&  $group->cb_status != 'D')}
                    <b>Shipping status:</b> {$group->dc_status_model}
                {/if}
            </td>
        </tr>
    {/foreach}
</table>

<table cellspacing="0" cellpadding="0" width="100%" border="0">
    <tr>
        <td align="right" width="100%" height="20"><b>Total:</b>&nbsp;</td>
        <td align="right" nowrap="nowrap">US$ {$order->subtotal|number_format:2}</td>
    </tr>
    {if $order->discount > 0}
        <tr>
            <td align="right" width="100%" height="20"><b>Discount:</b>&nbsp;</td>
            <td align="right" nowrap="nowrap">US$ {$order->discount|number_format:2}</td>
        </tr>
    {/if}

    {if $order->coupon && $order->coupon_type != "free_ship"}
        <tr>
            <td align="right" width="100%" height="20"><b>Coupon Savings:</b>&nbsp;</td>
            <td align="right" nowrap="nowrap">US$ {$order->coupon_discount|number_format:2}</td>
        </tr>
    {/if}

    {if $order->coupon_discount > 0}
        <tr>
            <td align="right" width="100%" height="20"><b>Discounted Total:</b>&nbsp;</td>
            <td align="right"
                nowrap="nowrap">US$ {$order->total - $this->coupon_discount|number_format:2}</td>
        </tr>
    {/if}

    {if $config.disable_shipping != 'Y'}
        <tr>
            <td align="right" width="100%" height="20"><b>Total Shipping Cost:</b>&nbsp;</td>
            <td align="right" nowrap="nowrap">US$ {$order->shipping_cost}</td>
        </tr>
    {/if}

    {if $order->coupon && $order->coupon_type == "free_ship"}
        <tr>
            <td align="right" width="100%" height="20"><b>Coupon Savings:</b>&nbsp;</td>
            <td align="right" nowrap="nowrap">US$ {$order->coupon_discount}</td>
        </tr>
    {/if}

    <tr>
        <td colspan="2">
            <hr style="width:100%;margin: 0; border: 0 none; border-bottom: 1px solid #999999;">
        </td>
    </tr>

    <tr>
        <td align="right" width="100%" bgcolor="#cccccc" height="25"><b>Grand total:</b>&nbsp;
        </td>
        <td align="right" bgcolor="#cccccc" height="25" nowrap="nowrap">
            <b>US$ {$order->total}</b>
        </td>
    </tr>

</table>