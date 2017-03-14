<tr data-order-transaction="{$v.id}">
    <td>{$v.payment_method}</td>
    <td>{$v.date|date_format:'%d-%b-%Y<br />%H:%M:%S'}</td>
    <td>{$v.firstname} ({$v.login})</td>
    <td>{if $v.transaction_id ne ""}
            {if $v.transaction_id_link ne ""}<a target="_blank" style="color: #1411FF;" href="{$v.transaction_id_link|substitute:"trans-id":$v.transaction_id}">{/if}
            {if $v.transaction_link_anchor ne ""}{$v.transaction_link_anchor}{else}{$v.transaction_id}{/if}{if $v.transaction_id_link ne ""}</a>{/if}
            {if $v.transaction_link_anchor ne ""}({$v.transaction_id}){/if}
        {/if}
    </td>
        {assign var="tr_status" value=$v.model->getField('transaction_status')}
        <td align="center">{$tr_status->toText()}</td>
    <td>{if $main_transaction}{$v.transaction_amount}{else}{$v.transaction_total}{/if} {$v.transaction_currency}</td>
    <td>
        {if $main_transaction}
            {assign var="tr_log" value=$v.transaction_response}
            {assign var="tr_log_message" value=$v.model->transaction_response.message}
        {else}
            {assign var="tr_log" value=$v.transaction_log}
            {assign var="tr_log_message" value=$v.model->transaction_log.message}
        {/if}

        {if $v.issue ne ""}
            <br />
            <B>issue:</B> {$v.issue}
        {elseif $tr_log_message ne ""}
            <br />
            <B>message:</B> {$tr_log_message}
        {/if}

        {if $tr_log ne ""}
            <div class="transaction_log_div" style="display: none;"><B>Full log:</B><br />{$tr_log}</div>
            <a href="#" style="color: #1411FF;" class="show_hide_link">Show details</a>
        {/if}

    </td>
    {if $main_transaction}
    <td>
        <a class="toggle_transaction_multiple" href="#"><img src="{$ImagesDir}/plus.gif"/></a>
        <a style="display: none;" class="toggle_transaction_multiple" href="#"><img src="{$ImagesDir}/minus.gif"/></a>
    </td>
    {/if}
</tr>
{if $main_transaction}
<tr>
    <td class="transaction_action" colspan="7" data-transaction-id="{$v.id}">
        <span style="float: left; line-height: 22px;"><b>Available actions:</b></span>&nbsp;
        <input type="text" name="transaction_amount[{$v.id}]" id="transaction_amount_{$v.id}" size="6" value="{$v.transaction_amount}" />
        <div class="ui xcart buttons">
            <div data-action="look_up_payment" class="ui button lookup" style="border: 1px solid #808080;">Look up payment (Get links)</div>
            {if $v.unserialized_transaction_response.links ne ""}
                <div style="border-color: #808080; border-image: none; border-style: solid solid solid none; border-width: 1px 1px 1px 0;" class="ui combo top right dropdown icon button">
                    <i class="dropdown icon"></i>
                    <div class="menu" style="min-width: 200px;">
                        {foreach from=$v.unserialized_transaction_response.links item=link key=k_link}
                            {if $link.rel eq "self"}
                            {elseif $link.rel eq "refund"}
                                <div data-action="refund_transaction" class="item" style="padding: 10px !important;">Refund transaction</div>
                            {elseif $link.rel eq "void"}
                                <div data-action="void_transaction" class="item" style="padding: 10px !important;">Void authorized transaction</div>
                            {elseif $link.rel eq "capture"}
                                <div data-action="capture_transaction" class="item" style="padding: 10px !important;">Capture selected authorized transaction</div>
                            {elseif $link.rel eq "reauthorize"}
                                <div data-action="re_authorize_transaction" class="item" style="padding: 10px !important;">RE-authorize selected transaction</div>
                            {/if}
                        {/foreach}
                    </div>
                </div>
            {/if}
        </div>
    </td>
</tr>
{/if}
<tr><td colspan="8"><hr /></td></tr>
