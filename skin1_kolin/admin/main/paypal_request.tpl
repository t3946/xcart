<link rel="stylesheet" href="{$SkinDir}/js/semantic/components/loader.min.css">
<script src="{$SkinDir}/js/semantic/components/form.min.js" type="text/javascript"></script>
{include file="main/subheader.tpl" title="Send PayPal Payment Request"}
<p>{$lng.lbl_payment_request_notes_text}</p>
{capture name=paypal_request}
<form action="order.php" method="post" name="paypal_request" class="ui form paypal_request">
    <input type="hidden" id="order_email" name="order_email" value="{$oOrder->getEmail()}"/>
    <input type="hidden" id="send_request_orderid" name="send_request_orderid" value="{$oOrder->getOrderId()}"/>
    <input type="hidden" id="invoice_next_number" name="invoice_next_number" value="{$oOrder->getCustomerInvoiceNextNumber()}"/>
    <div class="ui centered loader"></div>
    <table cellspacing="5" cellpadding="0" align="center">
        <tr>
            <td align="right" style="font-size: 12px;">
                <b>Cx invoice number* :</b>
            </td>
            <td>
                <input readonly="readonly" class="field" style="font-size: 12px;" type="text" name="paypal_request_invoice_number" value="{$oOrder->getDisplayOrderNumber()}-{$oOrder->getCustomerInvoiceNextNumber()}" size="20" id="paypal_request_invoice_number" />
            </td>
        </tr>
        <tr>
            <td align="right" style="font-size: 12px;">
                <b>Payer email* :</b>
            </td>
            <td>
                <input class="field" style="font-size: 12px;" type="text" name="paypal_request_email" value="{$oOrder->getEmail()}" size="64" id="paypal_request_email" />
            </td>
        </tr>
        <tr>
            <td align="right" style="font-size: 12px;"><b>Payment Request subject* :</b>
                <div class="cidev_field_descr">(put S3 Stores Inc. name and order number at least)</div>
            </td>
            <td><input class="field" style="font-size: 12px;" type="text" name="paypal_request_subject" value="PayPal money request for order # {$oOrder->getDisplayOrderNumber()} from S3 Stores, Inc." size="64" id="paypal_request_subject" /></td>
        </tr>
        <tr>
            <td align="right" style="font-size: 12px;"><b>Short payment description* :</b>
            </td>
            <td><input class="field" style="font-size: 12px;" type="text" name="paypal_request_notes" value="" size="64" id="paypal_request_notes" /></td>
        </tr>
        <tr>
            <td align="right" style="font-size: 12px;"><b>Request amount* :</b> </td>
            <td><input class="field" style="font-size: 12px;" type="text" name="paypal_request_amount" value="0.00" size="8" id="paypal_request_amount" />
                <select style="font-size: 12px; padding: 2px;" name="paypal_request_currency" id="paypal_request_currency">
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
        <th>Cx invoice #</th>
        <th>PP invoice #</th>
        <th>Payment description</th>
        <th>Payment amount</th>
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
<br/>
<br/>
{include file="dialog.tpl" title="PayPal Invoices" content=$smarty.capture.paypal_invoices extra='width="100%"'}
{literal}
    <script type="text/javascript">
        $('#main_order_tabs-container').bind('tabsshow', function(event, ui) {
            if ($(ui.tab).attr('href') == '#main_order_tabs-paypal_request'){
                $('.invoice_list_row[data-status=new]').each(function(){
                    var row = $(this);
                    var inv_number = $(this).find('.pp_invoice_number').data('inv-number');
                    row.find('.inv_status').addClass('active').text('');
                    $.post('ajax_admin.php', {
                                ajax_action: 'get_paypal_invoice_status',
                                paypal_invoice_id: inv_number
                            },
                            function (data) {
                                if (data.result) {
                                    row.attr('data-status', 'updated');
                                    row.find('.inv_status').removeClass('active').removeClass('ui').text(data.status);
                                }
                            }, 'json');
                })
            }
        });
        $.fn.form.settings.rules.gtzero = function(value) {
            return (value > 0)
        };
        $('.ui.form.paypal_request')
                .form({
                    onValid: function(){
                        $('.ui.error.message').empty();
                    },
                    onSuccess: function () {
                        $('.ui.error.message').empty();
                        if ($('#order_email').val() != $('#paypal_request_email').val()) {
                            if (!window.confirm("Payer email is different from order's email. Are you sure?")) {
                                return false;
                            }
                        }
                        var form = $(this);
                        var param = form.css('opacity', 0.4).find('.ui.loader').addClass('active').end().serializeArray();
                        form.find('#send_paypal_request').attr('disabled', 'disabled');
                        param.push({name: 'ajax_action', value: 'send_paypal_request'});
                        $.post('ajax_admin.php', param,
                                function (data) {
                                    form.css('opacity', 1).find('.ui.loader').removeClass('active').end().find('#send_paypal_request').removeAttr('disabled');
                                    if (data) {
                                        form.find('#paypal_request_amount').val('0.00').end()
                                                .find('#paypal_request_notes').val('').end();
                                        alert('The Invoice has been send');
                                    } else {
                                        alert('An error occurred');
                                    }
                                    window.location.reload();
                                }, 'json');
                        return false;
                    },
                    fields: {
                        paypal_request_email: {
                            identifier  : 'paypal_request_email',
                            rules: [
                                {
                                    type   : 'empty',
                                    prompt : '<b>Payer email</b>: Mandatory field is empty!'
                                },
                                {
                                    type   : 'email',
                                    prompt : '<b>Payer email</b>: Email address is incorrect'
                                }
                            ]
                        },
                        paypal_request_subject: {
                            identifier  : 'paypal_request_subject',
                            rules: [
                                {
                                    type   : 'empty',
                                    prompt : '<b>Payment Request subject</b>: Mandatory field is empty!'
                                }
                            ]
                        },
                        paypal_request_notes: {
                            identifier  : 'paypal_request_notes',
                            rules: [
                                {
                                    type   : 'empty',
                                    prompt : '<b>Short payment description</b>: Mandatory field is empty!'
                                }
                            ]
                        },
                        paypal_request_amount: {
                            identifier  : 'paypal_request_amount',
                            rules: [
                                {
                                    type   : 'empty',
                                    prompt : '<b>Request amount</b>: Mandatory field is empty!'
                                },
                                {
                                    type   : 'regExp[/^[0-9]*[.]{0,1}[0-9]{0,2}$/]',
                                    prompt : '<b>Request amount</b>: Value is incorrect!'
                                },
                                {
                                    type   : 'gtzero',
                                    prompt : '<b>Request amount</b>: Value must be greater then 0!'
                                }
                            ]
                        }
                    }

                });

    </script>

{/literal}