<form name="osnotificform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="PBX_options">
    <input type="hidden" name="mode" value="">

    <br />
    <B>External mrketplaces</B>
    <hr />

    <table cellpadding="3" cellspacing="1" width="100%">

        <tr>
            <th>extension</th>
            <th>anveo account (login)</th>
            <th>anveo password</th>
            <th>Linked xcart account</th>
            <th>delete</th>
        </tr>

        {if $pbx_options ne ""}
            {foreach from=$pbx_options item=v key=k}
                <tr>
                    <td><input type="text" name="pbx[{$v.id}][extension]" value="{$v.extension}" /></td>
                    <td><input type="text" name="pbx[{$v.id}][anveo_account]" value="{$v.anveo_account}" /></td>
                    <td><input type="text" name="pbx[{$v.id}][anveo_password]" value="{$v.anveo_password|escape}" /></td>
                    <td>
                        {if $v.linked_xcart_accounts ne ""}
                            {foreach from=$v.linked_xcart_accounts item=item key=key}
                                <a href="user_modify.php?user={$item.login}&usertype={$item.usertype}" target="_blank">{$item.login} ({$item.activity})</a><br />
                            {/foreach}
                        {/if}
                    </td>
                    <td><input type="checkbox" name="pbx[{$v.id}][delete]" value="Y" /></td>
                    </td>
                </tr>
            {/foreach}
        {/if}

    </table>

    <input type="button" value="add" onclick="javascript: submitForm(this, 'add');" />

    <div align="center">
        <input type="button" value="Save" onclick="javascript: submitForm(this, 'update');" />
    </div>

</form>
