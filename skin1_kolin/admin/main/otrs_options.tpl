<form name="osnotificform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="OTRS_options">
    <input type="hidden" name="mode" value="">

    <table cellpadding="3" cellspacing="1" width="100%">

        <tr>
            <td width="40%">OTRS passphrase:</td>
            <td>
                <input type="text" name="OTRS_passphrase" value="{$otrs_options.OTRS_passphrase}" style="width: 98%;"/>
            </td>
        </tr>

        <tr>
            <td colspan="2">New mail notification options</td>
        </tr>

        <tr>
            <td>Order 'Attention tag' to add:</td>
            <td>
                {if $attention_tags_values ne ""}
                    <select name="status_id">
                        <option value="0"></option>
                        {foreach from=$attention_tags_values item=v key=k}
                            <option value="{$v.status_id}"
                                    {if $otrs_options.status_id eq $v.status_id}selected="selected"{/if}>{$v.status}</option>
                        {/foreach}
                    </select>
                {/if}
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <table id="rules_table" width="100%">
                    <tr>
                        <td colspan="6">
                            <table cellpadding="0" cellspacing="0" width="100%" class="SubHeader">

                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="color: #000000;"><B>'New OTRS message' rules</B></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="SubHeaderLine"><img alt="" class="Spc"
                                                                               src="{$SkinDir}/images/spacer.gif"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>

                            </table>
                        </td>

                    </tr>
                    <tr class="TableHead">
                        <td>Rule ID</td>
                        <td nowrap="nowrap">CB status</td>
                        <td nowrap="nowrap">DC status</td>
                        <td nowrap="nowrap">BD status</td>
                        <td nowrap="nowrap">Include/Exclude</td>
                        <td>Delete</td>
                    </tr>
                    {foreach from=$otrs_new_message_rules item=rules name=rules}
                        <tr>

                            <td class="td_rule_id" align="center">{$rules.rule_id}</td>
                            <td>
                                <select class="cb_status" style="width:100%;" name="cb_status[{$rules.rule_id}]">
                                    <option value="*">Any</option>
                                    {foreach from=$order_statuses item=ostatus}
                                        {if ($ostatus.type == 'CB')}
                                            <option {if $ostatus.code == $rules.cb_status}selected="selected"{/if}
                                                    value="{$ostatus.code}">{$ostatus.name}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </td>
                            <td><select class="dc_status" style="width:100%;" name="dc_status[{$rules.rule_id}]">
                                    <option value="*">Any</option>
                                    {foreach from=$order_statuses item=ostatus}
                                        {if ($ostatus.type == 'DC')}
                                            <option {if $ostatus.code == $rules.dc_status}selected="selected"{/if}
                                                    value="{$ostatus.code}">{$ostatus.name}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </td>
                            <td><select class="bd_status" style="width:100%;" name="bd_status[{$rules.rule_id}]">
                                    <option value="*">Any</option>
                                    {foreach from=$order_statuses item=ostatus}
                                        {if ($ostatus.type == 'BD')}
                                            <option {if $ostatus.code == $rules.bd_status}selected="selected"{/if}
                                                    value="{$ostatus.code}">{$ostatus.name}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </td>
                            <td>
                                <select class="select_action" style="width:100%;" name="action[{$rules.rule_id}]">
                                    <option {if $rules.action == 'Include'} selected="selected" {/if} value="Include">
                                        Include
                                    </option>
                                    <option {if $rules.action == 'Exclude'} selected="selected" {/if} value="Exclude">
                                        Exclude
                                    </option>
                                </select>
                            </td>
                            <td align="center" class="crosscell"><input class="delete_checkbox" type="checkbox" name="rules_to_delete[{$rules.rule_id}]"/>
                                {if $smarty.foreach.rules.last}<a class="add_rule" href="javascript: void(0);"><img src="{$ImagesDir}/plus.gif" /></a>{/if}
                            </td>
                        </tr>
                    {/foreach}</table>
            </td>
        </tr>


    </table>
    <br/>
    <input type="button" value="{$lng.lbl_update|strip_tags:false|escape}"
           onclick="javascript: submitForm(this, 'update');"/>

</form>
{literal}
<script>
    $( document ).ready(function() {
        $("#rules_table").delegate("a.add_rule", "click", function() {
            var click_row = $(this).parent().parent();
            var clone_row = click_row.clone();
            $('.add_rule',click_row).remove();
            $(".td_rule_id",clone_row).html('');
            $("select.cb_status",clone_row).val('*').attr('name','cb_status[]');
            $("select.dc_status",clone_row).val('*').attr('name','dc_status[]');
            $("select.bd_status",clone_row).val('*').attr('name','bd_status[]');
            $("select.select_action",clone_row).attr('name','action[]');
            $("input.delete_checkbox",clone_row).remove();
            if ($('.delete_checkbox',click_row).length == 0) {
                var img = $("<img/>").attr('src','{/literal}{$ImagesDir}{literal}/minus.gif');
                var minus = $('<a/>').attr('href','javascript: void(0);').addClass('remove_rule').append(img);
                $(".crosscell",click_row).append(minus);
            }
            click_row.after(clone_row);
        }).delegate("a.remove_rule", "click", function() {
            var click_row = $(this).parent().parent();
            click_row.remove();
        });
    });
</script>
{/literal}