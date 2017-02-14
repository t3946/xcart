{extends 'base/admin.tpl'}
{block 'heading'}
    <h1 align="center">Filters list.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Actions'}
            <a href="{url 'dashboard:create'}" class="button">Create new</a>
    {/smarty_admin_block}

    {smarty_admin_block name= 'Filters'}
        <table style="width: 100%">
            {foreach 1..$row_col.row as $row}
                <tr>
                    {foreach 1..$row_col.col as $col}
                        <td>
                            {foreach $models as $model}
                                {if $model->position_row == $row && $model->position_column == $col}
                                    <a href="{$model->getAdminUrl()}" class="button" style="display: block; text-align: left;">
                                        {if $model->tag}
                                            <span style="background-color: {$model->color}; display: inline-block; min-width: 1.3em; min-height: 1.3em; text-align: center; color: #fff; font-weight: bold; border-radius: 2px;">
                                                {$model->tag|upper}
                                            </span>
                                        {/if}
                                        {$model}
                                        {*({$model->position_row}, {$model->position_column})*}
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