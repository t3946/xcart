{extends 'base/admin.tpl'}
{block 'heading'}
    <h1 align="center">
        Filters list.
        <a href="{url 'dashboard:create_group'}" class="button">
            <i class="icon-plus-thin">+</i>
        </a>
    </h1>
{/block}

{block 'content'}
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