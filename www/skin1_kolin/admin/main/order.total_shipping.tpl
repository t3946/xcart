<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
    <td>
        Total Shipping Cx -> S3 <br/>
        Total Shipping S3 -> Dx <br/>
        Total Shipping S3 -> Az <br/>
    </td>
    <td colspan="6">&nbsp;</td>
    <td align="right">
        {include file="currency2.tpl" value=$oOrder->getOrderShippingNet() hide_zero='Y'} <br/>
        &nbsp; <br/>
        &nbsp; <br/>
    </td>
    <td align="right">{include file="currency2.tpl" value=$oOrder->getOrderShippingHST() hide_zero='Y'}</td>
    <td align="right">
        {include file="currency2.tpl" value=$oOrder->getOrderShippingGross() hide_zero='Y'} <br/>
        &nbsp; <br/>
        &nbsp; <br/>
    </td>
    <td>&nbsp;</td>
</tr>