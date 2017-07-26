{include file="main/subheader.tpl" title="Virtual Terminal"}


{capture name=authorize}
    <script type="text/javascript">
        //<![CDATA[
        {literal}

        function func_AJAX_authorize_PayPal() {

            var f_name;
            var f_value;
            var cidev_parameters = 'AJAX_SUBMIT=Y';

            $("form[name='vt_form1']").find("input,select,textarea").not('[type="button"]').each(function () {

                f_name = $(this).attr('name');
                f_value = $(this).attr('value');

                if (f_name == "mode") {
                    cidev_parameters = cidev_parameters + '&mode=authorize';
                }
                else if (f_name != "" && f_value != "") {
                    cidev_parameters = cidev_parameters + '&' + f_name + '=' + f_value;
                }
            });

//	alert(cidev_parameters);

            cidev_xmlHttp = cidev_createHttpRequestObject();
            if (cidev_xmlHttp.readyState == 4 || cidev_xmlHttp.readyState == 0) {

                cidev_xmlHttp.onreadystatechange = function () {
                    if (cidev_xmlHttp.readyState == 4) {
                        if (cidev_xmlHttp.status == 200) {
                            var paypal_response = cidev_xmlHttp.responseText;

                            //alert(paypal_response);

                            if (paypal_response == "Authorized" || paypal_response == "Failed") {
                                $("#AJAX_Please_wait").show();
                                $("#AJAX_Authorize_button").hide();
                                $("#AJAX_Authorize_button_text").hide();
                            }

                            var m_id = $("#m_id_for_additional_shipping_status").val();

                            if (paypal_response == "Authorized") {
                                $("#additional_shipping_status_" + m_id).val("A"); // Authorized
                                document.ordereditform1.submit();
                            }
                            else if (paypal_response == "Failed") {
                                $("#additional_shipping_status_" + m_id).val("A");
//						window.location.reload();
                            }

                        } else {
                            cidev_Error('no_server', 'Y');
                        }
                    }
                };

                var tmp_rand = Math.random();

                cidev_xmlHttp.open('POST', 'ajax_paypal_vt.php?rand=' + tmp_rand, true);
                cidev_xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                cidev_xmlHttp.setRequestHeader('Content-length', cidev_parameters.length);
                cidev_xmlHttp.setRequestHeader('Cache-Control', 'no-cache');
                cidev_xmlHttp.setRequestHeader('Cache-Control', 'no-store');
                cidev_xmlHttp.setRequestHeader('Connection', 'close');
                cidev_xmlHttp.send(cidev_parameters);
            }
            else {
                setTimeout('func_AJAX_authorize_PayPal()', 1000);
            }
        }
        {/literal}
        //]]>
    </script>
    <div id="AJAX_Authorize_button_text" style="display: none; background-color: #f4cccc;">
        {$lng.lb_additional_payment_authorize_message}
    </div>
    <form action="{$authorise_url}" method="post" name="vt_form1">
        <input type="hidden" name="mode" id="mode" value=""/>
        <input type="hidden" name="orderid" value="{$orderid}"/>

        <input type="hidden" name="m_id_for_additional_shipping_status" id="m_id_for_additional_shipping_status"
               value=""/>

        <table cellspacing="5" cellpadding="0" align="center">

            <tr>
                <td align="right"><h3 style="color: #000000;">Amount and currency</h3></td>
                <td></td>
            </tr>
            <tr>
                <td align="right"><b>Currency:</b></td>
                <td>
                    <select name="paypal_vt[currency]" id="paypal_vt_currency">
                        <option value="USD">US Dollars</option>
                        <option value="CAN">CA Dollars</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" style="font-size: 12px;"><b>Grand total:</b></td>
                <td><input style="font-size: 12px;" type="text" name="paypal_vt[grand_total]" value="{$order.total}"
                           size="8" required pattern="^\d+(\.?\d+|)$"
                           id="paypal_vt_grand_total"/></td>
            </tr>
            <tr>
                <td align="right"><h3 style="color: #000000;">Credit card information</h3></td>
                <td></td>
            </tr>
            <tr>
                <td align="right"><b>Cardholder's name:</b></td>
                <td><input type="text" name="paypal_vt[cardholderl_name]" value="{$customer.b_firstname}"/></td>
            </tr>
            <tr>
                <td align="right"><b>Card number:</b></td>
                <td><input type="text" name="paypal_vt[card_number]" value="" autocomplete="off"
                           id="paypal_vt_card_number" onkeyup="cidev_check_field_phone_ext('paypal_vt_card_number')"/>
                </td>
            </tr>
            <tr>
                <td align="right"><b>Expiration date:</b></td>
                <td><input type="text" name="paypal_vt[expiration_month]" value="" placeholder="MM" size="2"
                           maxlength="2"/> / <b>20</b><input type="text" name="paypal_vt[expiration_year]" value=""
                                                             placeholder="YY" size="2" maxlength="2"/></td>
            </tr>

            <tr>
                <td nowrap="nowrap" align="right"><b>Security code (CSC):</b>
                    <div class="cidev_field_descr">Optional</div>
                </td>
                <td><input type="text" name="paypal_vt[csc]" value="" size="4" maxlength="4" autocomplete="off"/></td>
            </tr>


            <tr>
                <td align="right"><h3 style="color: #000000;">{$lng.lbl_billing_address}</h3></td>
                <td></td>
            </tr>
            <tr>
                <td align="right"><b>{$lng.lbl_address}:</b></td>
                <td>{if !$static}<input type="text" name="paypal_vt[b_address]"
                                        value="{$customer.b_address}" />{else}{$customer.b_address}{/if}</td>
            </tr>
            <tr>
                <td align="right" nowrap="nowrap">{$lng.lbl_address_2}:</td>
                <td>{if !$static}<input type="text" name="paypal_vt[b_address_2]"
                                        value="{$customer.b_address_2}" />{else}{$customer.b_address_2}{/if}</td>
            </tr>
            <tr>
                <td align="right"><b>{$lng.lbl_city}:</b></td>
                <td>{if !$static}<input type="text" name="paypal_vt[b_city]"
                                        value="{$customer.b_city}" />{else}{$customer.b_city}{/if}</td>
            </tr>
            <tr>
                <td align="right"><b>{$lng.lbl_state}:</b></td>
                <td>{if !$static}
                        {include file="main/states.tpl" states=$states name="paypal_vt[b_state]" default=$customer.b_state default_country=$customer.b_country|default:$config.General.default_country country_name="paypal_vt[b_country]"}
                    {else}{$customer.b_statename}{/if}
                </td>
            </tr>
            <tr>
                <td align="right"><b>{$lng.lbl_country}:</b></td>
                <td>{if !$static}
                        <select name="paypal_vt[b_country]" id="paypal_vt_b_country" size="1">
                            {section name=country_idx loop=$countries}
                                <option value="{$countries[country_idx].country_code}"{if $customer.b_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $customer.b_country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
                            {/section}
                        </select>
                        {if $customer.default_fields.b_state}
                            {include file="main/register_states.tpl" state_name="paypal_vt[b_state]" country_name="paypal_vt[b_country]" county_name="paypal_vt[b_county]" state_value=$customer.b_state county_value=$customer.b_county country_id="paypal_vt_b_country"}
                        {/if}
                    {else}{$customer.b_countryname}{/if}</td>
            </tr>
            <tr>
                <td align="right"><b>{$lng.lbl_zip_code}:</b></td>
                <td>{if !$static}<input type="text" name="paypal_vt[b_zipcode]"
                                        value="{$customer.b_zipcode}" />{else}{$customer.b_zipcode}{/if}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="right"><b>Processor:</b></td>
                <td>
                    <select style="margin:1px 5px 0 0; float:left;" name="paypal_vt[processor]"
                            id="paypal_vt_processor">
                        <option value="Paypal VT">Paypal VT</option>
                        <option value="BluePay VT">BluePay VT</option>
                    </select>
                    <div id="default_Authorize_button">
                        <input type="button" value="Authorize" onclick="javascript: submitForm(this, 'authorize');"/>
                    </div>
                    <div id="AJAX_Authorize_button" style="display: none;">
                        <input type="button" id="btn_Authorize" value="Authorize"
                               onclick="javascript: func_AJAX_authorize_PayPal();"/>
                    </div>
                    <div id="AJAX_Please_wait" style="display: none;">
                        <h1>Please wait. <br>Page will be reloaded now ...</h1>
                    </div>
                </td>
            </tr>
        </table>

    </form>
{/capture}
{include file="dialog.tpl" title="Authorization" content=$smarty.capture.authorize extra='width="100%"'}

{if $order_transactions ne ""}
    <br/>
    {capture name=virtual_terminal_transactions}
        <form action="order.php" method="post" name="vt_form01">
            <input type="hidden" name="mode" id="mode" value=""/>
            <input type="hidden" name="order_transaction_id" id="order_transaction_id" value=""/>
            <input type="hidden" name="orderid" value="{$orderid}"/>
            {assign var='main_transaction' value=true}
            {include file="admin/main/transactions_table.tpl"}
        </form>
        <table align="right" cellspacing="1" cellpadding="1">
            {assign var=oPaymentProcessor value=$oOrder->getPaymentMethodInstance()}
            {math assign="transaction_with_multiplier" equation="x*y" x=$order_transactions_totals.authorized_PLUS_captured_totals y=$oPaymentProcessor->getMaximumReAuthorizationMultiplier()}
            <tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
                <td>Transactions amount (authorized/pending +captured )</td>
                <td>&nbsp;</td>
                <td align="right"
                    style="font-size: 10px; background-color: {if $oOrder->getOrderTotalGross() == $order_transactions_totals.authorized_PLUS_captured_totals}#d9ead3;
                    {elseif $oOrder->getOrderTotalGross() > $order_transactions_totals.authorized_PLUS_captured_totals && $oOrder->getOrderTotalGross() <= $transaction_with_multiplier}
                            yellow
                            {else}red
                    {/if};">{include file="currency2.tpl" value=$order_transactions_totals.authorized_PLUS_captured_totals}</td>
            </tr>

            <tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
                <td>Void total</td>
                <td>&nbsp;</td>
                <td align="right"
                    style="font-size: 10px;">{include file="currency2.tpl" value=$order_transactions_totals.void_total}</td>
            </tr>

            <tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
                <td>Authorized total</td>
                <td>&nbsp;</td>
                <td align="right"
                    style="font-size: 10px;">{include file="currency2.tpl" value=$order_transactions_totals.authorized_total}</td>
            </tr>
            {math assign="transaction_capture_with_multiplier" equation="x*y" x=$order_transactions_totals.captured_total y=$oPaymentProcessor->getMaximumReAuthorizationMultiplier()}
            <tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
                <td>Captured total</td>
                <td>&nbsp;</td>
                <td align="right"
                    style="font-size: 10px; background-color: {if $oOrder->getOrderTotalGross() eq $order_transactions_totals.captured_total}
                            green
                            {elseif $oOrder->getOrderTotalGross() > $order_transactions_totals.captured_total && $oOrder->getOrderTotalGross() <= $transaction_capture_with_multiplier}
                            yellow
                            {else}
                            red{/if};">{include file="currency2.tpl" value=$order_transactions_totals.captured_total}</td>
            </tr>
        </table>
    {/capture}
    {include file="dialog.tpl" title="Transactions" content=$smarty.capture.virtual_terminal_transactions extra='width="100%"'}
{/if}

{if $transactions_log}
    <br/>
    {capture name=virtual_terminal}
        <form action="order.php" method="post" name="vt_form02">
            <input type="hidden" name="mode" id="mode" value=""/>
            <input type="hidden" name="orderid" value="{$orderid}"/>
            {assign var='main_transaction' value=false}
            {include file="admin/main/transactions_table.tpl" order_transactions=$transactions_log}
        </form>
    {/capture}
    {include file="dialog.tpl" title="Transaction log" content=$smarty.capture.virtual_terminal extra='width="100%"'}
{/if}


<br/>
{capture name=add_manual_transaction}
    <form action="order.php" method="post" name="vt_form03">
        <input type="hidden" name="mode" id="mode" value="add_manual_transaction"/>
        <input type="hidden" name="orderid" value="{$orderid}"/>

        <table>
            <tr>
                <td>
                    <b>Transaction status:</b><br/>
                    <select name="transaction_status">
                        <option value="authorized">Authorized</option>
                        <option value="completed">Authorized & Captured</option>
                    </select>
                </td>
                <td width="20">&nbsp;</td>
                <td>
                    <b>Currency<b>
                            <select name="transaction_currency">
                                <option value="USD">U.S. Dollars</option>
                                <option value="CAN"> CAN. Dollars</option>
                            </select>
                </td>
                <td width="20" colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <b>Payment method:</b><br/>
                    <select name="paymentid" required>
                        <option value=""></option>
                        {foreach from=$all_vt_processors item=item_vt key=key_vt}
                            <option {if $v.additional_vt_paymentid eq $item_vt.paymentid} selected="selected"{/if}
                                    value="{$item_vt.paymentid}">{$item_vt.payment_method}</option>
                        {/foreach}
                    </select>
                </td>
                <td width="20">&nbsp;</td>
                <td>
                    <b>Virtual terminal transaction ID:</b><br/>
                    <input type="text" name="transaction_id" value="" size="40" required/>
                </td>
                <td width="20">&nbsp;</td>
                <td>
                    <b>AVS code:</b><br/>
                    <input type="text" name="avs_code" value="" size="1" maxlength="1"/>
                </td>
                <td width="20">&nbsp;</td>
                <td>
                    <b>Transaction amount:</b><br/>
                    <input name="transaction_amount" value="0" size="8" required pattern="^\d+(\.?\d+|)$" type="text"/>
                </td>
            </tr>
        </table>

        <input type="submit" type="button" value="Add transaction"/>

    </form>
{/capture}
{include file="dialog.tpl" title="Add manual transaction" content=$smarty.capture.add_manual_transaction extra='width="100%"'}

<script src="{$SkinDir}/js/semantic/components/dropdown.js"></script>
<script src="{$SkinDir}/js/semantic/components/transition.js"></script>
<link rel="stylesheet" href="{$SkinDir}/js/semantic/components/dropdown.min.css">
<link rel="stylesheet" href="{$SkinDir}/js/semantic/components/transition.min.css">
<link rel="stylesheet" href="{$SkinDir}/js/semantic/components/button.min.css">
{literal}
    <script>
        $('.dropdown').dropdown();
        $('.toggle_transaction_multiple').click(function () {
            var button = $(this);
            var tr_id = $(this).closest('tr').data('order-transaction');
            if (button.parent().hasClass('opened')) {
                button.closest('tr').siblings('tr.transaction_log_' + tr_id).remove();
            } else {
                $.post('ajax_admin.php', {
                        ajax_action: 'get_transactions_log',
                        order_transaction_id: tr_id
                    },
                    function (data) {
                        if (data) {
                            if (button.parent().hasClass('opened')) {
                                button.closest('tr')
                                    .after($('<tr class="transaction_log_' + tr_id + '"/>').html($('<td colspan="8"/>').css('padding-left', '20px').html(data)));
                            }
                        }
                    });
            }
            button.hide();
            button.siblings('.toggle_transaction_multiple').show();
            button.parent().toggleClass('opened');
            return false;
        });
        $('.transaction_info_table').on('click', '.show_hide_link',
            function () {
                $(this).text(function (i, text) {
                    return (text == 'Show details') ? 'Hide details' : 'Show details';
                });
                $(this).prev('.transaction_log_div').toggle('slow');
                return false;
            }
        );
        $('.transaction_info_table .dropdown .item, .transaction_info_table .lookup').click(function () {
            var form = $(this).closest('form');
            form.find('#order_transaction_id').val($(this).closest('td.transaction_action').data('transaction-id'))
                .end().find('#mode').val($(this).data('action'))
                .end().submit();
        })
    </script>
{/literal}