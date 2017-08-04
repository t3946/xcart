<table align="right" cellspacing="1" cellpadding="3" width="100%">

    <tr>
        <td colspan="2"><b>Order payments summary</b></td>
    </tr>
    <tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
        <td>Order grand total</td>
        <td align="right"
            {if ( $order_store->getTotal() > 0 && $order_store->getCapturedAvail() == $order_store->getTotal())}style="background-color: #00c61d;"
            {elseif ($order_store->getTotal() > 0 && $order_store->getAmountToCapture() == $order_store->getTotal())}style="background-color: #d9ead3;"{/if}
        >{include file="currency2.tpl" value=$order_store->getTotal()}</td>
    </tr>
    <tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
        <td>Captured amount</td>
        <td align="right" {if ($order_store->getTotal() > 0 && $order_store->getCapturedAvail() == $order_store->getTotal())}style="background-color: #00c61d;"{/if}>{include file="currency2.tpl" value=$order_store->getCapturedAvail()}</td>
    </tr>
    <tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
        <td>Available to capture</td>
        <td align="right" {if ($order_store->getTotal() > 0 && $order_store->getAmountToCapture() == $order_store->getTotal())}style="background-color: #d9ead3;"{/if}>{include file="currency2.tpl" value=$order_store->getAmountToCapture()} {if ($order_store->getAdditionalCaptureAmount())}(+{$order_store->getAdditionalCaptureAmount()}){/if}</td>
    </tr>

    <tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
        <td>
            {if $order_store->getAmountDeficit() >= 0}Order deficit
            {elseif $order_store->getAmountDeficit() < 0}Order proficit
            {/if}
        </td>
        <td align="right" {if ($order_store->getAmountDeficit() != 0)}style="background-color: red;"{/if}>{include file="currency2.tpl" value=$order_store->getAmountDeficit()}</td>
    </tr>
    {if $order_store->getAskFromCx() != 0}
        <tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
            <td>
                {if $order_store->getAskFromCx() >= 0}Ask from Cx
                {elseif $order_store->getAskFromCx() < 0}Return to Cx
                {/if}
            </td>
            <td width="1%" align="right" {if ($order_store->getAskFromCx() != 0)}style="background-color: red;"{/if}>{include file="currency2.tpl" value=$order_store->getAskFromCx()}</td>
        </tr>
    {/if}

</table>