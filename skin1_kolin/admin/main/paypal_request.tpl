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
                <b>Cx invoice number :</b>
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
{include file="dialog.tpl" title="Send PayPal Payment Request" content=$smarty.capture.paypal_request extra='width="100%"'}
{literal}
    <script type="text/javascript">
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
                                    if (data == 'true') {
                                        form.find('#paypal_request_amount').val('0.00').end()
                                                .find('#paypal_request_notes').val('').end();
                                        alert('The Invoice has been send');
                                    } else {
                                        alert('An error occurred');
                                    }
                                    window.location.reload();
                                });
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