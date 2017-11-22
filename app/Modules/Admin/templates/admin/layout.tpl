<!doctype html>
<html lang="en">
<head>
    {if !$.request->getIsAjax()}
        <meta charset="utf-8">
        {* Title, description, keywords *}
        {block 'seo'}
            <title>Admin</title>
        {/block}
        <link rel="stylesheet" href="/static/backend/dist/css/main.css?v={backend_css_version}">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link href="/static/backend/production/gotham-pro.css" rel="stylesheet">

        <script src="/static/backend/dist/js/main.js?v={backend_js_version}"></script>

        {* Another head information *}
        {block 'head'}{/block}

        {filter|unescape}
        {get_assets type="css" position='head'}
        {get_assets type="js" position='head'}
        {/filter}
    {/if}

</head>
<body class="admin-body">
    <div class="wrapper">
        {if !$.request->getIsAjax()}
            {render_flash:raw template='admin/_flash.tpl'}

            {block 'menu_block'}
                <div class="menu-block">
                    <div class="links-block clearfix">
                        <a href="/" target="_blank" class="link"></a>
                        <a href="#" class="settings" disabled=""></a>
                        <a href="{url route='admin:logout'}" class="logout"></a>
                    </div>
                    <div class="menu-wrapper">
                        {*<div class="search-block">*}
                            {*<input type="text" data-menu-search placeholder="search...">*}
                        {*</div>*}
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
                                                    <a href="{$item['route']}">
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
            {/block}
        {/if}

        <div class="main-block {block 'main_block_class'}{/block}">
            {render_breadcrumbs:raw template="admin/_breadcrumbs.tpl"}

            {if $.block.heading}
                <div class="heading">
                    {block 'heading'}{/block}
                </div>
            {/if}

            {block 'main_block'}

            {/block}
        </div>
    </div>

    {block 'js'}

    {/block}

    {filter|unescape}
    {get_assets type="css"}
    {get_assets type="js"}
    {/filter}
</body>
</html>