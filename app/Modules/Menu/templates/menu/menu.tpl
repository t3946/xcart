{add $level = 1}
{foreach $items as $item }
    {set $classes = $item.class?[$item.class]:[]}
    {set $classes[] = 'level-'~$level}

    {if $item.items}
        {set $classes[] = 'has-subitems'}
    {/if}

    {if $item.url && $.request->getPath() == $item.url}
        {set $classes[] = 'active'}
    {/if}

    <li class="{$classes|implode:' '}">
        {if $item.url}
            <a href="{$item.url ? $item.url : "#" }">{$item.name}</a>
        {else}
            <span>{$item.name}</span>
        {/if}

        {if $item.items!}
            <ul class="childrens {"level-"~$level}">
                {include "menu/menu.tpl" items=$item.items level=$level+1}
            </ul>
        {/if}
    </li>
&ensp;
{/foreach}
