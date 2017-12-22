{capture name=product_splash_upload}
    <form id="product_splash_upload_form" method="post" name="product_splash_upload_form" enctype="multipart/form-data">
        <input id="splash_form_mode" type="hidden" name="mode" value="add_splash">
        <table cellspacing="0" cellpadding="3" width="100%">
            <tr class="TableHead">
                <td width="50"</td>
                <td width="170" class="DataTable">{$lng.lbl_image}</td>
                <td width="200" nowrap="nowrap" class="DataTable">Splash name</td>
                <td width="*" nowrap="nowrap" class="DataTable">Comment</td>
                <td width="50" nowrap="nowrap" class="DataTable">Active</td>
            </tr>
            {if $splashes}
                {foreach from=$splashes item=splash}
                    <tr{cycle values=", class='TableSubHead'"}>
                        <td><input name="splash_scheckbox[{$splash->id}]" type="checkbox"/></td>
                        <td align="center" class="DataTable">
                            <img style="cursor: pointer" class="splash_image" src="{$xcart_web_dir}{$splash->image_path}" alt=""/>
                            <input name="splash_file[{$splash->id}]" type="file" style="display: none;" />
                        </td>
                        <td class="DataTable">
                            <input style="width:99%;" type="text" name="splash_name[{$splash->id}]" value="{$splash->splash_name}"/>
                        </td>
                        <td class="DataTable">
                            <input style="width:99%;" type="text" name="splash_comment[{$splash->id}]" value="{$splash->comment}"/>
                        </td>
                        <td align="center">
                            <select name="splash_active[{$splash->id}]">
                                <option value="N" {if $splash->active == 'N'}selected="selected"{/if}>N</option>
                                <option value="Y" {if $splash->active == 'Y'}selected="selected"{/if}>Y</option>
                            </select>
                        </td>
                    </tr>
                {/foreach}
                <tr>
                    <td colspan="4">
                        <input id="update_splash_button" type="button" value="Update selected"/>&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
            {else}
                <tr>
                    <td colspan="4" align="center">{$lng.txt_no_images}</td>
                </tr>
            {/if}
            <tr>
                <td colspan="4">
                    <br/><br/>
                    {include file="main/subheader.tpl" title='Add new splash image'}

                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table cellspacing="0" cellpadding="3" width="100%">
                        <tr class="TableHead">
                            <td width="50"></td>
                            <td width="170" class="DataTable">{$lng.lbl_image}</td>
                            <td width="200" nowrap="nowrap" class="DataTable">Splash name</td>
                            <td width="*" nowrap="nowrap" class="DataTable">Comment</td>
                            <td width="50" nowrap="nowrap" class="DataTable">Active</td>
                        </tr>
                        <tr id="upload_row_1">
                            <td colspan="2">
                                <input name="splash_scheckbox[0]" type="checkbox" checked="checked" style="display: none;"/>
                                <span id="upload_fname_1"></span><input type="file" size="25" name="splash_file[0]" id="userfile_1"/>
                            </td>
                            <td>
                                <input style="width:99%;" name="splash_name[0]" type="text" />
                            </td>
                            <td>
                                <input style="width:99%;" name="splash_comment[0]" type="text" />
                            </td>
                            <td align="center">
                                <select name="splash_active[0]">
                                    <option value="N">N</option>
                                    <option value="Y">Y</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <div id="cidev_popup_poptions1" style="display: block;">
                        <input type="submit" value="{$lng.lbl_upload|strip_tags:false|escape}"/>
                    </div>
                </td>
            </tr>
        </table>
    </form>
{/capture}

{$smarty.capture.product_splash_upload}

{literal}
    <script type="text/javascript">
        $('img.splash_image').click(function(){
            $(this).hide().next('input[type=file]').click().show();
        });
        $('#update_splash_button').click(function() {
            $('#splash_form_mode').val('update_splashes').closest('form').submit();
        })
    </script>
{/literal}