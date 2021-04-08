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
            {if $row|in_array:['AUTHORIZATION VOIDED RATE','REFUNDED RATE', 'ABANDONED RATE']}
                {foreach $orders_rates[$row]|array_keys as $col}
                    <td class="borderb-gray" align="center">
                        {if $orders_rates[$row][$col]['total'] !== null}
                            {$orders_rates[$row][$col]['total']}%
                        {/if}
                    </td>
                    <td class="borderr-black" align="center">
                        {if $orders_rates[$row][$col]['count'] !== null}
                            {$orders_rates[$row][$col]['count']}%
                        {/if}
                    </td>
                {/foreach}
            {else}
                {foreach $orders[$row]|array_keys as $col}
                    <td class="borderb-gray" align="center">
                        {$orders[$row][$col]->getTotal()|site_currency}
                    </td>
                    <td class="borderr-black" align="center">
                        {$orders[$row][$col]->getCount()}
                    </td>
                {/foreach}
            {/if}
        </tr>
    {/foreach}
</table>