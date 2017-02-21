{extends 'dashboard/layouts/menu_layout.tpl'}
{block 'heading'}
    <h1 align="center">Filters list.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='My dashboard'}

        {include 'dashboard/dashboard_group.tpl' models=$myModels}
    {/smarty_admin_block}

    {smarty_admin_block name='Order dashboard'}
        <div rel="g_null">Not in group</div>
        <div id="g_null">
            {include 'dashboard/dashboard_group.tpl' models=$models|get_filtered:null group=null}
        </div>

        {foreach $groups as $group}
            <div rel="g_{$group->id}">{$group}</div>
            <div id="g_{$group->id}">
                {include 'dashboard/dashboard_group.tpl' models=$models|get_filtered:$group->id group=$group->id}
            </div>
        {/foreach}

    {/smarty_admin_block}
{/block}