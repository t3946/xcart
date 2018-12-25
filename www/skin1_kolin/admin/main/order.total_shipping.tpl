<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
    <td>
        Total Shipping Cx -> S3 charge<br/>
        Total Shipping S3 -> Dx cost<br/>
        Total Shipping S3 -> Az cost<br/>
    </td>
    <td colspan="6">&nbsp;</td>
    <td align="right">
        {include file="currency2.tpl" value=$oOrder->getOrderShippingNet() hide_zero='Y'}
        <div>{$order_store->getActualShippingCostNet()|price_format}</div>
        {$order_store->getAmazonCompetitorsMinShipping()|price_format}
    </td>
    <td align="right">{include file="currency2.tpl" value=$oOrder->getOrderShippingHST() hide_zero='Y'}</td>
    <td align="right">
        {include file="currency2.tpl" value=$oOrder->getOrderShippingGross() hide_zero='Y'}
        <div>{$order_store->getActualShippingCostGross()|price_format}</div>
        {$order_store->getAmazonCompetitorsMinShipping()|price_format}
    </td>
    <td>&nbsp;</td>
</tr>