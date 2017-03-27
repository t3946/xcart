<link rel="stylesheet" href="/static/backend/dist/css/main.css?v={backend_css_version}">
<script type="text/javascript" src="/static/backend/dist/js/main.js?v={backend_js_version}"></script>

{block 'js-head'}

{/block}

{filter|strip:true}
    <div id="wrapper" class="admin">
        {block 'content-header'}
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
                        <div class="row">
                            <div class="columns large-12">
                                {block 'menu_block'}
                                    {include 'base/_admin_menu.tpl'}
                                {/block}
                            </div>
                        </div>

                    {/block}
                </div>
            {/block}
        </div>

        <div id="push"></div>
    </div>
{/filter}

{block 'js'}

{/block}