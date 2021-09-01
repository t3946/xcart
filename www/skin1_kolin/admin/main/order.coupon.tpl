{if $order.coupon}
    <tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
        <td>{$lng.lbl_coupon_saving}</td>
        <td colspan="6">
            {assign var="couponModel" value=$oOrder->getCouponModel()}
            {if $couponModel}
                {assign var="couponAdmin" value=$couponModel->getAdmin()}
                {$couponModel->code}

                ( <a href="{$couponAdmin->getInfoUrl()}" target="_blank">View info</a> )
            {else}
                {$order.coupon}
            {/if}
        </td>
        <td align="right" class="color__red">
            {include file="currency2.tpl" value=$oOrder->coupon_discount}
        </td>
        <td align="right"></td>
        <td align="right" class="info color__red">
            {include file="currency2.tpl" value=$oOrder->coupon_discount}
        </td>
        <td>&nbsp;</td>
    </tr>
{/if}