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
            {set $group_total = []}

            {if $first_group@first}
                <tr class="report-header">
                    <td>{$group_names[$form_data.report.group_settings[1]].name}</td>
                    {foreach $first_group as $report_arr}
                        {foreach $report_arr as $r_key => $report_d}
                            <td>
                                {if $aggregates_names[$r_key]}
                                    {$aggregates_names[$r_key].name}
                                {else}
                                    {$group_names[$r_key].name}
                                {/if}
                            </td>
                        {/foreach}
                        {break}
                    {/foreach}

                </tr>
            {/if}

            {foreach $first_group as $report_arr}
                {foreach $report_arr as $key => $report_d last=$last}
                    {if $last}
                        {set $total_prefix = $aggregates_names[$key].prefix}
                        {set $total_suffix = $aggregates_names[$key].suffix}
                        {set $sum_group += $report_d}
                    {/if}
                    {set $group_total[$key][] = $report_d}
                {/foreach}
            {/foreach}

            {foreach $first_group as $report_arr}
                <tr class="{cycle ["even", "odd"] index=$group_index} {if $report_arr@first}first{/if}">
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
                                {if ($aggregates_names[$d_key].prefix)}
                                    {$aggregates_names[$d_key].prefix}{$report_d|formatprice:",":"."}
                                {else}
                                    {$report_d}
                                {/if}
                                {if $aggregates_names[$d_key].suffix}
                                    {$aggregates_names[$d_key].suffix}
                                {/if}
                            {else}
                                {$report_d}
                            {/if}
                        </td>
                    {/foreach}
                </tr>
                {if $first_group@last && $is_aggregate}
                    <tr class="subtotal {cycle ["even", "odd"] index=$group_index}">
                        <td class="border-off"></td>
                        {foreach $report_arr as $d_key => $report_d}
                            {if ($d_key in keys $aggregates_names)}
                                {set $is_aggregate = true}
                            {else}
                                {set $is_aggregate = false}
                            {/if}
                            <td class="{if $is_aggregate}align-right{else}border-off{/if}">
                                {if $is_aggregate && $group_total[$d_key]}
                                    {if ($aggregates_names[$d_key].prefix)}
                                        {$aggregates_names[$d_key].prefix}{$group_total[$d_key]|aggregate_function:$aggregates_names[$d_key].function|formatprice:",":"."}
                                    {else}
                                        {$group_total[$d_key]|aggregate_function:$aggregates_names[$d_key].function}
                                    {/if}
                                {/if}
                            </td>
                        {/foreach}
                    </tr>
                    <tr>
                        <td class="border-off">&nbsp;</td>
                    </tr>
                {/if}
            {/foreach}

            {set $sum_total += $sum_group}

        {/foreach}
    </table>

    <div class="report-footer">
        <div class="row">
            <div class="columns large-11 total-label">
                <span>Total sales volume:</span>
            </div>
            <div class="columns large-1 total-value align-right">
                <span>{$total_prefix}{if $total_prefix}{$sum_total|formatprice:",":"."}{else}{$sum_total}{/if}{$total_suffix}</span>
            </div>
        </div>
        {if $form_data.report.comment}
            <div class="row report-comment">
                <div class="columns large-12">
                    {raw $form_data.report.comment|nl2br}
                </div>
            </div>
        {/if}
    </div>



{/block}

