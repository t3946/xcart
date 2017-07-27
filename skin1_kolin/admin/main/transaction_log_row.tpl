{assign var=payment_method value=$model->payment_method_model}
<tr data-order-transaction="{$model->id}">
    <td>
        {if ($main_transaction && $model->type)}
            {assign var=tr_type value=$model->getField('type')}
            <b>{$tr_type->toText()}</b><br/>
        {/if}
        {$payment_method->payment_method}
    </td>
    <td>{$model->date|date_format:'%d-%b-%Y<br />%H:%M:%S'}</td>
    <td>{$model->user->firstname} ({$model->user->login})</td>
    <td>{if $model->transaction_id ne ""}
            {if $payment_method->transaction_id_link}<a target="_blank" style="color: #1411FF;" href="{$payment_method->transaction_id_link|substitute:"trans-id":$model->transaction_id}">{/if}
            {if $payment_method->transaction_link_anchor ne ""}{$payment_method->transaction_link_anchor}{else}{$model->transaction_id}{/if}{if $payment_method->transaction_id_link ne ""}</a>{/if}
            {if $payment_method->transaction_link_anchor ne ""}({$model->transaction_id}){/if}
        {/if}
    </td>
        {assign var="tr_status" value=$model->getField('transaction_status')}
        <td align="center">{$tr_status->toText()}</td>
    <td>{if $main_transaction}{$model->transaction_amount}{else}{$model->transaction_total}{/if} {$model->transaction_currency}</td>
    <td>
        {if $main_transaction}
            {assign var="tr_log" value=$model->transaction_response}
            {assign var="tr_log_message" value=$model->transaction_response.message}
        {else}
            {assign var="tr_log" value=$model->transaction_log}
            {assign var="tr_log_message" value=$model->transaction_log.message}
        {/if}

        {if $v.issue ne ""}
            <br />
            <B>issue:</B> {$v.issue}
        {elseif $tr_log_message ne ""}
            <br />
            <B>message:</B> {$tr_log_message}
        {/if}

        {if $tr_log}
            <div class="transaction_log_div" style="display: none;"><B>Full log:</B><br /><pre>{$tr_log|@print_r}</pre></div>
            <a href="#" style="color: #1411FF;" class="show_hide_link">Show details</a>
        {/if}

    </td>
    {if $main_transaction}
    <td>
        {if $model->child->count()}
            <a class="toggle_transaction_multiple" href="#"><img src="{$ImagesDir}/plus.gif"/></a>
            <a style="display: none;" class="toggle_transaction_multiple" href="#"><img src="{$ImagesDir}/minus.gif"/></a>
        {/if}
    </td>
    {/if}
</tr>
{if $main_transaction}
<tr>
    <td class="transaction_action" colspan="7" data-transaction-id="{$model->id}">
        <form action="{$model->getProcessUrl('lookup')}" method="post">
            <span style="float: left; line-height: 22px;"><b>Available actions:</b></span>&nbsp;
            {if $model->getLinks()}
                <input type="text" name="transaction_amount[{$model->id}]" id="transaction_amount_{$model->id}" size="6" value="{$model->transaction_amount|abs}" required pattern="^\d+(\.?\d+|)$"/>
            {/if}
            <div class="ui xcart buttons">
                <div data-action="{$model->getProcessUrl('lookup')}" class="ui button lookup" style="border: 1px solid #808080;">Look up payment</div>
                {if $model->getLinks()}
                    <div style="border-color: #808080; border-image: none; border-style: solid solid solid none; border-width: 1px 1px 1px 0;" class="ui combo top right dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu" style="min-width: 200px;">
                                {foreach from=$model->getLinks() item=link}
                                        {if $link.rel eq "self"}
                                        {elseif $link.rel eq "refund"}
                                            {if in_array($user_login, array('sergey2', 'igor', 'roman_n', 'dmitry_s'))}
                                                <div data-action="{$model->getProcessUrl('refund')}" class="item" style="padding: 10px !important;">Refund transaction</div>
                                            {/if}
                                        {elseif $link.rel eq "void"}
                                                <div data-action="{$model->getProcessUrl('void')}" class="item" style="padding: 10px !important;">Void authorized transaction</div>
                                        {elseif $link.rel eq "capture"}
                                                <div data-action="{$model->getProcessUrl('capture')}" class="item" style="padding: 10px !important;">Capture selected authorized transaction</div>
                                        {elseif $link.rel eq "reauthorize"}
                                                <div data-action="{$model->getProcessUrl('reauthorize')}" class="item" style="padding: 10px !important;">RE-authorize selected transaction</div>
                                        {/if}
                                {/foreach}
                        </div>
                    </div>
                {/if}
            </div>
        </form>
    </td>
</tr>
{/if}
<tr><td colspan="8"><hr /></td></tr>
