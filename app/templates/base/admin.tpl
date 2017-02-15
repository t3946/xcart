<script src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous">
</script>

<link rel="stylesheet" href="/static/backend/css/base.css?v=1">

<script src="/static/vendors/jquery.cookie-1.4.1.min.js"></script>
<script type="text/javascript" src="/static/backend/js/main.js?v=1"></script>

{block 'js-head'}

{/block}

{filter|strip:true}
<div id="wrapper" class="admin">
    {block 'content-header'}

        <div class="row">
            <div class="columns large-12">
                {block 'menu_block'}
                    {smarty_admin_block name= 'Modules main menu'}
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
                {/block}
            </div>
        </div>

        <div class="content-header">
            <div class="row">
                <div class="column large-12">
                    {block 'breadcrumbs'}

                    {/block}

                    {block 'heading'}

                    {/block}
                </div>
            </div>
        </div>
    {/block}

    <div id="main">
        {block 'main'}
            <div class="main-content">
                {block 'before-content'}

                {/block}

                <div class="row">
                    <div class="column large-12">
                        {block 'content'}

                        {/block}
                    </div>
                </div>

                {block 'after-content'}

                {/block}
            </div>
        {/block}
    </div>

    <div id="push"></div>
</div>
{/filter}

{block 'js'}

{/block}