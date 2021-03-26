<table cellpadding="3" cellspacing="0" width="100%">

    <tr class="TableHead">
        <td class="borderr-black" width="40%">Status</td>
        <td class="borderr-black" colspan="2" align="center">Last 24 hours</td>
        <td class="borderr-black" colspan="2" nowrap="nowrap" align="center">Last 7 days</td>
        <td class="borderr-black" colspan="2" nowrap="nowrap" align="center">Last 30 days</td>
    </tr>
    {foreach $orders|array_keys as $row}
        <tr class="{cycle ['SectionBox','TableSubHead']}">
            <td class="borderr-black" align="right"><b>{$row}</b></td>
            {foreach $orders[$row]|array_keys as $col}
                <td class="borderb-gray" align="center">
                    {if $orders[$row][$col] !== null}
                        {if $orders[$row][$col]['rate']}
                            {$orders[$row][$col]['rate']}%
                        {else}
                            {$orders[$row][$col]['total']|site_currency}
                        {/if}
                    {/if}
                </td>
                <td class="borderr-black" align="center">
                    {if $orders[$row][$col] !== null}
                        {$orders[$row][$col]['count']}
                    {/if}
                </td>
            {/foreach}
        </tr>
        {if $row|in_array:['AUTHORIZATION VOIDED','REFUNDED']}
            <tr class="{cycle ['SectionBox','TableSubHead']}">
                <td class="borderr-black" align="right"><b>{$row} RATE</b></td>
                {foreach $orders_rates[$row]|array_keys as $col}
                    <td class="borderb-gray" align="center">
                        {if $orders_rates[$row][$col] !== null}
                            {$orders_rates[$row][$col]}%
                        {/if}
                    </td>
                    <td class="borderr-black" align="center">
                    </td>
                {/foreach}
            </tr>
        {/if}
    {/foreach}
</table>