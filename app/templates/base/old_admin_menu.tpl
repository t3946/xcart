<div class="VertMenuBorder main-menu-modules">
    <div class="VertMenuTitle">
        Modules menu
    </div>
    <div class="VertMenuBox">
        <div class="menu-block">
                <div class="menu-wrapper">
                    <ul class="main-menu">
                        {foreach $.admin_menu as $module}
                            {if $module['items']|count > 0}
                                <li class="module">
                                    <div class="name">
                                        {$module['name']}
                                    </div>
                                    {if $module['items']}
                                        {include 'base/_admin_menu_item.tpl' items = $module['items']}
                                    {/if}
                                </li>
                            {/if}
                        {/foreach}
                    </ul>
                </div>
            </div>
    </div>
</div>
