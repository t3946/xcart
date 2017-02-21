<table class="dashboard-filters">
    {foreach 1..($row_col.row+1) as $row}
        <tr>
            {foreach 1..$row_col.col as $col}
                <td>
                    <div class="container" data-row="{$row}" data-col="{$col}" data-group="{$group}">
                        {foreach $models as $model}
                            {if $model->position_row == $row && $model->position_column == $col}

                                <a href="{$model->getAdminUrl()}" class="button">
                                    <div class="row">
                                        {if $model->tag}
                                            <div class="columns large-2">
                                                <span style="background-color: {$model->color};" class="tag no-border">&nbsp;{$model->tag|upper}&nbsp;</span>
                                            </div>
                                        {/if}
                                        <div class="columns {if $model->tag}large-10{else}large-12{/if}">
                                            <span class="name">
                                                <span class="{if $model->bold}bold{/if} {if !$model->enabled}gray{/if}">
                                                    {$model}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            {/if}
                        {/foreach}
                    </div>
                </td>
            {/foreach}
        </tr>
    {/foreach}
</table>