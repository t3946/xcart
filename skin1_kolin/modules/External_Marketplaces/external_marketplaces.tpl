<form name="osnotificform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="External_marketplaces">
    <input type="hidden" name="mode" value="">

    <br/>
    <B>External marketplaces</B>
    <hr/>

    <table cellpadding="3" cellspacing="1" width="100%" id="external_marketplaces">

        <tr class="TableHead">
            <th></th>
            <th>Marketplace name</th>
            <th>Processor class</th>
            <th>Active</th>
            <th></th>
        </tr>

        {if !empty($external_marketplaces)}
            {foreach from=$external_marketplaces item=oMarketPlace key=k name=marketplaces}
                <tr data-marketplace-id="{$oMarketPlace->getMarketPlaceId()}">
                    <td align="center" class="crosscell">
                        <a href="javascript: void(0);" class="toggle_storefront plus"><img src="{$ImagesDir}/plus.gif"></a>
                        <a href="javascript: void(0);" style="display:none;" class="toggle_storefront minus"><img src="{$ImagesDir}/minus.gif"></a>
                    </td>
                    <td align="center">
                        <input class="marketplace_name" style="width:98%;" type="text"
                                              name="external_marketplace[{$oMarketPlace->getMarketPlaceId()}][marketplace_name]"
                                              value="{$oMarketPlace->getMarketPlaceName()}"/>
                    </td>
                    <td align="center">
                        <input class="processor_class" style="width:98%;" type="text"
                                              name="external_marketplace[{$oMarketPlace->getMarketPlaceId()}][processor_class]"
                                              value="{$oMarketPlace->getMarketPlaceProcessorClassName()}"/>
                    </td>
                    <td align="center">
                        <select class="marketplace_active" style="width:98%;"
                                name="external_marketplace[{$oMarketPlace->getMarketPlaceId()}][active]">
                            {html_options values=$oMarketPlace->getMarketPlaceStatusesValues() output=$oMarketPlace->getMarketPlaceStatusesValues() selected=$oMarketPlace->getMarketPlaceStatus()}
                        </select>
                    </td>
                    <td align="center" class="crosscell">
                        {if $smarty.foreach.marketplaces.last}
                            <a href="javascript: void(0);" class="add_rule"><img src="{$ImagesDir}/plus.gif"></a>
                        {/if}
                    </td>
                </tr>
                <tr style="display:none;">
                    <td>&nbsp;</td>
                    <td colspan="3">
                        <table width="100%" cellspacing="1" cellpadding="2">
                            <tr class="TableHead">
                                <td width="200">Storefront</td>
                                <td width="60">Inventory batch</td>
                                <td width="60">Products batch</td>
                                <td width="200">Endpoint</td>
                                <td>P1(Merchant id)</td>
                                <td>P2(Client id)</td>
                                <td>FTP Domain</td>
                                <td>FTP Login</td>
                                <td>FTP Password</td>
                                <td>FTP Path</td>
                                <td>File Suffix</td>
                                <td>Delete</td>
                            </tr>
                            {if ($oMarketPlace->getStoreFrontMarketplaces())}
                            {foreach from=$oMarketPlace->getStoreFrontMarketplaces() item=oStoreFrontMarketPlace }
                            <tr data-marketplace-id="{$oMarketPlace->getMarketPlaceId()}">
                                <td align="center">
                                    <select autocomplete="off" class="external_storefront_storefrontid"  style="width:98%;" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}][storefront_id]">
                                        <option value=""></option>
                                        {html_options values=$external_storefronts->getStoreFrontsIds() output=$external_storefronts->getStoreFrontsDomains() selected=$oStoreFrontMarketPlace->getStoreFrontId()}
                                    </select>
                                </td>
                                <td align="center">
                                    <input class="external_storefront_inventory_batch_count" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}][inventory_batch_count]" type="text" size="4" value="{$oStoreFrontMarketPlace->getInventoryBatchCount()}"/>
                                </td>
                                <td align="center">
                                    <input class="external_storefront_products_batch_count" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}][products_batch_count]" type="text" size="4" value="{$oStoreFrontMarketPlace->getProductsBatchCount()}"/>
                                </td>
                                <td align="center">
                                    <input class="external_storefront_P0" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}][P0]" type="text" size="30" value="{$oStoreFrontMarketPlace->getP0()}"/>
                                </td>
                                <td align="center">
                                    <input class="external_storefront_P1" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}][P1]" type="text" size="15" value="{$oStoreFrontMarketPlace->getP1()}"/>
                                </td>
                                <td align="center">
                                    <input class="external_storefront_P2" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}][P2]" type="text" size="15" value="{$oStoreFrontMarketPlace->getP2()}"/>
                                </td>
                                <td align="center">
                                    <input class="external_storefront_ftp_domain" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}][ftp_domain]" type="text" size="15" value="{$oStoreFrontMarketPlace->getFTPDomain()}"/>
                                </td>
                                <td align="center">
                                    <input class="external_storefront_ftp_login" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}][ftp_login]" type="text" size="15" value="{$oStoreFrontMarketPlace->getFTPLogin()}"/>
                                </td>
                                <td align="center">
                                    <input class="external_storefront_ftp_password" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}][ftp_password]" type="text" size="15" value="{$oStoreFrontMarketPlace->getFTPPassword()}"/>
                                </td>
                                <td align="center">
                                    <input class="external_storefront_ftp_path" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}][ftp_path]" type="text" size="15" value="{$oStoreFrontMarketPlace->getFTPPath()}"/>
                                </td>
                                <td align="center">
                                    <input class="external_storefront_export_filename_suffix" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}][export_filename_suffix]" type="text" size="15" value="{$oStoreFrontMarketPlace->getFileNameSuffix()}"/>
                                </td>
                                <td>
                                    <input class="delete_checkbox" type="checkbox" name="external_storefront_marketplace_to_delete[{$oMarketPlace->getMarketPlaceId()}][{$oStoreFrontMarketPlace->getStoreFrontId()}]"/>
                                    <a href="javascript: void(0);" class="add_storefront"><img src="{$ImagesDir}/plus.gif"></a>
                                </td>
                            </tr>
                            {/foreach}
                            {else}
                                <tr data-marketplace-id="{$oMarketPlace->getMarketPlaceId()}">
                                    <td align="center">
                                        <select autocomplete="off" class="external_storefront_storefrontid"  style="width:98%;" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][0][storefront_id]">
                                            <option value=""></option>
                                            {html_options values=$external_storefronts->getStoreFrontsIds() output=$external_storefronts->getStoreFrontsDomains()}
                                        </select>
                                    </td>
                                    <td align="center">
                                        <input class="external_storefront_inventory_batch_count" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][0][inventory_batch_count]" type="text" size="4" value=""/>
                                    </td>
                                    <td align="center">
                                        <input class="external_storefront_products_batch_count" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][0][products_batch_count]" type="text" size="4" value=""/>
                                    </td>
                                    <td align="center">
                                        <input class="external_storefront_P0" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][0][P0]" type="text" size="30" value=""/>
                                    </td>
                                    <td align="center">
                                        <input class="external_storefront_P1" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][0][P1]" type="text" size="15" value=""/>
                                    </td>
                                    <td align="center">
                                        <input class="external_storefront_P2" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][0][P2]" type="text" size="15" value=""/>
                                    </td>
                                    <td align="center">
                                        <input class="external_storefront_ftp_domain" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][0][ftp_domain]" type="text" size="15" value=""/>
                                    </td>
                                    <td align="center">
                                        <input class="external_storefront_ftp_login" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][0][ftp_login]" type="text" size="15" value=""/>
                                    </td>
                                    <td align="center">
                                        <input class="external_storefront_ftp_password" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][0][ftp_password]" type="text" size="15" value=""/>
                                    </td>
                                    <td align="center">
                                        <input class="external_storefront_ftp_path" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][0][ftp_path]" type="text" size="15" value=""/>
                                    </td>
                                    <td align="center">
                                        <input class="external_storefront_export_filename_suffix" name="external_storefront_marketplace[{$oMarketPlace->getMarketPlaceId()}][0][export_filename_suffix]" type="text" size="15" value=""/>
                                    </td>
                                    <td>
                                        <input class="delete_checkbox" type="checkbox" name="external_storefront_marketplace_to_delete[{$oMarketPlace->getMarketPlaceId()}][0]"/>
                                        <a href="javascript: void(0);" class="add_storefront"><img src="{$ImagesDir}/plus.gif"></a>
                                    </td>
                                </tr>
                            {/if}
                        </table>
                    </td>
                </tr>
            {/foreach}
        {/if}

    </table>

    <div align="center">
        <input type="button" value="Save" onclick="javascript: submitForm(this, 'update');"/>
    </div>

</form>

{literal}
    <script>
        function cloneRow(click_row) {
            var clone_row = click_row.clone();
            return clone_row;
        }

        function clearRowValues(clone_row){
            $("select.external_storefront_storefrontid", clone_row).val('');
            $("input.external_storefront_inventory_batch_count", clone_row).val('');
            $("input.external_storefront_products_batch_count", clone_row).val('');
            $("input.external_storefront_P0", clone_row).val('');
            $("input.external_storefront_P1", clone_row).val('');
            $("input.external_storefront_P2", clone_row).val('');
            $("input.external_storefront_ftp_domain", clone_row).val('');
            $("input.external_storefront_ftp_login", clone_row).val('');
            $("input.external_storefront_ftp_password", clone_row).val('');
            $("input.external_storefront_ftp_path", clone_row).val('');
            $("input.external_storefront_ftp_path", clone_row).val('');
            $("input.external_storefront_export_filename_suffix", clone_row).val('');
        }

        function setRowMarketplaceAndStorefront(change_row, marketplace_id, storefront_id){
            $('.external_storefront_storefrontid', change_row).attr('name',"external_storefront_marketplace["+marketplace_id+"]["+storefront_id+"][storefront_id]");
            $('.external_storefront_inventory_batch_count', change_row).attr('name',"external_storefront_marketplace["+marketplace_id+"]["+storefront_id+"][inventory_batch_count]");
            $('.external_storefront_products_batch_count', change_row).attr('name',"external_storefront_marketplace["+marketplace_id+"]["+storefront_id+"][products_batch_count]");
            $('.external_storefront_P0', change_row).attr('name',"external_storefront_marketplace["+marketplace_id+"]["+storefront_id+"][P0]");
            $('.external_storefront_P1', change_row).attr('name',"external_storefront_marketplace["+marketplace_id+"]["+storefront_id+"][P1]");
            $('.external_storefront_P2', change_row).attr('name',"external_storefront_marketplace["+marketplace_id+"]["+storefront_id+"][P2]");
            $('.external_storefront_ftp_domain', change_row).attr('name',"external_storefront_marketplace["+marketplace_id+"]["+storefront_id+"][ftp_domain]");
            $('.external_storefront_ftp_login', change_row).attr('name',"external_storefront_marketplace["+marketplace_id+"]["+storefront_id+"][ftp_login]");
            $('.external_storefront_ftp_password', change_row).attr('name',"external_storefront_marketplace["+marketplace_id+"]["+storefront_id+"][ftp_password]");
            $('.external_storefront_ftp_path', change_row).attr('name',"external_storefront_marketplace["+marketplace_id+"]["+storefront_id+"][ftp_path]");
            $('.external_storefront_export_filename_suffix', change_row).attr('name',"external_storefront_marketplace["+marketplace_id+"]["+storefront_id+"][export_filename_suffix]");
            $('.delete_checkbox', change_row).attr('name',"delete_checkbox["+marketplace_id+"]["+storefront_id+"]");
        }

        $(document).ready(function () {
            $("#external_marketplaces").delegate("a.add_rule", "click", function () {
                var click_row = $(this).parent().parent();
                var clone_row = cloneRow(click_row);
                var clone_row_sub = cloneRow(click_row.next('tr'));
                var last_marketplace_id = 0;
                $('#external_marketplaces tr').each(function () {
                    if ($(this).data('marketplace-id'))
                        last_marketplace_id = Math.max($(this).data('marketplace-id'), last_marketplace_id);
                });
                var new_marketplace_id = last_marketplace_id + 1;
                $('.add_rule', click_row).remove();
                $(clone_row).attr('data-marketplace-id', new_marketplace_id);
                $("input.marketplace_name", clone_row).val('').attr('name', 'external_marketplace[' + new_marketplace_id + '][marketplace_name]');
                $("input.processor_class", clone_row).val('').attr('name', 'external_marketplace[' + new_marketplace_id + '][processor_class]');
                $("select.marketplace_active", clone_row).val('Y').attr('name', 'external_marketplace[' + new_marketplace_id + '][active]');

                clone_row_sub.attr('data-marketplace-id',0);
                clearRowValues(clone_row_sub);
                setRowMarketplaceAndStorefront(clone_row_sub, 0, 0);

                click_row.next('tr').after(clone_row_sub).after(clone_row);
            }).delegate("a.toggle_storefront", "click", function () {
                var click_row = $(this).parent().parent();
                if ($('.plus',click_row).is(':visible')) {
                    $('.plus', click_row).hide();
                    $('.minus', click_row).show();
                } else {
                    $('.minus', click_row).hide();
                    $('.plus', click_row).show();
                }
                click_row.next('tr').fadeToggle('medium');
            }).delegate("a.add_storefront", "click", function () {
                var click_row = $(this).parent().parent();
                var clone_row = cloneRow(click_row);
                clearRowValues(clone_row);

                click_row.next('tr').toggle();
                $('.add_storefront', click_row).remove();
                click_row.after(clone_row);
            }).delegate("select.external_storefront_storefrontid", "change", function () {
                var change_row = $(this).parent().parent(),
                marketplace_id = change_row.data('marketplace-id');
                setRowMarketplaceAndStorefront(change_row, marketplace_id, $(this).val());
                console.log($(this).val());
            });
        });
    </script>
{/literal}