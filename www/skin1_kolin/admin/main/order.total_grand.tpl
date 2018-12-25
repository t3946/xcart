<tr{cycle values=", class='TableSubHead'" name="cycle_totals"} style="font-weight: bold;">
    <td style="font-size: 1.1rem;">
        Grand total Cx -> S3 charge<br/>
        Grand total S3 -> Dx cost<br/>
        Grand total S3 -> Az cost<br/>
    </td>
    <td colspan="6">&nbsp;</td>
    <td align="right" style="font-size: 1.1rem;">
        {include file="currency2.tpl" value=$oOrder->getOrderTotalNet()}
        <div>{$order_store->getS3ToDxTotalNet()|price_format}</div>
        {$order_store->getAmazonCompetitorsMinTotal()|price_format}
    </td>
    <td align="right" style="font-size: 1.1rem;">{include file="currency2.tpl" value=$oOrder->getOrderTotalHST() hide_zero='Y'}</td>
    <td align="right" style="font-size: 1.1rem;">
        {include file="currency2.tpl" value=$oOrder->getOrderTotalGross()}
        <div>{$order_store->getS3ToDxTotalGross()|price_format}</div>
        {$order_store->getAmazonCompetitorsMinTotal()|price_format}
    </td>
    <td>&nbsp;</td>
</tr>