<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
    <td>
        Total Shipping Cx -> S3 charge<br/>
        <div class="bg__yellow color__black">Total Shipping S3 -> Dx cost</div>
        <div class="bg__amazon color__black">Total Shipping S3 -> Az cost</div>
    </td>
    <td colspan="6">&nbsp;</td>
    <td align="right">
        {include file="currency2.tpl" value=$oOrder->getOrderShippingNet() hide_zero='Y'}
        <div class="bg__yellow color__black">{$order_store->getActualShippingCostNet()|price_format}</div>
        <div class="bg__amazon color__black">{$order_store->getAmazonCompetitorsMinShipping()|price_format}</div>
    </td>
    <td align="right">{include file="currency2.tpl" value=$oOrder->getOrderShippingHST() hide_zero='Y'}</td>
    <td align="right">
        {include file="currency2.tpl" value=$oOrder->getOrderShippingGross() hide_zero='Y'}
        <div class="bg__yellow color__black">{$order_store->getActualShippingCostGross()|price_format}</div>
        <div class="bg__amazon color__black">{$order_store->getAmazonCompetitorsMinShipping()|price_format}</div>
    </td>
    <td>&nbsp;</td>
</tr>