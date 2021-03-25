<table cellpadding="3" cellspacing="0" width="100%">

    <tr class="TableHead">
        <td class="borderr-black">Status</td>
        <td class="borderr-black" colspan="2" align="center">Last 24 hours</td>
        <td class="borderr-black" colspan="2" nowrap="nowrap" align="center">Last 7 days</td>
        <td class="borderr-black" colspan="2" nowrap="nowrap" align="center">Last 30 days</td>
        <td class="borderr-black" colspan="2" nowrap="nowrap" align="center">Total / Up to date</td>
    </tr>
    {foreach $orders|array_keys as $row}
        <tr class="{cycle ['SectionBox','TableSubHead']}">
            <td class="borderr-black" align="right"><b>{$row}</b></td>
            {foreach $orders[$row]|array_keys as $col}
                    <td class="borderb-gray" align="center">
                        {if $orders[$row][$col] !== null}
                            {if $orders[$row][$col]|is_array}
                                {$.call.Modules.Order.Helpers.OrderAnalyticsHelper::ordersTotalSum($orders[$row][$col])|site_currency}
                            {else}
                                {$orders[$row][$col]} %
                            {/if}
                        {/if}
                    </td>
                    <td class="borderr-black" align="center">
                        {if $orders[$row][$col] !== null}
                            {if $orders[$row][$col]|is_array}
                                {$orders[$row][$col]|count}
                            {/if}
                        {/if}
                    </td>
            {/foreach}
        </tr>
    {/foreach}
</table>