{extends 'base/admin.tpl'}
{block 'heading'}
    <h1 align="center">Filters list.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Order dashboard'}
        <table style="width: 100%" class="dashboard-filters">
            {foreach 1..$row_col.row as $row}
                <tr>
                    {foreach 1..$row_col.col as $col}
                        <td>
                            {foreach $models as $model}
                                {if $model->position_row == $row && $model->position_column == $col}
                                    {if $model->getSearchStorage()->getPager()->getTotal() > 0}
                                        <a href="{$model->getAdminUrl()}" class="">
                                            <div class="row">
                                                {if $model->tag}
                                                    <div class="columns large-2">
                                                        <span style="background-color: {$model->color};" class="tag">
                                                            <span>
                                                                {$model->tag|upper}
                                                            </span>
                                                        </span>
                                                    </div>
                                                {/if}
                                                <div class="columns {if $model->tag}large-10{else}large-12{/if}">
                                                    <span class="name">
                                                        {$model} ({$model->getSearchStorage()->getPager()->getTotal()})
                                                    </span>


                                                </div>
                                            </div>
                                        </a>
                                    {else}
                                        <div class="row">
                                            <div class="columns large-12">
                                                <span class="gray">
                                                    {$model}
                                                </span>
                                            </div>
                                        </div>
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