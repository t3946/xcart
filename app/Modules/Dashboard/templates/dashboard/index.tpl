{extends 'dashboard/layouts/menu_layout.tpl'}
{block 'heading'}
    <h1 align="center">Filters list.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='My dashboard'}

        {include 'dashboard/dashboard_group.tpl' models=$myModels}
    {/smarty_admin_block}

    {smarty_admin_block name='Order dashboard'}
        {include 'dashboard/dashboard_group.tpl' models=$models|get_filtered:null group=null title='Not in group'}

        {foreach $groups as $group}
            {include 'dashboard/dashboard_group.tpl' models=$models|get_filtered:$group->id group=$group->id title=$group}
        {/foreach}

    {/smarty_admin_block}
{/block}