<br>
<style type="text/css">
    .main-menu-modules {
        line-height: 1.3;
    }
    .main-menu-modules a {
        text-decoration: none;
    }

    .main-menu-modules a:hover {
        text-decoration: underline;
    }

    .main-menu-modules .menu-block {
        padding: .5em  .5em 0;
    }
    .main-menu-modules .menu-block ul {
        margin:0;
        padding:0;
        list-style: none;
    }

    .main-menu-modules .menu-block .module {
        margin-bottom: .4em;
    }
    .main-menu-modules .menu-block .module ul li {
        line-height: 1.2;
    }

    .main-menu-modules .menu-block .module ul li ul {
        padding-left: .8em;
    }
    .main-menu-modules .menu-block .module .name {
        border-bottom: 1px solid;
        margin-bottom: .5em;
        font-size: 1em;
        font-weight: bold;
    }
</style>

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

