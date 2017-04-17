<nav>
    {set $menus = $.getRandomSubmenu()}
    {if $menus|count > 0}
        {foreach $menus as $menu}
            <div class="column">
                <h4 class="{if $menu.items|count > 0}has-children{/if}">
                    <a href="{$menu.link}">{$menu.name}</a>
                </h4>
                {if $menu.items|count > 0}
                    <ul>
                        {foreach $menu.items as $item}
                            <li>
                                <a href="{$item.link}">{$item.name}</a>
                            </li>
                        {/foreach}
                    </ul>

                {/if}
            </div>
        {/foreach}
        {if rand(1,2) > 1}
            <div class="banner-fantom" style=" width: 400px; height: 258px;" > </div>
            <div class="banner" style="background: url('/static/frontend/demo_images/home/1280/sale-of-brushes.png') no-repeat 100% 100%; width: 400px; height: 258px;">
                <a href="/#" class="sale" style="position: absolute; top:30%; left: 10%;">

                {if rand(1,2) == 1}
                    Sale
                {else}
                    Learn more
                {/if}
                </a>
            </div>
        {/if}
    {/if}
</nav>



<a href="#" class="view-all">
        View all {$menu_name} departments
</a>