<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
    <td>
        Total Product Cx -> S3
        <div class="bg__yellow color__black" align="left">
            Total Product S3 -> Dx
        </div>
        Total Product S3 -> Az
    </td>
    <td colspan="6">&nbsp;</td>
    <td align="right">{include file="currency2.tpl" value=$oOrder->getProductPriceNet()}
        <div class="bg__yellow color__black" align="right">
            {include file="currency2.tpl" value=$oOrder->getOrderCostToUs()|price_format}
        </div>
        &nbsp; <br/>
    </td>
    <td align="right">{include file="currency2.tpl" value=$oOrder->getProductPriceHSTPST() hide_zero='Y'}</td>
    <td align="right">{include file="currency2.tpl" value=$oOrder->getProductPriceGross()}
        <div class="bg__yellow color__black" align="right">
            {include file="currency2.tpl" value=$oOrder->getOrderCostToUs()|price_format}
        </div>
        &nbsp; <br/>
    </td>
    <td></td>
</tr>