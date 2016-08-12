<link rel="stylesheet" href="{$SkinDir}/css/semantic/semantic.css">
<script src="{$SkinDir}/js/jquery-3.1.0.slim.min.js"></script>
<script src="{$SkinDir}/js/semantic/components/dropdown.js"></script>
<script src="{$SkinDir}/js/semantic/components/transition.min.js"></script>

    <input type="hidden" name="option" value="Secure_data">
    <input type="hidden" name="mode" value="">

    <table cellpadding="3" cellspacing="1" width="100%">
        {if !empty($aSecureData)}
            {foreach from=$aSecureData item=SecureData}
                <tr class="secure_data_table">
                    <td style="vertical-align: top;" width="10%"><b>Secure data:</b></td>
                    <td style="vertical-align: top;" width="65%">
                        <textarea style="min-height:150px; width: 98%;" name="secure_data[{$SecureData.id}]">{$SecureData.data}</textarea>
                        <input class="delete_data_checkbox" name="delete_data_checkbox[{$SecureData.id}]" type="checkbox"/>
                        <label style="position: relative; top: -3px;" for="delete_data_checkbox">delete data</label>
                    </td>
                    <td align="right" style="vertical-align: top;" width="25%">
                        <div style="text-align: center;"><b>Who can read this data</b></div>
                        <br/>
                        <select data-user-select-id="{$SecureData.id}" style="display: none;" class="ui dropdown search normal selectpicker" name="secure_data_use[{$SecureData.id}][]" multiple="">
                            {html_options options=$aCustomers selected=$aSecureDataLogins[$SecureData.id]}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table cellspacing="0" class="SubHeaderBlack">
                            <tbody>
                            <tr>
                                <td class="SubHeaderBlack"></td>
                            </tr>
                            <tr>
                                <td class="SubHeaderBlackLine"><img alt="" class="Spc" src="{$SkinDir}/images/spacer.gif"><br>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            {/foreach}
        {/if}
        <tr>
            <td>

            </td>
            <td>
                <div class="ui teal buttons">
                    <div id="update_button" class="ui button" style="border: 1px solid #808080;" onclick="javascript: submitForm($('form[name=processform]').get(0), 'update');" />{$lng.lbl_update|strip_tags:false|escape}</div>
                    <div style="border-color: #808080; border-image: none; border-style: solid solid solid none; border-width: 1px 1px 1px 0;" class="ui combo top right dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <div class="item" id="add_new_secure_data">Add new</div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

    </table>

<script>
    {literal}
    $(document).ready(function () {
        $(".selectpicker").dropdown().show();
        $('.combo.dropdown').dropdown();
        $("#add_new_secure_data").on('click','',function(){
            var tablerow = $(".secure_data_table").last(),
            tablerowdelimiter = tablerow.next('tr'),
            newtablerow = tablerow.clone(),
            selectuser = $('select',newtablerow);

            var new_id = selectuser.data('user-select-id');
            new_id++;
            selectuser.val('').attr('data-user-select-id', new_id).attr('name','secure_data_use['+new_id+'][]');
            $('textarea',newtablerow).val('').attr('name','secure_data['+new_id+']');
            $('.delete_data_checkbox',newtablerow).prop( "checked", false ).attr('name','delete_data_checkbox['+new_id+']');
            tablerowdelimiter.after(newtablerow,tablerowdelimiter.clone());
            $(".selectpicker",newtablerow).dropdown('clear').show();
        })
    });
    {/literal}
</script>