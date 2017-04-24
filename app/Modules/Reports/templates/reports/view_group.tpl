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
    <div class="order-report-wrapper">
        {parent}
    </div>
{/block}

{block 'content'}
    <table class="report-table">
        {set $sum_total = 0}
        {foreach $report_data as $key_first => $first_group index=$group_index}
            {set $sum_group = 0}

            {if $first_group@first}
                <tr class="report-header">
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
                <tr class="{cycle ["even", "odd"] index=$group_index}">
                    {if $first_group@first}
                        <td rowspan="{count($first_group)}">
                            {$key_first}
                        </td>
                    {/if}
                    {foreach $report_arr as $d_key => $report_d}
                        {if ($d_key in keys $aggregates_names)}
                            {set $is_aggregate = true}
                        {else}
                            {set $is_aggregate = false}
                        {/if}
                        <td {if $is_aggregate} class="align-right"{/if}>
                            {if ($is_aggregate)}
                                ${$report_d|formatprice:",":"."}
                            {else}
                                {$report_d}
                            {/if}

                        </td>
                    {/foreach}
                    {if $first_group@first}
                        <td class="align-right" rowspan="{count($first_group)}">
                            ${$sum_group|formatprice:",":"."}
                        </td>
                    {/if}
                </tr>
            {/foreach}

            {set $sum_total += $sum_group}


        {/foreach}
    </table>
    <div class="row report_footer">
        <div class="columns large-11 total-label">
            <span>Total sales volume:</span>
        </div>
        <div class="columns large-1 total-value align-right">
            <span>${$sum_total|formatprice:",":"."}</span>
        </div>
    </div>

{/block}

