{extends 'base/admin.tpl'}
{block 'heading'}
    <h1 align="center">Filters list.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Order dashboard'}
        <table style="width: 100%" class="dashboard-table">
            {foreach 1..$row_col.row as $row}
                <tr>
                    {foreach 1..$row_col.col as $col}
                        <td>
                            {foreach $models as $model}
                                {if $model->position_row == $row && $model->position_column == $col}
                                    {if $model->getSearchStorage()->getPager()->getTotal() > 0}
                                        <a href="{$model->getAbsoluteUrl()}">
                                            {if $model->tag}
                                                <span style="background-color: {$model->color}; display: inline-block; min-width: 1.3em; min-height: 1.3em; text-align: center; color: #fff; font-weight: bold;">
                                                    {$model->tag|upper}
                                                </span>
                                            {/if}
                                            <span class="underline">
                                                {if $model->bold}
                                                    <em>{$model} </em>
                                                {else}
                                                    {$model}
                                                {/if}
                                                ({$model->getSearchStorage()->getPager()->getTotal()})
                                            </span>
                                        </a>
                                    {else}
                                        <span>{$model}</span>
                                    {/if}
                                {/if}
                            {/foreach}
                        </td>
                    {/foreach}
                </tr>
            {/foreach}
        </table>
    {/smarty_admin_block}
{/block}