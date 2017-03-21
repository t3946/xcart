<div id="admin-menu-wrapper">
    {smarty_admin_block name= 'Modules main menu' title_size=12 class='no-padding no-margin'}
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
    {/smarty_admin_block}
</div>
