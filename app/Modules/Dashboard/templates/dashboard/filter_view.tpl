{extends 'dashboard/layouts/search_layout.tpl'}

{block 'heading'}
    <h1 align="center">
        {$model->name} ({$pager->getTotal()})
    </h1>
{/block}
