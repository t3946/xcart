<link rel="stylesheet" href="{$SkinDir}/js/semantic/components/loader.min.css">
{include file="main/subheader.tpl" title="Send PayPal Payment Request"}
<p>{$lng.lbl_payment_request_notes_text}</p>
{capture name=paypal_request}
<form action="order.php" method="post" name="paypal_request" class="ui form paypal_request">
    <input type="hidden" id="order_email" name="order_email" value="{$oOrder->getEmail()}"/>
    <input type="hidden" id="send_request_orderid" name="send_request_orderid" value="{$oOrder->getOrderId()}"/>
    <input type="hidden" id="invoice_next_number" name="invoice_next_number" value="{$oOrder->getCustomerInvoiceNextNumber()}"/>
    <div class="ui centered loader"></div>
    <table cellspacing="5" cellpadding="0" align="center" style="border-spacing: 5px; border-collapse: initial;">
        <tr>
            <td align="right" style="font-size: 0.8125rem;">
                <b>Invoice #</b>
            </td>
            <td>
                <input readonly="readonly" class="field" style="font-size: 0.8125rem;" type="text" name="paypal_request_invoice_number" value="{$oOrder->getDisplayOrderNumber()}-{$oOrder->getCustomerInvoiceNextNumber()}" size="20" id="paypal_request_invoice_number" />
            </td>
        </tr>
        <tr>
            <td align="right" style="font-size: 0.8125rem;"><b>‘Bill to’ Full name</b></td>
            <td>
                <input class="field" style="font-size: 0.8125rem;" type="text" name="b_firstname" value="{$oOrder->b_firstname}" size="64" id="paypal_b_firstname" />
            </td>
        </tr>
        <tr>
            <td align="right" style="font-size: 0.8125rem;">
                <b>‘Bill to’ Email</b>
            </td>
            <td>
                <input class="field" style="font-size: 0.8125rem;" type="text" name="paypal_request_email" value="{$oOrder->getEmail()}" size="64" id="paypal_request_email" />
            </td>
        </tr>
        <tr>
            <td align="right" style="font-size: 0.8125rem;"><b>Payment request subject line</b>
                <div class="cidev_field_descr">(should contain S3 Stores Inc. and order #)</div>
            </td>
            <td><input class="field" style="font-size: 0.8125rem;" type="text" name="paypal_request_subject" value="PayPal money request for order # {$oOrder->getDisplayOrderNumber()} from S3 Stores, Inc." size="64" id="paypal_request_subject" /></td>
        </tr>
        <tr>
            <td align="right" style="font-size: 0.8125rem;"><b>Invoice description</b>
            </td>
            {assign var="site" value=$oOrder->site}
            {assign var="site_config" value=$site->getConfig()}
            <td><input class="field" style="font-size: 0.8125rem;" type="text" name="paypal_request_notes" size="64" id="paypal_request_notes"
                value="{$site_config.company_name} order # {$oOrder->getDisplayOrderNumber()}"
                /></td>
        </tr>
        <tr>
            <td align="right" style="font-size: 0.8125rem;"><b>Amount due</b> </td>
            <td><input class="field" style="font-size: 0.8125rem;" type="text" name="paypal_request_amount" value="{$oOrder->total|number_format:2:'.':''}" size="8" id="paypal_request_amount" />
                <select style="font-size: 0.8125rem; padding: 2px;" name="paypal_request_currency" id="paypal_request_currency">
                    <option value="USD">US Dollars</option>
                    <option value="CAD">CA Dollars</option>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div class="ui error message"></div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Send request" name="send_paypal_request" id="send_paypal_request"/></td>
        </tr>

</table>
</form>
{/capture}

{capture name=paypal_invoices}
    {if ($cx_invoices)}
    <table cellspacing="5" cellpadding="0" width="100%">
        <thead>
        <th>Invoice date</th>
        <th>Invoice #</th>
        <th>Link to PayPal invoice</th>
        <th>Invoice description</th>
        <th>Amount due</th>
        <th>Invoice status</th>
        </thead>
        {foreach from=$cx_invoices item="inv_item"}
            <tr class="invoice_list_row" data-status="new">
                {assign var="invDate" value=$inv_item->getInvoiceDate()}
                <td>{$invDate->format('d-M-Y H:i')}</td>
                <td>{$oOrder->getDisplayOrderNumber()}-{$inv_item->getField('invoice_order_number')}</td>
                <td class="pp_invoice_number" data-inv-number="{$inv_item->getField('invoice_number')}"><a href="https://www.paypal.com/webscr?cmd=_history-details-from-hub&id={$inv_item->getField('invoice_number')}" target="_blank">{$inv_item->getField('invoice_number')}</a></td>
                <td>{$inv_item->getField('short_payment_description')}</td>
                <td align="center">{$inv_item->getField('currency')} {include file="currency2.tpl" value=$inv_item->getField('amount')}</td>
                <td class="inv_status ui centered inline mini loader" align="center">{$inv_item->getField('status')}</td>
            </tr>
        {/foreach}
    </table>
    {/if}
{/capture}
{include file="dialog.tpl" title="Send PayPal Payment Request" content=$smarty.capture.paypal_request extra='width="100%"'}
{if ($cx_invoices)}
    <br/>
    <br/>
    {include file="dialog.tpl" title="PayPal Invoices" content=$smarty.capture.paypal_invoices extra='width="100%"'}
{/if}
