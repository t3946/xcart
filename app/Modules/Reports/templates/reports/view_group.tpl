{extends 'reports/layouts/report_layout.tpl'}

{block 'heading'}
    <h1 align="center">Order report
        {if $form_data.order.date}
            {$form_data.order.date}
        {else}
            All dates
        {/if}</h1>
{/block}

{block 'content'}
    <table>
    {foreach $report_data as $key_first => $first_group}
        <tr>
            <td>{$key_first}</td>
            {foreach $first_group as $report_arr}
                <tr>
                {foreach $report_arr as $report_d}
                    <td>{$report_d}</td>
                {/foreach}
                </tr>
            {/foreach}
        </tr>
    {/foreach}
    </table>
{/block}

