{extends 'dashboard/layouts/search_layout.tpl'}

{block 'heading'}
    <h1 align="center">
        {$model->name} ({$pager->getTotal()})
    </h1>

    {if $modify}
        <h2 align="center" style="color: darkred;">
            Attention! This list is narrowed by certain distributor orders only
        </h2>
    {/if}
{/block}
