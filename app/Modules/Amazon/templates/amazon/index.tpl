{extends 'amazon/layouts/amazon_layout.tpl'}

{block 'heading'}
    <h1 align="center">Amazon reordering</h1>
{/block}


{block 'content'}
    {smarty_admin_block name='Create Amazon reorder batch'}
        {include 'amazon/_errors.tpl'}
        {include 'amazon/reordering/_amazon_loading.tpl'}
    {/smarty_admin_block}

{/block}