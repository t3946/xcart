{if $menus|count > 0}
    <div class="submenu-container">
        {*<nav class="has-column-{if $menus.columns > 3}3{else}{$menus.columns}{/if}">*}
        <div class="nav has-column-3">

            {foreach $menus.menu as $menu}
                {*<div class="menu-block {if !$has_banner && ($menus.columns > 1)}liquid{/if}">*}
                <noindex><div class="menu-block">
                    <h4 class="{if $menu.items|count > 0}has-children{/if}">
                        <a href="{$menu.link}">{$menu.name}</a>
                    </h4>
                    {if $menu.items|count > 0}

                            <ul class="{if $menu.more_items}has_more_items{/if}">
                                    {foreach $menu.items as $item}
                                        <li>
                                            <a href="{$item.link}" rel="noindex">{$item.name}</a>
                                        </li>
                                    {/foreach}
                            </ul>

                    {/if}
                </div></noindex>
            {/foreach}

            {if $has_banner}
                <div class="banner-fantom" style=" width: 100px; height: 300px;" > </div>
                <div class="banner" style="background: url('/static/frontend/demo_images/home/1280/sale-of-brushes.png') no-repeat 100% 100%; width: 400px; height: 258px;">
                    <a href="/#" class="sale" style="position: absolute; top:30%; left: 10%;">

                        {if rand(0,1)}
                            Sale
                        {else}
                            Learn more
                        {/if}
                    </a>
                </div>
            {/if}

        </div>

        <a href="{url 'catalog:list'}#id{$model->categoryid}" class="view-all">
            View all {$menu_name} departments
        </a>
    </div>
{/if}