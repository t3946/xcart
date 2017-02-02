<link rel="stylesheet" href="/static/backend/css/base.css?v=1">

<script src="/static/vendors/jquery.cookie-1.4.1.min.js"></script>
<script type="text/javascript" src="/static/backend/js/main.js?v=1" async></script>

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

                {/block}
            </div>
        {/block}
    </div>

    <div id="push"></div>
</div>

{block 'js'}

{/block}