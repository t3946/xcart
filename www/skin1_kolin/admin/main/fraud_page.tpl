<div style="font-size: 15px; font-weight: bold; margin: 15px;" align="center">Fraud check for
    <a style="color: #550000;" href="{$orderModel->getAdminUrl()}">order # {$orderModel->getOrderNumber()}</a></div>

{if $you_cannot_modify_order eq "Y"}
    {if $warning_message ne ""}
        <table width="100%">
            <tr>
                <td align="center" style="border: solid 1px #000000; background: #F4CCCC;">
                    {$warning_message}
                </td>
            </tr>
        </table>
        <br/>
    {/if}

{else}
    <table width="100%">
        <tr>
            <td align="center" style="border: solid 1px #000000; background: #D9EAD3;">
                {if $order_unlocked eq "Y"}
                    {$unlock_message}
                {else}
                    <form action="fraud_page.php?orderid={$orderid}" method="post" name="unlockorderform">
                        <input type="hidden" name="mode" value="" id="id_mode_unlock"/>
                        {$lock_message}<input type="button" value="Unlock it now"
                                              onclick="javascript: $('#id_mode_unlock').val('unlock_order'); this.form.submit();"/>.

                        {if $count_locked_orders gt "1"}
                            <input type="button" value="Unlock all orders locked by me"
                                   onclick="javascript: $('#id_mode_unlock').val('unlock_orders'); this.form.submit();"/>
                        {/if}

                    </form>
                {/if}
            </td>
        </tr>
    </table>
    <br/>
{/if}

{capture name=dialog}
    <script>
        {literal}

        function func_show_full_info(code) {
            $('#div_' + code).toggle();
            var button_value = $('#button_' + code).val();
            if (button_value === "[+]") {
                $('#button_' + code).val("[-]");
            } else {
                $('#button_' + code).val("[+]");
            }
        }
        function scrollToFirstNotAnswered()
        {
            $('html, body').animate({
                scrollTop: $('.not-answered').offset().top
            }, 1000);
        }
        $(document).ready(function (){
            scrollToFirstNotAnswered();
            $('tr.not-answered input.manual-action').click(function() {
                $(this).closest('tr').removeClass('not-answered');
                if ($(this).data('child') !== 'undefined') {
                    $('input[data-key='+$(this).data('child')+']').click();
                }
                scrollToFirstNotAnswered();
            });
        });
        {/literal}
    </script>
    <form name="fraudform" action="fraud_page.php" method="post">

        <input type="hidden" name="mode" value="" id="mode"/>
        <input type="hidden" name="orderid" value="{$orderid}"/>

        <table width="100%" style="background-color: #000000;" cellpadding="1" cellspacing="1">
            <tr style="background-color: #cccccc;">
                <td><B>Fraud check question</B></td>
                <td><B>Manual action</B></td>
                <td align="right"><B>Bare fraud score</B></td>
                <td align="right"><B>Importance factor</B></td>
                <td align="right"><B>Fraud score</B></td>
            </tr>

            {if $fraud_checks}
                {foreach from=$fraud_checks item=item key=key}
                    {assign var=fraud_result value=$item->getMethodResult($orderModel, false)}
                    <tr
                            {assign var="bold_arr_index" value="-1"}
                            {assign var="fraud_score" value=$item->getScore($orderModel, false)}
                            {if $fraud_score !== 0}
                                {if $item->auto === 'Y'}
                                    {if $fraud_result[0] === 'positive'}
                                        style="background-color: #D9EAD3;"
                                        {assign var="bold_arr_index" value="2"}
                                    {elseif $fraud_result[0] === 'negative'}
                                        style="background-color: #F4CCCC;"
                                        {assign var="bold_arr_index" value="0"}
                                    {else}
                                        style="background-color: #FFF2CC;"
                                        {assign var="bold_arr_index" value="1"}
                                    {/if}
                                {else}
                                    {if !$fraud_result[3]}
                                        style="background-color: #FFFFFF;"
                                        class="not-answered"
                                    {elseif $fraud_result[3] === 'Y'}
                                        style="background-color: #D9EAD3;"
                                        {assign var="bold_arr_index" value="2"}
                                    {elseif $fraud_result[3] === 'N'}
                                        style="background-color: #F4CCCC;"
                                        {assign var="bold_arr_index" value="0"}
                                    {/if}
                                {/if}
                            {else}
                                style="background-color: #FFFFFF;"
                            {/if}
                    >
                        <td>
                            <div align="right"><I>Question code: {$item->question_code}</I></div>
                            {$item->getCompiledBody($orderModel)}
                            {assign var=response value=$item->getResponse($orderModel)}
                            {$response}
                        </td>
                        <td nowrap="nowrap">
                            <input type="hidden" name="posted_data[{$key}][question_code]" value="{$item->question_code}"/>
                            {if $item->auto eq "Y"}
                                Auto
                            {else}
                                <input data-key="{$key}_Y" class="manual-action" type="radio" name="posted_data[{$key}][manual_action]" value="Y"{if $fraud_result[3] === "Y"} checked="checked"{/if} />
                                Yes
                                <br/>
                                <input data-key="{$key}_N" {if $item->question_code == 'MANUAL_CHECK_EMAIL_DOMAIN_WEBSITE'}data-child="32_N"{/if} class="manual-action" type="radio" name="posted_data[{$key}][manual_action]" value="N"{if $fraud_result[3] === "N"} checked="checked"{/if} />
                                No
                            {/if}
                        </td>
                        <td nowrap="nowrap" align="right">
                            {if $fraud_result[1] === null || ($item->auto !== 'Y' && !$fraud_result[3])}To be calculated{else}{$fraud_result[1]}{/if}
                        </td>
                        <td nowrap="nowrap" align="right">
                            {if $item->getImportanceFactor()}
                                {foreach from=$item->getImportanceFactor() item=vv key=kk}
                                    {if $kk eq $bold_arr_index}<B>{/if}{$vv}{if $kk eq $bold_arr_index}</B>{/if}{if $kk lt 2}, {/if}
                                {/foreach}
                            {else}
                                {$item->importance_factor}
                            {/if}
                        </td>
                        <td nowrap="nowrap" align="right">
                            {if $response}
                                <input type="button" value="[+]" id="button_{$item->question_code}" onclick="func_show_full_info('{$item->question_code}');">
                                <br/>
                                <br/>
                            {/if}
                            {if $fraud_result[1] === null || ($item->auto !== 'Y' && !$fraud_result[3])}To be calculated{else}{$fraud_score|number_format:2}{/if}
                        </td>
                    </tr>
                {/foreach}
            {/if}

            <tr style="background-color: #FFFFFF;">
                <td colspan="4" align="right"><b>Overall fraud score:</b></td>
                <td align="right">{if $overall_fraud_score eq 0}0{else}{$overall_fraud_score|default:"To be calculated"}{/if}</td>
            </tr>

            <tr style="background-color: #FFFFFF;">
                <td colspan="5" align="right">
                    <b>Current fraud check status:</b> {include file="main/fraud_status.tpl" fraud_status=$orderModel->fraud_status fraud_static="Y"}
                </td>
            </tr>

            {foreach from=$orderModel->groups item=v name=groups}
                <tr style="background-color: #FFFFFF;">
                    <td colspan="5">
                        <table width="100%">
                            <tr>
                                <td></td>
                                <td align="center">
                                    <B>Payment:</B> {$orderModel->payment_method_model}
                                </td>
                                <td align="right">
                                    <b>{$lng.lbl_processor}:</b>
                                    <select name="acc_paymentid">
                                        <option value="0"></option>
                                        {foreach from=$all_processors item=ps}
                                            <option value="{$ps->paymentid}" {if $ps->paymentid == $v->acc_paymentid || $ps->paymentid == $orderModel->paymentid} selected="selected" {/if}>{$ps->payment_method}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            {/foreach}

            <tr style="background-color: #FFFFFF;">
                <td colspan="5">
                    <a name="buttons"></a>
                    <input class="not-answered" type="button" value="Apply changes and update fraud scores"
                           onclick="$('#mode').val('apply_changes_and_update_fraud_scores'); document.fraudform.submit();">
                    <input type="button" value="Don't apply changes and close this window"
                           onclick="window.close();">
                </td>
            </tr>
        </table>

        <br/>
        {capture name=dialog}

            {$lng.lbl_fraud_check_expert_section}
            <table width="100%">
                <tr>
                    <td>
                        <b>Change fraud check status to:</b> {include file="main/fraud_status.tpl" fraud_status=$orderModel->fraud_status}
                    </td>
                    <td align="right">
                        <input type="button" value="Apply changes, update fraud scores and change fraud check status"
                               onclick="$('#mode').val('apply_changes_and_update_fraud_scores_and_change_fraud_check_status'); document.fraudform.submit();">
                    </td>
                </tr>
            </table>
        {/capture}
        {include file="dialog.tpl" title="Fraud check expert section" content=$smarty.capture.dialog extra='width="100%"'}

    </form>
{/capture}
{include file="dialog.tpl" title="Fraud check questions" content=$smarty.capture.dialog extra='width="100%"'}
