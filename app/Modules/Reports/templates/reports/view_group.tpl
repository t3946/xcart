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

{/block}

