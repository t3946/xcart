<table class="dashboard-filters index">
    {foreach 1..$row_col.row as $row}
        <tr>
            {foreach 1..$row_col.col as $col}
                <td>
                    {foreach $models as $model}
                        {if $model->position_row == $row && $model->position_column == $col}
                            <a href="{$model->getAbsoluteUrl()}" class="{if $model->getSearchStorage()->getCashedCount() == 0}empty{else}button{/if}" target="_blank">
                                <div class="row">
                                    <div class="columns large-2">
                                        {if $model->tag}
                                            <span style="background-color: {$model->color};" class="tag no-border">{$model->tag|upper}</span>
                                        {else}
                                            <span class="tag"></span>
                                        {/if}
                                    </div>
                                    <div class="columns large-10">
                                        <span class="name {if $model->getSearchStorage()->getCashedCount() == 0}gray{/if}">
                                            <span class="{if $model->bold}bold{/if}">{$model}</span>
                                            (<span class="count">{$model->getSearchStorage()->getCashedCount()}</span>)
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
