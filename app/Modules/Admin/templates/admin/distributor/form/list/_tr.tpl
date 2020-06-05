<tr data-pk="{$pk}" style="background-color: {($index %2) === 0 ? "#ffffff" : "#e1e5ed"}">
    {if $admin->sort}
        <td class="sort" style="border: none;">
            <a href="#" class="sort-handler {if $canSort}active{else}not-active{/if}">
                <i class="icon-double_triangle"></i>
            </a>
        </td>
    {/if}

    {if $isNested}
        <td class="nested">
            {if !$item->getIsLeaf()}
                <a href="{url 'admin:list_nested' params=['id' => $item->pk, 'admin' => $adminClass, 'module' => $moduleClass]}"
                   class="">
                    <i class="fa fa-folder-open"></i>
                </a>
            {/if}
        </td>
    {/if}

    {foreach $columns['enabled'] as $column}

        {var $config = $columns['config'][$column]}
        {var $template = $config['template']}
        {var $ext_column = $config['extend']}
        {var $ext_template = $columns['config'][$ext_column]['template']}
        <td class="col" style="border: none; vertical-align: top; white-space: nowrap; padding: 2px 0px;">
            <table cellpadding="0" cellspacing="0">
                <tr style="background-color: {($index %2) === 0? "#ffffff" : "#e1e5ed"}">
                    {if $config['title_inline']}
                        <td style="border: none; padding:0;">
                            <span><b>{$config['title']}</b></span>
                            {if ($config['hint'])}
                                {include 'admin/distributor/form/hint.tpl' hint=$config['hint']}
                            {/if}
                        </td>
                    {/if}
                    <td style="border: none;">{include $template}</td>
                </tr>
                {if $ext_column}
                    <tr style="background-color: {($index %2) === 0 ? "#ffffff" : "#e1e5ed"}">
                        <td style="border: none; padding:0;">
                            {set $column = $ext_column}
                            <span><b>{$columns['config'][$ext_column]['title']}</b></span>
                        </td>
                        <td style="border: none;">
                            {include $ext_template}
                        </td>
                    </tr>
                {/if}
            </table>
        </td>
    {/foreach}

    <td class="actions" style="border: none; white-space: nowrap;">
        {include $admin->listItemActionsTemplate}
    </td>
</tr>