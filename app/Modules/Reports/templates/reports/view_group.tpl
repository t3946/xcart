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
        {set $totals = []}
        {foreach $report_data as $key_domain => $first_group index=$group_index last=$last_domain}
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
                    {if $report_d is not empty}
                        {set $group_total[$key][] = $report_d}
                    {/if}
                    {if ($key not in keys $aggregates_names)}
                        {set $last_group_item = $key}
                    {/if}
                {/foreach}
            {/foreach}

            {foreach $first_group as $report_arr}
                <tr class="{cycle ["even", "odd"] index=$group_index} {if $report_arr@first}first{/if}">
                    {if $first_group@first}
                        <td rowspan="{count($first_group)}">
                            {$key_domain}
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
                                {if ($report_d && ($aggregates_names[$d_key].prefix || $aggregates_names[$d_key].suffix))}
                                    {$report_d|formatprice:",":"."|format_value:$aggregates_names[$d_key].prefix:$aggregates_names[$d_key].suffix}
                                {else}
                                    {$report_d}
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
                                <b>
                                {if $last_group_item == $d_key}
                                    {$group_names[$form_data.report.group_settings[1]].name} totals
                                {else}
                                    {if $is_aggregate && $group_totals[$form_data.report.group_settings[1]][$key_domain][0].$d_key}
                                        {if ($aggregates_names[$d_key].prefix || $aggregates_names[$d_key].suffix)}
                                            {$group_totals[$form_data.report.group_settings[1]][$key_domain][0].$d_key|formatprice:",":"."|format_value:$aggregates_names[$d_key].prefix:$aggregates_names[$d_key].suffix}
                                        {else}
                                            {$group_totals[$form_data.report.group_settings[1]][$key_domain][0].$d_key}
                                        {/if}

                                    {/if}
                                {/if}
                                </b>
                            </td>
                        {/foreach}
                    </tr>
                    <tr class="delimiter">
                        <td class="border-off">&nbsp;</td>
                    </tr>
                {/if}
            {/foreach}
        {/foreach}
        <tr>
            <td class="total-label border-off">All {$group_names[$form_data.report.group_settings[1]].name|lower}s grand total:</td>
        {foreach $report_arr as $d_key => $report_d}
            {if ($d_key in keys $aggregates_names)}
                {set $is_aggregate = true}
            {else}
                {set $is_aggregate = false}
            {/if}
            <td class="{if $is_aggregate}align-right {/if}border-off total-label">
                {set $report_total = reset($group_totals['report_total'])}
                {if ($aggregates_names[$d_key].prefix || $aggregates_names[$d_key].suffix)}
                    {$report_total[0].$d_key|formatprice:",":"."|format_value:$aggregates_names[$d_key].prefix:$aggregates_names[$d_key].suffix}
                {else}
                    {$report_total[0].$d_key}
                {/if}
            </td>
        {/foreach}
        </tr>
    </table>

    <div class="report-footer">
        {if $form_data.report.comment}
            <div class="row report-comment">
                <div class="columns large-12">
                    {raw $form_data.report.comment|nl2br}
                </div>
            </div>
        {/if}
    </div>

{/block}

