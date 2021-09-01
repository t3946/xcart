{if $items}
    <ul class="items">
        {foreach $items as $item}
            <li class="item">
                {if $item['route']}
                    <a href="{$item['route']}">
                        {if $item['icon']}
                            <i class="{$item['icon']}"></i>
                        {/if}

                        {$item['name']}
                    </a>
                {else}
                    <span>
                        {if $item['icon']}
                            <i class="{$item['icon']}"></i>
                        {/if}

                        {$item['name']}
                    </span>
                {/if}


                {if $item['items']}
                    {include 'base/_admin_menu_item.tpl' items = $item['items']}
                {/if}
            </li>
        {/foreach}
    </ul>
{/if}