<table class="OrderSheet orders" cellspacing="1" cellpadding="3">
    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td width="5">#</td>
        <td>Fraud Check</td>
        <td>Customer</td>
        <td colspan="2">Order age</td>
        <td colspan="7">Last customer service message</td>
    </tr>

    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td colspan="2">OTRS ticket</td>
        <td colspan="1">ZIP code</td>
        <td colspan="2">Last activity</td>
        <td colspan="7">Attention tag</td>
    </tr>

    <tr class="TableHead TableHeadAccounting TableHeadLight">
        <td colspan="2">Date <br /> Time</td>
        <td>Country</td>
        <td colspan="2">LATEST ETA DATE</td>
        <td colspan="2">Payment</td>
        <td>Grand total</td>
        <td colspan="4">+</td>
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
        <td colspan="4">+</td>
    </tr>

    {foreach $orders as $order index=$index}
        {set $cycle_class}{cycle ["OrderSheetDark","TableSubHead_new"]}{/set}

        <tr class="separator">
            <td colspan="12"></td>
        </tr>

        <tr class="{$cycle_class} title">
            <td>
                <a href="{$order->getAdminUrl()}" class="order_link" target="_blank">
                    {$order->order_prefix}{$order->orderid}
                </a>
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
            <td colspan="2">
                {if $order->otrs_ticket}
                    <a style="color: blue;" href="{$order->otrs_ticket}" target="_blank">
                        OTRS ticket
                    </a>
                {/if}
            </td>
            <td colspan="1">
                {set $zip_code = '-'|explode:$order->s_zipcode}

                {*{set $query =  $.request->getQueryArray()}*}
                {set $query = []}
                {set $query['search']['customer']['zip_code'] = $zip_code.0 }

                <a href="{build_url data=$query}" target="_blank">
                    {$order->s_zipcode}
                </a>
            </td>
            <td colspan="2">
                {$order->last_activity|interval_string}
            </td>
            <td colspan="7">
                {foreach $order->tags as $tag}
                    <div style="background-color: {if $tag->color}{$tag->color}{else}#F4CCCC{/if}; color: #000000; padding: 3px;">
                        <span title="{$tag->description}">
                            {$tag->status}
                        </span>
                    </div>
                {/foreach}
            </td>
        </tr>

        <tr class="{$cycle_class}">
            <td colspan="2">
                {raw $order->date|date_format:'%d-%b-%Y <br/>%H:%M:%S'}
            </td>
            <td>
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
            <td style="background-color: {$order->getMaxEta()|max_eta_colors}; color: #000000;" colspan="2">
                {if $order->getMaxEta()|max_eta_colors != "do_not_show"}
                    {$order->getMaxEta()|date_format:'%d-%b-%Y'}
                {/if}
            </td>
            <td colspan="2">
                {foreach $payment_methods as $method}
                    {if $method.paymentid == $order->paymentid}
                        <span title="{$method.payment_details}">
                                {$method.payment_method}
                        </span>
                    {/if}
                {/foreach}
            </td>
            <td>
                <b> {$order->getOrderTotalGross()|abs|formatprice:",":"."} </b>
            </td>
            <td class="events-container" colspan="4">
                {if $order->getCountEvents()}
                    <span class="events">
                        +{$order->getCountEvents()}
                    </span>
                {/if}
            </td>
        </tr>

        <tr>
            <td colspan="12" style="padding: 0;"></td>
        </tr>
        {foreach $order->groups as $group last=$last_group}
            <tr class="{$cycle_class} {if $group->getShippingModel()->important}important{/if}">
                <td align="center" width="5" {if $group->manufacturerid->submit_to_operator == "through_distributor_website"}style="background: #fff2cc"{/if}>

                    {set $query =  $.request->getQueryArray()}
                    {set $query['search']['order']['distributor'][] = $group->manufacturer->manufacturerid }

                    <a href="{extend_url data=$query}" target="_blank">
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
                        {foreach $group->invoices->all() as $invoice}
                            <B>I-{$invoice->invoice_number}: {$invoice->getStatusName()}</B>
                            <br/>
                        {foreachelse}
                            <B>I: Not received</B>
                            <br>
                        {/foreach}

                        {foreach $group->memos->all() as $memo}
                            <B>C-{$memo->memo_number}: {$memo->getStatusName()}</B>
                            <br/>
                        {foreachelse}
                            <B>C: Not received</B>
                        {/foreach}
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
                <td colspan="4">
                    {if $group->getShippingModel()->important}
                        <span class="sign important"></span>
                    {/if}
                </td>
            </tr>
            {if !$last_group}
            <tr>
                <td colspan="12" style="padding: 0;"></td>
            </tr>
            {/if}
        {/foreach}

    {/foreach}
</table>