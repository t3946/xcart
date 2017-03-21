{extends 'dashboard/layouts/search_layout.tpl'}

{block 'content'}

    {smarty_admin_block name='Report options'}
        {include 'reports/layouts/_search_form_block.tpl'}
    {/smarty_admin_block}

    {block 'report'}
        {smarty_admin_block name='Order reports'}
            {include 'reports/layouts/_reports_block.tpl'}
        {/smarty_admin_block}
    {/block}

{/block}


{block 'menu_block'}

{/block}