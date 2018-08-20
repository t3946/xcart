{var $id = $admin->getId()}
{add $isNested = false}

<div class="list-block" data-list data-id="{$id}-list">
    <div class="list-top clearfix">
        <div class="top-buttons-block left">
            <a href="{$admin->getCreateUrl()}" class="button round upper pad">
                <span class="text">
                    Add
                </span>
                <i class="icon-plus"></i>
            </a>
        </div>

        {if $search}
            <div class="top-search-block left">
                <input type="text" data-list-search placeholder="Поиск...">
            </div>
        {/if}
    </div>
    <div class="list-wrapper">
        <div class="list-update-block">
            <table data-list-table {if $admin->sort}data-sorting{/if}>
                <thead>
                    {var $cols = 0}

                    <tr class="list-head">
                        <th class="checker full">
                            <input type="checkbox" id="{$id}-check-all" data-checkall-list>
                            <label for="{$id}-check-all" class="alone"></label>
                            {var $cols = $cols+1}
                        </th>

                        {if $admin->sort}
                            <th class="sort full" data-sort-column>
                                <span class="title">
                                     <i class="icon-double_triangle"></i>
                                </span>

                                {var $cols = $cols+1}
                            </th>
                        {/if}

                        {if $isNested }
                            <th class="nested full" data-nested-column>
                                <span class="title">
                                     <i class="fa fa-folder"></i>
                                </span>

                                {var $cols = $cols+1}
                            </th>
                        {/if}

                        {foreach $columns['enabled'] as $column}
                            {var $config = $columns['config'][$column]}
                            <th class="col full">
                                {include 'admin/list/_th.tpl'}
                                {var $cols = $cols+1}
                            </th>
                        {/foreach}

                        <th class="actions">
                            <div class="columns-list-appender">
                                <a href="#" class="button-appender appender-columns">
                                    <i class="icon-plus"></i>
                                </a>
                                <div class="popup-block">
                                    <ul class="columns-list">
                                        {foreach $columns['config'] as $name => $column}
                                            <li>
                                                <div class="checker">
                                                    <input type="checkbox" id="{$id}-{$name}-column" name="columns_list[]" value="{$name}" {if $name in $columns['enabled']}checked="checked"{/if}>
                                                    <label for="{$id}-{$name}-column">
                                                        {$column['title']}
                                                    </label>
                                                </div>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            </div>

                            {var $cols = $cols+1}
                        </th>
                    </tr>

                    <tr class="delimiter">
                        {foreach 1..$cols}
                            <th></th>
                        {/foreach}
                    </tr>
                </thead>
                <tbody>
                    {foreach $objects as $item}
                        {var $pk = $item->pk}
                        {include $admin->listRowTemplate}
                    {foreachelse}
                        <tr class="empty">
                            <td colspan="{$cols}" class="text-center">
                                Пока здесь нет ни одной записи
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
            <div class="list-footer clearfix">
                {if $pagination}
                    <div class="list-footer-block v-align right total">
                        <div>
                            Всего записей: {$pagination->getTotal()}
                        </div>
                    </div>
                {/if}

                <div class="list-footer-block v-align left group">
                    <div>
                        <div class="checker-wrapper">
                            <input type="checkbox" id="{$id}-check-all-bottom" data-checkall-list>
                            <label for="{$id}-check-all-bottom">
                                Для всех
                            </label>
                        </div>

                        {var $actions = $admin->getListGroupActions()}
                        {if ("update" in $actions) || ("remove" in $actions)}
                            <div class="group-buttons">
                                {if ("update" in $actions)}
                                    <a href="#" class="group-button" data-group-update>
                                        <i class="icon-edit"></i>
                                    </a>
                                {/if}

                                {if ("remove" in $actions)}
                                    <a href="#" class="group-button" data-group-remove>
                                        <i class="icon-delete_in_table"></i>
                                    </a>
                                {/if}
                            </div>
                        {/if}

                        {var $dropdown = $admin->getListDropDownGroupActions()}
                        {if $dropdown}
                            <div class="dropdown-block">
                                <select name="" id="" data-group-action>
                                    <option value="" selected disabled>Выберите действие</option>
                                    {foreach $dropdown as $key => $item}
                                        <option value="{$key}">
                                            {$item['title']}
                                        </option>
                                    {/foreach}
                                </select>
                                <button class="button" data-group-submit>
                                    <i class="icon-check_mark"></i>
                                </button>
                            </div>
                        {/if}
                    </div>
                </div>
            </div>

            {if $pagination}
                <div class="pagination-block">
                    {raw $pagination->render($admin->listPaginationTemplate)}
                </div>
            {/if}
        </div>
    </div>
</div>

<script>
    $(function () {
        $('[data-id="{$id}-list"]').adminList({
            url: "{$.request->getUrl()}",
            groupActionUrl: "{$admin->getGroupActionUrl()}",
            sortUrl: "{$admin->getSortUrl()}",
            columnsUrl: "{$admin->getColumnsUrl()}"
        });
    });
</script>