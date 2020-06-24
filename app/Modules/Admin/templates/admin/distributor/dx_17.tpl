{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block name=$section_title}
        <table id="distributor_section_id_17" width="100%" cellspacing="1" cellpadding="3">
            <tr>
                <td>
                    <b>All SKUs:</b> {$distributorModel->products->count()}
                </td>
            </tr>
            <tr>
                <td>
                    <b>Active SKUs:</b> {$distributorModel->products_active->count()}
                </td>
            </tr>
            <tr>
                <td>
                    <hr/>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Inventory feeds info:</b>
                </td>
            </tr>
            {if $distributorModel->feed_I_E->count()}
                {foreach $distributorModel->feed_I_E as $feed}
                <tr>
                    <td>
                        <b>Name:</b> {$feed} ({if $feed->enabled}Enabled{else}Disabled{/if})
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Storefront:</b> {$feed->site}
                    </td>
                </tr>

                <tr>
                    <td>
                        <b>Feed source:</b> {$feed->feed_source} {if $feed->feed_source_date}({$feed->feed_source_date}){/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Last update time:</b> {$feed->last_update_time|date_format:'%d-%b-%Y %H:%M'}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Average update period:</b> {$feed->getAverageUpdatePeriod()}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Last update items count:</b> {$feed->last_update_items_count}
                    </td>
                </tr>

                <tr>
                    <td>
                        <b>Feed fields last time processed:</b>
                        <br/>
                        <table>
                            <tr>
                                <td><B>Feed fields</B></td>
                                <td><B>Sample value</B></td>
                            </tr>
                            {foreach $feed->last_feed_fields as $ks=>$vs}
                                <tr>
                                    <td><B>{$ks}:</B></td>
                                    <td>{$vs}</td>
                                </tr>
                            {/foreach}
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
            {/foreach}
            {else}
                <tr>
                    <td style="color:red">
                        N/A
                    </td>
                </tr>
            {/if}
            <tr>
                <td>
                    <hr/>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Product feeds info:</b>
                </td>
            </tr>
            {if $distributorModel->feed_P_E->count()}
                {foreach $distributorModel->feed_P_E as $feed}
                <tr>
                    <td>
                        <b>Name:</b> {$feed} ({if $feed->enabled}Enabled{else}Disabled{/if})
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Storefront:</b> {$feed->site}
                    </td>
                </tr>

                <tr>
                    <td>
                        <b>Feed source:</b> {$feed->feed_source} {if $feed->feed_source_date}({$feed->feed_source_date}){/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Last update time:</b> {$feed->last_update_time|date_format:'%d-%b-%Y %H:%M'}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Average update period:</b> {$feed->getAverageUpdatePeriod()}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Last update items count:</b> {$feed->last_update_items_count}
                    </td>
                </tr>

                <tr>
                    <td>
                        <b>Feed fields last time processed:</b>
                        <br/>
                        <table>
                            <tr>
                                <td><B>Feed fields</B></td>
                                <td><B>Sample value</B></td>
                            </tr>
                            {foreach $feed->last_feed_fields as $ks=>$vs}
                                <tr>
                                    <td><B>{$ks}:</B></td>
                                    <td>{$vs}</td>
                                </tr>
                            {/foreach}
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
            {/foreach}
            {else}
                <tr>
                    <td style="color:red">
                        N/A
                    </td>
                </tr>
            {/if}
        </table>
    {/smarty_admin_block}
{/block}