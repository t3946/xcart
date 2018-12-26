<tr{cycle values=", class='TableSubHead'" name="cycle_totals"} style="font-weight: bold;">
    <td style="font-size: 1.1rem;">
        Grand total Cx -> S3 charge<br/>
        <div class="bg__yellow color__black">Grand total S3 -> Dx cost</div>
        <div class="bg__amazon color__black">Grand total S3 -> Az cost</div>
    </td>
    <td colspan="6">&nbsp;</td>
    <td align="right" style="font-size: 1.1rem;">
        {include file="currency2.tpl" value=$oOrder->getOrderTotalNet()}
        <div class="bg__yellow color__black">{$order_store->getS3ToDxTotalNet()|price_format}</div>
        <div class="bg__amazon color__black">{$order_store->getAmazonCompetitorsMinTotal()|price_format}</div>
    </td>
    <td align="right" style="font-size: 1.1rem;">{include file="currency2.tpl" value=$oOrder->getOrderTotalHST() hide_zero='Y'}</td>
    <td align="right" style="font-size: 1.1rem;">
        {include file="currency2.tpl" value=$oOrder->getOrderTotalGross()}
        <div class="bg__yellow color__black">{$order_store->getS3ToDxTotalGross()|price_format}</div>
        <div class="bg__amazon color__black">{$order_store->getAmazonCompetitorsMinTotal()|price_format}</div>
    </td>
    <td>&nbsp;</td>
</tr>