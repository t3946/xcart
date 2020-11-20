<tr data-pk="{$pk}"
        {if $item->enabled}
            {if intval($item->last_update_late) == 0}style="background-color:#beffbe;"
                {elseif intval($item->last_update_late) == 1}style="background-color:#fff7b0;"
                {elseif $item->last_update_late >= 2}style="background-color:#ffd6d6;"
            {/if}
        {/if}>


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

    {if $admin->getListItemActions()}
        <td class="actions">
            {include $admin->listItemActionsTemplate}
        </td>
    {/if}
</tr>