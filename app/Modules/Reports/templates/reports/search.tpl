{extends 'reports/layouts/search_layout.tpl'}

{block 'heading'}
    {if $model}
        <h1 align="center">Report "{$model->name}"</h1>
    {else}
    <h1 align="center">Order reports</h1>
    {/if}
{/block}
