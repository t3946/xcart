{if $models|count > 0}
    {if $title}
        <fieldset>
        <legend>{$title}</legend>
    {/if}

        <table class="dashboard-filters index groups">
            <tr>
                {foreach 1..$row_col.col as $col}
                    <td>
                        {foreach 1..$row_col.row as $row}
                            {foreach $models as $model}
                                {if ($model->position_row == $row && $model->position_column == $col && !$my_position) || ($my_position && $model->getMyPositionRow() == $row && $model->getMyPositionColumn() == $col )}
                                    <a href="{$model->getAbsoluteUrl()}"
                                       class="{if $model->getSearchStorage()->getCashedCount() == 0}empty{else}button{/if}"
                                       target="_blank"
                                       data-id="{$model->id}"
                                       data-action="{url 'dashboard:filter_subscription' id=$model->id}"
                                       data-count="{$model->getSearchStorage()->getCashedCount()}">
                                        <div class="row">
                                            <div class="columns large-2">
                                                {if $model->tag}
                                                    <span style="background-color: {$model->color};" class="tag no-border">{$model->tag|upper}</span>
                                                {else}
                                                    <span class="tag"></span>
                                                {/if}
                                            </div>
                                            <div class="columns large-10">
                                                <span class="name">
                                                    <span class="{if $model->bold}bold{/if} filter_name">{$model}</span>
                                                    (<span class="count">{$model->getSearchStorage()->getCashedCount()}</span>)
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                {/if}
                            {/foreach}
                        {/foreach}
                    </td>
                {/foreach}
            </tr>
        </table>

    {if $title}
        </fieldset>
    {/if}
{/if}