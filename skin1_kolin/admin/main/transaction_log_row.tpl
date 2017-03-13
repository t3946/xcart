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
    <td align="center">{$v.transaction_status}</td>
    <td>{if $main_transaction}{$v.transaction_amount}{else}{$v.transaction_total}{/if} {$v.transaction_currency}</td>
    <td>
        Transaction:
        {if $v.transaction_id ne ""}

            {if $v.transaction_id_link ne ""}<a target="_blank" style="color: #1411FF;" href="{$v.transaction_id_link|substitute:"trans-id":$v.transaction_id}">{/if}
            {if $v.transaction_link_anchor ne ""}{$v.transaction_link_anchor}{else}{$v.transaction_id}{/if}{if $v.transaction_id_link ne ""}</a>{/if}

            {if $v.transaction_link_anchor ne ""}({$v.transaction_id}){/if}

            {if $v.unserialized_transaction_log.FIELD_manual_transaction eq "Y"}
                (Manually added)
            {/if}

        {else}
            NONE
        {/if}
        <br />
        transaction_status: <B>{$v.transaction_status}</B><br />
        transaction_currency: {$v.transaction_currency}<br />
        transaction_total: {$v.transaction_total}

        {if $v.issue ne ""}
            <br />
            <B>issue:</B> {$v.issue}
        {elseif $v.unserialized_transaction_log.message ne ""}
            <br />
            <B>message:</B> {$v.unserialized_transaction_log.message}
        {/if}


        {if $v.transaction_log ne ""}
            <script>
                //<![CDATA[
                {literal}
                $(document).ready(function(){
                    $('#show_hide_link_{/literal}{$k}{literal}').click(
                            function() {
                                $(this).text(function(i,text) { return (text == 'Show details') ? 'Hide details' : 'Show details'; });
                                $('#transaction_log_div_{/literal}{$k}{literal}').toggle('slow');
                                return false;
                            }
                    );
                });
                {/literal}
                //]]>
            </script>

            <br />
            <div id="transaction_log_div_{$k}" style="display: none;"><B>Full log:</B><br />{$v.transaction_log}</div>
            <a href="javascript: void(0);" style="color: #1411FF;" onclick="javascript: func_show_hide_log('{$k}');" id="show_hide_link_{$k}">Show details</a>

        {/if}

    </td>
    {if $main_transaction}
    <td>
        <a class="toggle_transaction_multiple" href="#"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
    </td>
    {/if}
</tr>
{if $main_transaction}
<tr>
    <td colspan="7">

        <input type="button" value="Look up payment (Get links)" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 'look_up_payment');" />

        {if $v.unserialized_transaction_response.links ne ""}


            {assign var="show_transaction_amount_field" value="N"}

            {foreach from=$v.unserialized_transaction_response.links item=link key=k_link}

                {if $link.rel eq "self"}
                    {*
                                <input type="button" value="Self" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 'self_transaction');" />
                    *}
                {elseif $link.rel eq "refund"}
                    {assign var="show_transaction_amount_field" value="Y"}
                    <input type="button" value="Refund transaction" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 'refund_transaction');" />
                    {*
                    {$lng.lbl_refund_transaction_txt} - not added yet br />
                    *}

                {elseif $link.rel eq "void"}
                    <input type="button" value="Void authorized transaction" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 'void_transaction');" />
                    {*
                     {$lng.lbl_void_transaction_txt} <br />
                    *}
                {elseif $link.rel eq "capture"}
                    {assign var="show_transaction_amount_field" value="Y"}
                    <input type="button" value="Capture selected authorized transaction" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 'capture_transaction');" />
                    {*
                    {$lng.lbl_capture_transaction_txt} <br />
                    *}
                {elseif $link.rel eq "reauthorize"}
                    {assign var="show_transaction_amount_field" value="Y"}
                    <input type="button" value="RE-authorize selected transaction" onclick="javascript: $('#order_transaction_id').val('{$v.id}'); submitForm(this, 're_authorize_transaction');" />
                    {*
                    {$lng.lbl_re_authorize_transaction_txt}
                    *}
                {/if}

            {/foreach}

            {if $show_transaction_amount_field eq "Y"}
                <input type="text" name="transaction_amount[{$v.id}]" id="transaction_amount_{$v.id}" size="6" value="{$v.transaction_amount}" />
            {/if}

        {/if}

    </td>
</tr>
{/if}
<tr><td colspan="4"><hr /></td></tr>