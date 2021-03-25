<table class="SubHeader" cellspacing="0">
    <tr>
        <td class="Green2">Last orders</td>
    </tr>
    <tr>
        <td class="SubHeaderLine"><img src="/skin1_kolin/images/spacer.gif" class="Spc" alt=""><br></td>
    </tr>
</table>

<table cellpadding="3" cellspacing="0" width="100%">
    <tr class="TableHead">
        <td>Order #</td>
        <td>Order date</td>
        <td nowrap="nowrap">Customer name</td>
        <td nowrap="nowrap">Shipping</td>
        <td nowrap="nowrap">Payment status</td>
        <td nowrap="nowrap">Grand total</td>
    </tr>
    {foreach $last_orders as $order}
        <tr class="{cycle ['SectionBox','TableSubHead']}">
            <td class="borderr-gray"><a target="_blank" href="{$order->getAdminUrl()}">{$order->getOrderNumber()}</a></td>
            <td class="borderb-gray">{$order->date|date_format:'%b %e, %Y %H:%M:%S'}</td>
            <td class="borderb-gray">{$order->s_firstname}</td>
            <td class="borderb-gray">{$order->s_city}, {$order->s_state}, {$order->s_zipcode}</td>
            <td class="borderb-gray">{$order->cb_status_model}</td>
            <td class="borderb-gray" align="right">{$order->total|site_currency}</td>
        </tr>
    {/foreach}
</table>