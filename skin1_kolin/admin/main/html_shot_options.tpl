<form name="html_shot_option_form" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="HTML_shots_options">
    <input type="hidden" name="mode" value="">

    <table cellpadding="3" cellspacing="1" width="100%">

        <tr>
            <td width="40%">Remove HTML-shot of the product when:</td>
            <td>
                <input type="text" name="remove_shot_after_days"
                       value="{$config.HTML_shots_options.remove_shot_after_days}" style="width: 10%;"/>
                days past since tracking number has been entered
            </td>
        </tr>
        <tr>
            <td width="40%"></td>
            <td>
                AND
            </td>
        </tr>
        <tr>
            <td width="40%"></td>
            <td>
                <input type="text" name="days_past_attn_tag_set"
                       value="{$config.HTML_shots_options.days_past_attn_tag_set}" style="width: 10%;"/>
                days past since the following Attn tags have been removed:
            </td>
        </tr>
        <tr>
            <td width="40%"></td>
            <td>
            </td>
        </tr>

        <tr>
            <td width="40%"></td>
            <td>
                {if $attention_tags_values ne ""}
                    <select name="attention_tags_after_remove[]" multiple="multiple" size="10">
                        {foreach from=$attention_tags_values item=v key=k}
                            <option value="{$v.status_id}"
                                    {if in_array($v.status_id,$attention_tags_selected)}selected="selected"{/if}>{$v.status}</option>
                        {/foreach}
                    </select>
                {/if}
            </td>
        </tr>
        <tr>
            <td width="40%"></td>
            <td>

            </td>
        </tr>
        <tr>
            <td width="40%"></td>
            <td>
                <input type="button" value="{$lng.lbl_update|strip_tags:false|escape}"
                       onclick="javascript: submitForm(this, 'update');"/>
            </td>
        </tr>
    </table>

</form>
