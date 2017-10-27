<table class="VertMenuBorder main-menu-modules" width="100%" cellspacing="1">
    <tr>
        <td width="100%" class="VertMenuTitle">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div class="name">
                            Modules menu
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="VertMenuBox">
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
        </td>
    </tr>
</table>

