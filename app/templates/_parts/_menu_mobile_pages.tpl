<div class="menu-title">
    <h3>Pages</h3>
</div>
<ul class="accordion" data-accordion data-allow-all-closed="true" data-multi-expand="true">
    {set $menu_items = $.get_menu_items('pages-menu') }
    {foreach $menu_items as $item}

            {set $childs = $item.items}
            {set $has_childs = ($childs|count > 0)}

            <li class="accordion-item" {if $has_childs }data-accordion-item{/if}>
                <a class="accordion-title" {if !$has_childs}href="{$item.link}" {/if}>
                    <div class="row">
                        <div class="columns small-12 ">
                            <span>{$item.name}</span>
                        </div>
                    </div>
                </a>

                {if $has_childs}
                    <div class="accordion-content" data-tab-content>
                        <ul class="no-bullet">
                            {foreach $childs as $item}
                                    <li>
                                        <a href="{$item.link}">
                                            {$item.name}
                                        </a>
                                    </li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}
            </li>

    {/foreach}
</ul>