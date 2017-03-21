{extends 'reports/layouts/search_layout.tpl'}

{block 'heading'}
    <h1 align="center">Report {if $model->getIsNewRecord()}create{else}"{$model}" edit{/if}.</h1>
{/block}

{block 'report'}
    {smarty_admin_block name='Create order report'}
        {include 'reports/admin/_reports_block_edit.tpl'}
    {/smarty_admin_block}
{/block}

{block 'js-head'}
    {parent}
    <script src="/static/vendors/jquery.shapeshift-master/core/jquery.shapeshift.min.js" type="text/javascript"></script>
{/block}

{block 'js'}

{/block}
