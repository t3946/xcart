{smarty_admin_block name= 'Modules main menu'}
    <h3>WARNING! Test only.</h3>
    <div class="menu-block">
        <div class="menu-wrapper">
            <ul class="main-menu">
                {foreach $.admin_menu as $module}
                    {if $module['items']|count > 0}
                        <li class="module">
                            <div class="name">
                                {$module['name']}
                            </div>
                            <ul class="items">
                                {foreach $module['items'] as $item}
                                    <li class="item">
                                        <a href="{$item['route']}" class="button">
                                            {$item['name']}
                                        </a>
                                    </li>
                                {/foreach}
                            </ul>
                        </li>
                    {/if}
                {/foreach}
            </ul>
        </div>
    </div>
{/smarty_admin_block}