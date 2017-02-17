<table class="OrderSheet" cellspacing="1" cellpadding="3" style="">
    <tr class="TableHead TableHeadAccounting">
        <td width="5"></td>
        <td><b>Fraud Check</b></td>
        <td><b>OTRS ticket</b></td>
        <td></td>
        <td>Processor</td>
        <td colspan="7"><b>Last customer service message</b></td>
    </tr>
    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td width="5"><b>#</b></td>
        <td><b>C2B PAYMENT</b></td>
        <td><b>Customer</b></td>
        <td></td>
        <td><b>Payment</b></td>
        <td colspan="2"><b>Order age</b></td>
        <td><b>Attention tag</b></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td width="5"><b>Distr</b></td>
        <td><b>D2C SHIPPING</b></td>
        <td><b>ZIP CODE</b></td>
        <td></td>
        <td><b>Date</b></td>
        <td colspan="2"><b>Last activity</b></td>
        <td><b>LATEST ETA DATE</b></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td width="5">&nbsp;</td>
        <td><b>B2D INVOICE</b></td>
        <td><b>Country</b></td>
        <td><b>Total</b></td>
        <td><b>Time</b></td>
        <td colspan="2"><b>New ticket messages</b></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>


    {foreach $orders as $order index=$index}
        <tr class="separator">
            <td colspan="12"></td>
        </tr>
        {if $index % 2}
            {set $cycle_class = 'TableSubHead_new'}
        {else}
            {set $cycle_class = 'OrderSheetDark'}
        {/if}

        {foreach $order->getOrderGroups() as $group last=$last_group}
            <tr class="{$cycle_class}" style="font-weight: bold;">
                <td align="center" width="5" style="font-weight: normal;">
                    {*{if $static eq 'Y' || $static eq 'O'}*}
                    {*{if $smarty.foreach.groups.first}*}
                    {*<input type="checkbox" name="orderids[{$order.orderid}]" />{/if}*}
                    {*{else}&nbsp;*}
                    {*{/if}*}
                </td>
                <td align="center">
                    {foreach $fraud_statuses as $status}
                        {if $status.code == $order->fraud_status}
                            {$status.name}
                        {/if}
                    {/foreach}
                    ({$order->overall_fraud_score})
                </td>
                <td align="center" nowrap="nowrap" class="OrderSheetCommonCell">
                    {if $order->otrs_ticket}
                        <a style="color: blue;" href="{$order->otrs_ticket}" target="_blank">
                            OTRS ticket
                        </a>
                    {/if}
                </td>
                <td></td>
                <td align="center" class="group">
                    {foreach $payment_methods as $method}
                        {if $method.paymentid == $group->getPaymentMethodId()}
                            <span title="{$method.payment_details}">
                                {$method.payment_method}
                            </span>
                        {/if}
                    {/foreach}
                </td>
                <td colspan="7" style="font-weight: normal;">
                    {*{raw $message.log|br2nl|strip_tags|truncate:160:'[...]'|nl2br}*}
                    {raw $order->last_message.log|br2nl|strip_tags|truncate:160:'[...]'|nl2space}
                </td>
            </tr>

            <tr class="{$cycle_class}">
                <td width="5" align="center">
                    <a href="{$order->getAdminUrl()}" style="color: blue; font-weight: bold;" target="_blank">{$order}</a>
                </td>
                <td class="OrderSheetGreenCell group" align="center">
                    {foreach $order_statuses.CB as $status}
                        {if $status.code == $group->cb_status}
                            <b>{$status.name}</b>
                        {/if}
                    {/foreach}
                </td>
                <td align="center">
                    {$order->firstname}
                </td>
                <td align="center"></td>
                <td align="center">
                    {foreach $payment_methods as $method}
                        {if $method.paymentid ==$order->paymentid}
                            <span title="{$method.payment_details}">
                                {$method.payment_method}
                            </span>
                        {/if}
                    {/foreach}
                </td>
                <td colspan="2" align="left">
                    {$order->date|interval_string}
                </td>
                <td>
                    {foreach $order->tags as $tag}
                        <div style="background-color: #F4CCCC; color: #000000; padding: 3px;">
                        <span title="{$tag.description}">
                            {$tag.status}
                        </span>
                        </div>
                    {/foreach}
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="{$cycle_class}">
                <td align="center" width="5" {if $group->manufacturer->submit_to_operator == "through_distributor_website"}style="background: #fff2cc"{/if} class="group">
                    <a href="{$order->getAdminUrl()}" target="_blank">
                        {$group->manufacturer->code}
                    </a>
                </td>
                <td align="center" class="OrderSheetGreenCell group">
                    {foreach $order_statuses.DC as $status}
                        {if $status.code == $group->dc_status}
                            <b>{$status.name}</b>
                        {/if}
                    {/foreach}
                </td>
                <td align="center">
                    <a href="{url 'dashboard:search'}&search[customer][zip_code]={$order->s_zipcode}" target="_blank">
                        {$order->s_zipcode}
                    </a>
                </td>
                <td align="center"></td>
                <td align="center">
                    {$order->date|date_format:'%d-%b-%Y'}
                </td>
                <td colspan="2" align="left">
                    {$order->last_activity|interval_string}
                </td>
                <td style="background-color: {$order->max_eta|max_eta_colors}; color: #000000;">
                    {if $order->max_eta|max_eta_colors != "do_not_show"}
                        {$order->max_eta|date_format:'%d-%b-%Y'}
                    {/if}
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="{$cycle_class}">
                <td width="5" align="center"></td>
                <td align="center" class="OrderSheetGreenCell group">
                    {if $order->amazon_fulfillment_channel == "AFN"}
                        <B>I: Reconciled</B>
                        <br/>
                        <B>C: Reconciled</B>
                    {else}
                        {if $group->getOrderGroupInvoices()->countOrderGroupInvoices() > 0 }
                            {foreach $group->getOrderGroupInvoices()->getAsArray() as $invoice}
                                <B>I-{$invoice->invoice_number}: {$invoice->getStatusName()}</B>
                                <br/>
                            {/foreach}
                        {else}
                            <B>I: Not received</B>
                            <br>
                        {/if}

                        {if $group->getOrderGroupMemos()->countOrderGroupMemos() > 0}
                            {foreach $group->getOrderGroupMemos()->getAsArray() as $memo}
                                <B>C-{$memo->memo_number}: {$memo->getStatusName()}</B>
                                <br/>
                            {/foreach}
                        {else}
                            <B>C: Not received</B>
                        {/if}
                    {/if}

                </td>
                <td align="center">
                    {set $c_showed = false}
                    {foreach $countries as $country}
                        {if $country.id == $order->s_country}
                            {set $c_showed = true}
                            {raw $country.text}
                        {/if}
                    {/foreach}
                    {if !$c_showed}
                        {$order->s_country}
                    {/if}
                </td>
                <td align="center" class="group">
                    {$group->getTotalGross()|abs|formatprice:",":"."}
                </td>
                <td align="center">
                    {raw $order->date|date_format:"%H:%M:%S"}
                </td>
                <td colspan="2" align="left"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            {if !$last_group}
                <tr>
                    <td colspan="12" style="padding: 0;"></td>
                </tr>
            {/if}
        {/foreach}

    {/foreach}
</table>