{var $id = $admin->getId()}
{add $isNested = false}
{var $actions = $admin->getListGroupActions()}

<div class="list-block" data-list data-id="{$id}-list">
    {if $search}
        <div class="list-top clearfix">
            <div class="top-search-block left">
                <input type="text" data-list-search placeholder="Search...">
            </div>
        </div>
    {/if}
    <div class="list-wrapper">
        <div class="list-update-block">
            <div class="checker-wrapper">
                <div class="top-buttons-block left">
                    {if "add" in $actions}
                        <a href="{$admin->getCreateUrl()}" class="{if $admin->isAjaxCreate()}ajax {/if}button round upper pad">
                            <span class="text">Add</span>
                            <i class="icon-plus"></i>
                        </a>
                    {/if}
                    {var $lang_codes = $filter_form->getField('name')->getValue()}
                    {if is_array($lang_codes)}
                        {var $lang_code = $lang_codes|array_filter|array_values}
                        <form class="upload-translations-form">
                            <label>
                                Upload {$lang_code[0]}.po translates file:
                                <input id="{$lang_code[0]}" type="file" name="translates-list" value="upload_translates"/>
                            </label>
                            <button>Upload</button>
                        </form>
                        <a class="download-translations-button"
                           href="{url 'admin_translate:download'}?lang_code={$lang_code[0]}">
                            <span class="text">Download {$lang_code[0]}.po</span>
                        </a>
                    {/if}
                </div>
            </div>
            <table data-list-table {if $admin->sort}data-sorting{/if} class="translates-table">
                <thead>
                {var $cols = 0}

                <tr class="list-head">

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
                        <th class="col full" {if $config['th']}{$config['th']|http_build_query:':'}{/if}>
                            {include 'admin/list/_th.tpl'}
                            {var $cols = $cols+1}
                        </th>
                    {/foreach}

                    {if $admin->getListItemActions()}
                        <th class="actions col full">
                            {var $cols = $cols+1}
                        </th>
                    {/if}
                </tr>
                </thead>
                <tbody>
                {foreach $objects as $item}
                    {var $pk = $item->pk}
                    {include $admin->listRowTemplate}
                    {foreachelse}
                    <tr class="empty">
                        <td colspan="{$cols}" class="text-center">
                            No data found
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
            {if $pagination || $actions}
                <div class="list-footer clearfix">
                    {if $pagination}
                        <div class="list-footer-block v-align right total">
                            <div>
                                Total: {$pagination->getTotal()}
                            </div>
                        </div>
                    {/if}
                    {if $actions}
                        <div class="list-footer-block v-align left group">
                            <div>
                                <div class="checker-wrapper">
                                    {if "add" in $actions}
                                        <div class="top-buttons-block left">
                                            <a href="{$admin->getCreateUrl()}" class="{if $admin->isAjaxCreate()}ajax {/if}button round upper pad">
                                                <span class="text">Add</span>
                                                <i class="icon-plus"></i>
                                            </a>
                                        </div>
                                    {/if}
                                </div>

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
                                            <option value="" selected disabled>Selext action</option>
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
                    {/if}
                </div>
            {/if}

            {if $pagination}
                <div class="pagination-block">
                    {raw $pagination->render($admin->listPaginationTemplate)}
                </div>
            {/if}
        </div>
    </div>

</div>
