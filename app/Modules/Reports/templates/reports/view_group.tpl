{extends 'reports/layouts/report_layout.tpl'}

{block 'before-content'}
    <div class="order-report-header">
        Order report:
        {if $form_data.order.date}
            {$form_data.order.date}
        {else}
            All dates
        {/if}

    </div>
{/block}

{block 'main'}
    <div class="order-report-wrapper" style="width:960px; margin: 0 auto">
    {parent}
    </div>
{/block}

{block 'content'}
    <table width="100%">
        {set $sum_total = 0}
        {foreach $report_data as $key_first => $first_group}
            {set $sum_group = 0}

            {if $first_group@first}
                <tr>
                    <td>{$group_names[$form_data.report.group_settings[1]]}</td>
                    {foreach $first_group as $report_arr}
                        {foreach $report_arr as $r_key => $report_d}
                            <td>
                                {if ($report_d@last)}
                                    {$aggregates_names[$r_key]}
                                {else}
                                    {$group_names[$r_key]}
                                {/if}
                            </td>
                        {/foreach}
                        {break}
                    {/foreach}
                    <td>
                    </td>
                </tr>
            {/if}

            {foreach $first_group as $report_arr}
                {foreach $report_arr as $report_d last=$last}
                    {set $sum_group += $report_d}
                {/foreach}
            {/foreach}

            {foreach $first_group as $report_arr}
                <tr>
                    {if $key_first != $group_field}
                        <td rowspan="{count($first_group)}">
                            {$key_first}
                        </td>
                    {/if}
                    {foreach $report_arr as $report_d}
                        <td>{$report_d}</td>
                    {/foreach}
                    {if $key_first != $group_field}
                        <td rowspan="{count($first_group)}">
                            {$sum_group}
                        </td>
                    {/if}
                </tr>
                {set $group_field = $key_first}
            {/foreach}

            {set $sum_total += $sum_group}

            {if $first_group@last}
                <tr>
                    <td><b>Total sales volume</b></td>
                    <td>{$sum_total}</td>
                </tr>
            {/if}
        {/foreach}
    </table>
{/block}

