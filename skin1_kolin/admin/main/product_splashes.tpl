{capture name=product_splash_upload}
    <form method="post" name="product_splash_upload_form" enctype="multipart/form-data">
        <table cellspacing="0" cellpadding="3" width="100%">
            <tr class="TableHead">
                <td width="30" class="DataTable">&nbsp;</td>
                <td width="200" class="DataTable">{$lng.lbl_image}</td>
                <td width="200" nowrap="nowrap" class="DataTable">Splash name</td>
                <td width="*" nowrap="nowrap" class="DataTable">Comment</td>
                <td width="50" nowrap="nowrap" class="DataTable">Active</td>
            </tr>
            {if $splashes}
                {foreach from=$splashes item=splash}
                    <tr{cycle values=", class='TableSubHead'"}>
                        <td width="15" class="DataTable"><input type="checkbox" value="Y" name="iids[{$images[image].imageid}]"/></td>
                        <td align="center" class="DataTable">
                            <img src="{$xcart_web_dir}{$splash->image_path}"alt=""/>
                        </td>
                        <td class="DataTable">
                            <input style="width:99%;" type="text" name="splash_name[{$splash->id}]" value="{$splash->splash_name}"/>
                        </td>
                        <td class="DataTable">
                            <input style="width:99%;" type="text" name="splash_comment[{$splash->id}]" value="{$splash->splash_comment}"/>
                        </td>
                        <td class="DataTable">
                            <select name="splash_active">
                                <option value="N">N</option>
                                <option value="Y">Y</option>
                            </select>
                        </td>
                    </tr>
                {/foreach}
                <tr>
                    {if $geid ne ''}
                        <td width="15" class="TableSubHead">&nbsp;</td>
                    {/if}
                    <td colspan="6">
                        <input type="hidden" name="fields[thumbnail]" value="" id="det_thumb_field"/>
                        <input type="button" value="{$lng.lbl_update|strip_tags:false|escape}"
                               onclick="document.uploadform.mode.value='update_availability';document.uploadform.submit();"/>&nbsp;&nbsp;&nbsp;
                        <input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}"
                               onclick="javascript: document.uploadform.mode.value='product_images_delete'; document.uploadform.submit();"/>
                    </td>
                </tr>
            {else}
                <tr>
                    {if $geid ne ''}
                        <td width="15" class="TableSubHead">&nbsp;</td>
                    {/if}
                    <td colspan="6" align="center">{$lng.txt_no_images}</td>
                </tr>
            {/if}
            <tr>
                {if $geid ne ''}
                    <td width="15" class="TableSubHead">&nbsp;</td>
                {/if}
                <td colspan="7">
                    <br/><br/>

                    {include file="main/subheader.tpl" title='Add new splash image'}
                    <script type="text/javascript">
                        <!--
                        var not_image = 'avail';
                        -->
                    </script>

                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table cellspacing="0" cellpadding="3" width="100%">
                        <tr class="TableHead">
                            <td width="30" class="DataTable"></td>
                            <td width="200" class="DataTable">{$lng.lbl_image}</td>
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