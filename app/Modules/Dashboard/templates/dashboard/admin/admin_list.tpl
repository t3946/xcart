{extends 'dashboard/layouts/menu_layout.tpl'}
{block 'heading'}
    <h1 align="center">Filters list.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Actions'}
        <a href="{url 'dashboard:create_filter'}" class="button">Create new filter</a>
    {/smarty_admin_block}

    {smarty_admin_block name= 'Filters'}
        <div class="admin-dashboard-filters-list">
            <div rel="g_null">Not in group</div>
            <div id="g_null">
                {include 'dashboard/admin/dashboard_group.tpl' models=$models|get_filtered:null group=null}
            </div>

            {foreach $groups as $group}
                <div rel="g_{$group->id}">{$group}</div>
                <div id="g_{$group->id}">
                    {include 'dashboard/admin/dashboard_group.tpl' models=$models|get_filtered:$group->id group=$group->id}
                </div>
            {/foreach}
        </div>
    {/smarty_admin_block}
{/block}