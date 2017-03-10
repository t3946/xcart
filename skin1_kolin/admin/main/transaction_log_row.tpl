<tr>
    <td>{$v.payment_method}</td>
    <td>{$v.date|date_format:'%d-%b-%Y<br />%H:%M:%S'}</td>
    <td>{$v.firstname} ({$v.login})</td>
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
</tr>
<tr><td colspan="4"><hr /></td></tr>