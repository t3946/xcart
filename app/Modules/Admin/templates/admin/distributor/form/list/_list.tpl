{var $id = $admin->getId()}

{add $isNested = false}

<div class="list-block" data-list data-id="{$id}-list">
    <div class="list-wrapper">
        <div class="list-update-block">

            <table data-list-table {if $admin->sort}data-sorting{/if} cellpadding="0" cellspacing="0">
                {if $admin->editable}
                <thead>
                    {var $cols = 0}
                    <tr class="list-head">
                        {if $admin->sort}
                            <th class="sort " data-sort-column>
                                <span class="title">
                                     <i class="icon-double_triangle"></i>
                                </span>

                                {var $cols = $cols+1}
                            </th>
                        {/if}

                        {if $isNested }
                            <th class="nested " data-nested-column>
                                <span class="title">
                                     <i class="fa fa-folder"></i>
                                </span>

                                {var $cols = $cols+1}
                            </th>
                        {/if}

                        {foreach $columns['enabled'] as $column}
                            {var $config = $columns['config'][$column]}
                            <th class="col" style="text-align: center; white-space: nowrap;">
                                {include 'admin/distributor/form/list/_th.tpl'}
                                {var $cols = $cols+1}
                            </th>
                        {/foreach}

                    </tr>
                </thead>
                {/if}
                <tbody>
                    {foreach $objects as $item index=$index}
                        {var $pk = $item->pk}
                        {include $admin->listRowTemplate}
                    {foreachelse}
                        <tr class="empty">
                            <td colspan="{$cols}" class="text-center">
                                Records not found
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
            <div class=" clearfix">
                {if $admin->editable}
                <div class="row" style="margin-top: 15px;">
                    <div class="top-buttons-block left">
                        <a href="{$admin->getCreateUrl()}" class="button round upper pad">
                            Add new line
                        </a>
                    </div>
                    <div class="text-center">
                        <button type="submit">Save</button>
                    </div>
                </div>
                {/if}
            </div>

            {if $pagination}
                <div class="pagination-block">
                    {raw $pagination->render($admin->listPaginationTemplate)}
                </div>
            {/if}

        </div>
    </div>
</div>
