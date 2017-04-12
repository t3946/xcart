<ul class="category-menu-container dropdown menu" data-dropdown-menu >
    <li class="category-menu">
        <span class="menu-icon"></span>
        <span class="category-menu-title">Departments</span>
        <ul class="menu category-menu-list">
            {set $catMenu = $.getCategoryMenu()}
            {foreach $catMenu as $item index=$index}
                <li class="category-menu-item">
                    <div class="item-container">
                        <a href="#">
                            <div class="row">
                                <div class="column large-2">
                                    <div class="item-image">
                                        <img src="{$item.image}" alt="{$item.name}">
                                    </div>
                                </div>
                                <div class="column large-10">
                                    <span class="item-name">
                                        {$item.name}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <ul class="menu">
                        <li>
                            {include "demo/blocks/_submenu_desktop.tpl"}
                        </li>
                    </ul>
                </li>
                {if $index == 11} {break} {/if}
            {/foreach}
            {if $catMenu|count > 12}
                <a href="#" class="view-all">View all departments</a>
            {/if}
        </ul>
    </li>
</ul>