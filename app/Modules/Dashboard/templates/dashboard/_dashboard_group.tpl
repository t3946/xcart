{if $models|count > 0}
    {if $title}
        <fieldset class="collapsible">
        <legend>{$title}</legend>
    {/if}

        <table class="dashboard-filters index groups">
            <tr>
                {foreach 1..$row_col.col as $col}
                    <td>
                        {foreach 1..$row_col.row as $row}
                            {foreach $models as $model}
                                {if ($model->position_row == $row && $model->position_column == $col && !$my_position) || ($my_position && $model->getMyPositionRow() == $row && $model->getMyPositionColumn() == $col )}
                                    {include 'dashboard/_dashboard_dymmy_item.tpl' model=$model check_owners=true}
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