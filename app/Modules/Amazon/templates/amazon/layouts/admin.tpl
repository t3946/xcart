<link rel="stylesheet" href="/static/backend/dist/css/main.css?v={backend_css_version}">

{block 'js-head'}

{/block}

{filter|strip:false}
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

                    {block 'content'}

                    {/block}

                    {block 'after-content'}

                    {/block}

                    {block 'menu_block'}
                        {include 'base/_admin_menu.tpl'}
                    {/block}
                </div>
            {/block}
        </div>

        <div id="push"></div>
    </div>
{/filter}

{block 'js'}

{/block}