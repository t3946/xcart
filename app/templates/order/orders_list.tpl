<table class="OrderSheet" cellspacing="1" cellpadding="3">
    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td width="5">#</td>
        <td>Fraud Check</td>
        <td>Customer</td>
        <td colspan="2">Order age</td>
        <td colspan="7">Last customer service message</td>
    </tr>

    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td>Date <br /> Time</td>
        <td>OTRS ticket</td>
        <td colspan="1">ZIP code</td>
        <td colspan="2">Last activity</td>
        <td colspan="3">Attention tag</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td></td>
        <td></td>
        <td>Country</td>
        <td colspan="2">LATEST ETA DATE</td>
        <td colspan="2">Payment</td>
        <td>Grand total</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="12" style="padding: 0;"></td>
    </tr>
    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td width="5">DISTR</td>
        <td>C2B PAYMENT</td>
        <td>D2C SHIPPING</td>
        <td colspan="2">B2D INVOICE</td>
        <td colspan="2">Processor</td>
        <td>TOTAL</td>
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

        <tr class="{$cycle_class} title">
            <td>
                <a href="{$order->getAdminUrl()}" style="color: blue; font-weight: bold;" target="_blank">{$order}</a>
            </td>
            <td align="center">
                {foreach $fraud_statuses as $status}
                    {if $status.code == $order->fraud_status}
                        <b>{$status.name}</b>
                    {/if}
                {/foreach} <br>
                ({$order->overall_fraud_score})
            </td>
            <td colspan="1">
                {$order->firstname}
            </td>
            <td colspan="2">
                {$order->date|interval_string}
            </td>
            <td colspan="7" class="text-left">
                {raw $order->last_message.log|br2nl|strip_tags|truncate:160:'[...]'|nl2space}
            </td>
        </tr>

        <tr class="{$cycle_class}">
            <td>
                {raw $order->date|date_format:'%d-%b-%Y %H:%M:%S'}
            </td>
            <td>
                {if $order->otrs_ticket}
                    <a style="color: blue;" href="{$order->otrs_ticket}" target="_blank">
                        OTRS ticket
                    </a>
                {/if}
            </td>
            <td colspan="1">
                <a href="{url 'dashboard:search'}&search[customer][zip_code]={$order->s_zipcode}" target="_blank">
                    {$order->s_zipcode}
                </a>
            </td>
            <td colspan="2">
                {$order->last_activity|interval_string}
            </td>
            <td colspan="3">
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
            <td></td>
            <td></td>
            <td>
                {$order->s_country}
            </td>
            <td style="background-color: {$order->max_eta|max_eta_colors}; color: #000000;" colspan="2">
                {if $order->max_eta|max_eta_colors == "do_not_show"}
                    {$order->max_eta|date_format:'%d-%b-%Y'}
                {/if}
            </td>
            <td colspan="2">
                {foreach $payment_methods as $method}
                    {if $method.paymentid ==$order->paymentid}
                        <span title="{$method.payment_details}">
                                {$method.payment_method}
                        </span>
                    {/if}
                {/foreach}
            </td>
            <td>
                <b> {$order->getOrderTotalGross()|abs|formatprice:",":"."} </b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td colspan="12" style="padding: 0;"></td>
        </tr>
        {foreach $order->getOrderGroups() as $group last=$last_group}
            <tr class="{$cycle_class}">
                <td align="center" width="5" {if $group->manufacturer->submit_to_operator == "through_distributor_website"}style="background: #fff2cc"{/if}>
                    <a href="{$order->getAdminUrl()}" target="_blank">
                        {$group->manufacturer->code}
                    </a>
                </td>
                <td class="OrderSheetGreenCell" align="center">
                    {foreach $order_statuses.CB as $status}
                        {if $status.code == $group->cb_status}
                            <b>{$status.name}</b>
                        {/if}
                    {/foreach}
                </td>
                <td class="OrderSheetGreenCell" align="center">
                    {foreach $order_statuses.DC as $status}
                        {if $status.code == $group->dc_status}
                            <b>{$status.name}</b>
                        {/if}
                    {/foreach}
                </td>
                <td class="OrderSheetGreenCell" align="center" colspan="2">
                    {if $order->amazon_fulfillment_channel == "AFN"}
                        <B>I: Reconciled</B>
                        <br/>
                        <B>C: Reconciled</B>
                    {else}
                        {if $group->getOrderGroupInvoices() }
                            {foreach $group->getOrderGroupInvoices() as $invoice}
                                <B>I-{$invoice->invoice_number}: {$invoice->getStatusName()}</B>
                                <br/>
                            {/foreach}
                        {else}
                            <B>I: Not received</B>
                            <br>
                        {/if}

                        {if $group->getOrderGroupMemos() }
                            {foreach $group->getOrderGroupMemos() as $memo}
                                <B>C-{$memo->memo_number}: {$memo->getStatusName()}</B>
                                <br/>
                            {/foreach}
                        {else}
                            <B>C: Not received</B>
                        {/if}
                    {/if}
                </td>
                <td colspan="2">
                    {foreach $payment_methods as $method}
                        {if $method.paymentid == $group->getPaymentMethodId()}
                            <span title="{$method.payment_details}">
                                <b>{$method.payment_method}</b>
                            </span>
                        {/if}
                    {/foreach}
                </td>
                <td>{$group->getTotalGross()|abs|formatprice:",":"."}</td>
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