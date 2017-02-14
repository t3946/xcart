{extends 'base/admin.tpl'}
{block 'heading'}
    <h1 align="center">Filters list.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Actions'}
            <a href="{url 'dashboard:create'}" class="button">Create new</a>
    {/smarty_admin_block}

    {smarty_admin_block name= 'Filters'}
        <table class="dashboard-filters">
            {foreach 1..$row_col.row as $row}
                <tr>
                    {foreach 1..$row_col.col as $col}
                        <td>
                            {foreach $models as $model}
                                {if $model->position_row == $row && $model->position_column == $col}

                                    <a href="{$model->getAdminUrl()}" class="button">
                                        <div class="row">
                                            {if $model->tag}
                                                <div class="columns large-2">
                                                    <span style="background-color: {$model->color};" class="tag">
                                                        {$model->tag|upper}
                                                    </span>
                                                </div>
                                            {/if}
                                            <div class="columns {if $model->tag}large-10{else}large-12{/if}">
                                            <span class="name">
                                            {$model}
                                                {*({$model->position_row}, {$model->position_column})*}
                                        </span>
                                            </div>
                                        </div>
                                    </a>
                                {/if}
                            {/foreach}
                        </td>
                    {/foreach}
                </tr>
            {/foreach}
        </table>
    {/smarty_admin_block}
{/block}