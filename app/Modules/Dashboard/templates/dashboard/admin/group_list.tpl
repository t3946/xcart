{extends 'dashboard/layouts/menu_layout.tpl'}
{block 'heading'}
    <h1 align="center">Filters list.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Actions'}
        <a href="{url 'dashboard:create_group'}" class="button">Create new group</a>
    {/smarty_admin_block}

    {smarty_admin_block name= 'Groups'}
        <table class="dashboard-filters">
            {foreach $models as $model}
                <tr>
                    <td>
                        <a href="{$model->getAdminUrl()}" class="button">
                            {$model}
                        </a>
                    </td>
                </tr>
            {/foreach}
        </table>
    {/smarty_admin_block}
{/block}