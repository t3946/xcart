<table class="SubHeader" cellspacing="0">
    <tr>
        <td class="Green2">Latest orders</td>
    </tr>
    <tr>
        <td class="SubHeaderLine"><img src="/skin1_kolin/images/spacer.gif" class="Spc" alt=""><br></td>
    </tr>
</table>

<table cellpadding="3" cellspacing="0" width="100%">
    <tr class="TableHead">
        <td align="left">Order #</td>
        <td align="left">Order date</td>
        <td align="left">CX time</td>
        <td align="left" nowrap="nowrap">Customer name</td>
        <td align="left" nowrap="nowrap">Shipping</td>
        <td align="left" nowrap="nowrap">Payment status</td>
        <td nowrap="nowrap">Grand total</td>
        <td>One page</td>
    </tr>
    {foreach $last_orders as $order}
        {set $cs_time = $order->getCxDateTime(false)}
        <tr class="{cycle ['SectionBox','TableSubHead']}">
            <td class="borderr-gray"><a target="_blank" href="{$order->getAdminUrl()}">{$order->getOrderNumber()}</a></td>
            <td class="borderb-gray">{$order->date|date_format:'%b %e, %Y %H:%M:%S'}</td>
            <td class="borderb-gray">{if $cs_time}{$cs_time->format('H:i')}{/if}</td>
            <td class="borderb-gray">{$order->s_firstname}</td>
            <td class="borderb-gray">{$order->s_city}, {$order->s_state}, {$order->s_zipcode}</td>
            <td class="borderb-gray">{$order->cb_status_model}</td>
            <td class="borderb-gray" align="right">{$order->total|site_currency}</td>
            <td class="borderb-gray" align="center">{$order->is_new_checkout ? 'Y' : 'N'}</td>
        </tr>
    {/foreach}
</table>