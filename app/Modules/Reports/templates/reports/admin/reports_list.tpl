{extends 'base/admin.tpl'}
{block 'heading'}
    <h1 align="center">
        Reports list.
        <a href="{url 'reports:create_report'}" class="button">
            <i class="icon-plus-thin">+</i>
        </a>
    </h1>
{/block}

{block 'content'}
    {smarty_admin_block name= 'Reports'}
        <table class="dashboard-filters">
            {foreach $models as $model}
                <tr>
                    <td>
                        <a href="{$model->getAdminUrl()}" class="button">
                            {$model->name}
                        </a>
                    </td>
                </tr>
            {/foreach}
        </table>
    {/smarty_admin_block}
{/block}