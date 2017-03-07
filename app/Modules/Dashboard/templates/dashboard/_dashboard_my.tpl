{if $models|count > 0}
    {if $title}
        <fieldset>
        <legend>{$title}</legend>
    {/if}

        <table class="dashboard-filters index">
            {foreach 1..$row_col.row as $row}
                <tr>
                    {foreach 1..$row_col.col as $col}
                        <td>
                            <div class="container" data-row="{$row}" data-col="{$col}" data-group="{$group}">
                                {foreach $models as $model}
                                    {if ($model->position_row == $row && $model->position_column == $col && !$my_position) || ($my_position && $model->getMyPositionRow() == $row && $model->getMyPositionColumn() == $col )}
                                        {include 'dashboard/_dashboard_item.tpl' model=$model}
                                    {/if}
                                {/foreach}
                            </div>
                        </td>
                    {/foreach}
                </tr>
            {/foreach}
        </table>

    {if $title}
        </fieldset>
    {/if}
{/if}