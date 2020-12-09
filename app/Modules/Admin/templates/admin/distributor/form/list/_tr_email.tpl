<tr data-thread-id="{$item->thread_id}" {if $child}class='child'{/if} style="cursor: pointer;" data-pk="{$pk}"
    onclick="window.open('{$admin->getInfoUrl($pk)|escape}')">

    {foreach $columns['enabled'] as $column}
        {var $config = $columns['config'][$column]}
        {var $template = $config['template']}
        <td class="col"
            {if $column === 'subject'}style="width: 100%;"
            {elseif $column === 'date'}style="width: 50px; min-width: 50px; white-space: nowrap"
            {else}style="width: 200px; min-width: 200px;"
            {/if}>
            {if $column === 'subject'}
                <div style="width: 0; min-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
            {/if}
            {include $template}
            {if $column === 'subject'}
                </div>
            {/if}
        </td>
    {/foreach}

    <td class="actions">
        {include $admin->listItemActionsTemplate}
        {if !$child && $item->message_id !== $item->thread_id}
            <a onclick="$('[data-thread-id={$item->thread_id}]').show(); $('i', this).addClass('fa-minus').removeClass('fa-plus'); event.stopPropagation(); return false;"
               href="#"
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