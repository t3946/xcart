<tr data-pk="{$pk}">

    <td class="checker">
        <input type="checkbox" id="{$id}-{$pk}-check" name="pk_list[]" value="{$pk}">
        <label for="{$id}-{$pk}-check" class="alone"></label>
    </td>

    {if $admin->sort}
        <td class="sort">
            <a href="#" class="sort-handler {if $canSort}active{else}not-active{/if}">
                <i class="icon-double_triangle"></i>
            </a>
        </td>
    {/if}

    {if $isNested}
        <td class="nested">
            {if !$item->getIsLeaf()}
                <a href="{url 'admin:list_nested' params=['id' => $item->pk, 'admin' => $adminClass, 'module' => $moduleClass]}" class="">
                    <i class="fa fa-folder-open"></i>
                </a>
            {/if}
        </td>
    {/if}

    {foreach $columns['enabled'] as $column}
        {var $config = $columns['config'][$column]}
        {var $template = $config['template']}

        <td class="col">
            {include $template}
        </td>
    {/foreach}

    <td class="actions">
        {include $admin->listItemActionsTemplate}
    </td>
</tr>