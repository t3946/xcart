{extends 'amazon/layouts/amazon_layout.tpl'}

{block 'heading'}
    <h1 align="center">Amazon reordering</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Calculate Amazon Shipping Plan'}
        {include 'amazon/reordering/_amazon_loading.tpl'}
    {/smarty_admin_block}

{/block}