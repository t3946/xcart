<link rel="stylesheet" href="{$SkinDir}/js/semantic/components/loader.min.css">
<script src="{$SkinDir}/js/semantic/components/form.min.js" type="text/javascript"></script>
{include file="main/subheader.tpl" title="Send PayPal Request"}

{capture name=paypal_request}
<form action="order.php" method="post" name="paypal_request" class="ui form paypal_request">
    <input type="hidden" id="order_email" name="order_email" value="{$order.email}"/>
    <div class="ui centered loader"></div>
    <table cellspacing="5" cellpadding="0" align="center">
        <tr>
            <td align="right" style="font-size: 12px;">
                <b><label for="paypal_request_email">Payer email* :</label></b>
            </td>
            <td>
                <input class="field" style="font-size: 12px;" type="text" name="paypal_request_email" value="{$order.email}" size="64" id="paypal_request_email" />
            </td>
        </tr>
        <tr>
            <td align="right" style="font-size: 12px;"><b>Request subject* :</b>
                <div class="cidev_field_descr">(put S3Stores name and order number at least)</div>
            </td>
            <td><input class="field" style="font-size: 12px;" type="text" name="paypal_request_subject" value="PayPal money request from S3 Stores, Inc. Order # {$oOrder->getDisplayOrderNumber()}" size="64" id="paypal_request_subject" /></td>
        </tr>
        <tr>
            <td align="right" style="font-size: 12px;"><b>Request notes* :</b>
                <div class="cidev_field_descr">(put payment details)</div>
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
            <td><input type="submit" value="Send request" name="send_paypal_request"/></td>
        </tr>

</table>
</form>
{/capture}
{include file="dialog.tpl" title="Send PayPal Request" content=$smarty.capture.paypal_request extra='width="100%"'}
{literal}
    <script type="text/javascript">
        $.fn.form.settings.rules.gtzero = function(value) {
            return (value > 0)
        };
        $('.ui.form.paypal_request')
                .form({
                    onSuccess: function () {
                        $('.ui.error.message').empty();
                        if ($('#order_email').val() != $('#paypal_request_email').val()) {
                            if (!window.confirm("Payer email is different from order's email. Are you sure?")) {
                                return false;
                            }
                        }
                        var form = $(this);
                        var param = form.css('opacity', 0.4).find('.ui.loader').addClass('active').end().serializeArray();
                        param.push({name: 'ajax_action', value: 'send_paypal_request'});
                        $.post('ajax_admin.php', param,
                                function (data) {
                                    form.css('opacity', 1).find('.ui.loader').removeClass('active');
                                    alert('The Invoice has been send');
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
                                    prompt : '<b>Request subject</b>: Mandatory field is empty!'
                                }
                            ]
                        },
                        paypal_request_notes: {
                            identifier  : 'paypal_request_notes',
                            rules: [
                                {
                                    type   : 'empty',
                                    prompt : '<b>Request notes</b>: Mandatory field is empty!'
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