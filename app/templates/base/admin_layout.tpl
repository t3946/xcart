{if !$.request->getIsAjax()}
    {*<link rel="stylesheet" href="/static/backend/dist/css/main.css?v={backend_version resource='main.css'}">*}
    {*<script src="/static/backend/dist/js/main.js?v={backend_version resource='main.js'}"></script>*}

    {* Another head information *}
    {block 'head'}{/block}

    {filter|unescape}
    {get_assets type="css" position='head'}
    {get_assets type="js" position='head'}
    {/filter}
{/if}

{block 'js-head'}

{/block}

{filter|strip:false}
    <div id="wrapper" class="wrapper {block 'wrapper_block_class'}{/block}">
        {block 'content-header'}
            <div class="content-header">
                {block 'breadcrumbs'}
                    {render_breadcrumbs:raw template="admin/_breadcrumbs.tpl"}
                {/block}
                <div class="row">
                    <div class="column large-12">

                        {if $.block.heading}
                            <div class="heading">
                                {block 'heading'}{/block}
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
        {/block}

        <div id="main" class="main-block {block 'main_block_class'}{/block}">
            {block 'main_block'}
                {block 'main'}
                    <div class="main-content">
                        {block 'before-content'}

                        {/block}

                        {*{smarty_admin_block}*}
                        {block 'content'}

                        {/block}
                        {*{/smarty_admin_block}*}

                        {block 'after-content'}

                        {/block}

                        {block 'menu_block'}
                            {*{include 'base/_admin_menu.tpl'}*}
                        {/block}
                </div>
                {/block}
            {/block}
        </div>

        <div id="push"></div>
    </div>
{/filter}

{block 'js'}

{/block}

{filter|unescape}
{get_assets type="css"}
{get_assets type="js"}
{/filter}