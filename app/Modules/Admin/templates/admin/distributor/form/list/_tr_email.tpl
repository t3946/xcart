<tr data-thread-id="{$item->thread_id}" {if $child}class='child'{/if} style="cursor: pointer;" data-pk="{$pk}"
    onclick="window.open('{$admin->getInfoUrl($pk)|escape}')">

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
                <a style="width: 100%;" href="{url 'admin:list_nested' params=['id' => $item->pk, 'admin' => $adminClass, 'module' => $moduleClass]}"
                   class="">
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
        {if !$child && $item->message_id !== $item->thread_id}
            <a onclick="$('[data-thread-id={$item->thread_id}]').show(); $('i', this).addClass('fa-minus').removeClass('fa-plus'); event.stopPropagation(); return false;"
               href="{url 'admin:list_nested' params=['id' => $item->pk, 'admin' => $adminClass, 'module' => $moduleClass]}"
               class="">
                <i class="fa fa-plus"></i>
            </a>
        {/if}
    </td>
</tr>
{if !$child && $item->message_id !== $item->thread_id}
    {foreach $item->children->filter(['message_id__isnt' => $item->message_id])->order(['-date']) as $child}
        {include 'admin/distributor/form/list/_tr_email.tpl' item=$child child=true pk=$child->pk}
    {/foreach}

{/if}