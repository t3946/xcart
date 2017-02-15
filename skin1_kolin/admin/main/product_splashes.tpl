{capture name=product_splash_upload}
    <form id="product_splash_upload_form" method="post" name="product_splash_upload_form" enctype="multipart/form-data">
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
                            <input style="width:99%;" type="text" name="splash_comment[{$splash->id}]" value="{$splash->splash_comment}"/>
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
                        <input type="button" value="Update selected"
                               onclick="document.uploadform.mode.value='update_splashes';document.uploadform.submit();"/>&nbsp;&nbsp;&nbsp;
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
                                <span id="upload_fname_1"></span><input type="file" size="25" name="splash_file" id="userfile_1"/>
                            </td>
                            <td>
                                <input style="width:99%;" name="splash_name" type="text" />
                            </td>
                            <td>
                                <input style="width:99%;" name="splash_comment" type="text" />
                            </td>
                            <td align="center">
                                <select name="splash_active">
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
    </script>
{/literal}