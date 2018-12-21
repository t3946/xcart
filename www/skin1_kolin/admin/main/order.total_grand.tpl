<tr{cycle values=", class='TableSubHead'" name="cycle_totals"} style="font-weight: bold;">
    <td style="font-size: 1.1rem;">
        Grand total Cx -> S3 <br/>
        Grand total S3 -> Dx <br/>
        Grand total S3 -> Az <br/>
    </td>
    <td colspan="6">&nbsp;</td>
    <td align="right" style="font-size: 1.1rem;">
        {include file="currency2.tpl" value=$oOrder->getOrderTotalNet()} <br/>
        &nbsp; <br/>
        &nbsp; <br/>
    </td>
    <td align="right" style="font-size: 1.1rem;">{include file="currency2.tpl" value=$oOrder->getOrderTotalHST() hide_zero='Y'}</td>
    <td align="right" style="font-size: 1.1rem;">
        {include file="currency2.tpl" value=$oOrder->getOrderTotalGross()} <br/>
        &nbsp; <br/>
        &nbsp; <br/>
    </td>
    <td>&nbsp;</td>
</tr>