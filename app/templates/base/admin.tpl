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
                                    {include 'base/modules_admin_menu.tpl'}
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