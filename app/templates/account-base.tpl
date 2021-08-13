{extends "wrapper.tpl"}
{block "wrapper"}
    <div id="main_wrapper" class="off-canvas-wrapper">
        <div class="off-canvas-content" data-off-canvas-content>
            <div id="content-wrapper">
                <div id="content">
                    <div class="before-content">
                        {block "before-content"}
                            <div class="row">
                                <div class="columns large-12">
                                    {insert "base/_breadcrumbs.tpl"}
                                </div>
                            </div>
                        {/block}
                    </div>

                    {block "content-wrapper"}
                        <div class="content">
                            {block "content"}{/block}
                        </div>
                    {/block}
                </div>
            </div>
        </div>
    </div>
{/block}

{block "noindex"}
    <link rel="stylesheet" href="/static/frontend/dist/css/bootstrap.min.css#{mt_rand(0, 1000)}">
{/block}

{block "noindex-footer"}
    {*
        resolve bootsrap and foundation grid systems conflict problem
    *}
    <style>
        .row {
            max-width: initial;
            -ms-flex-flow: initial;
            flex-flow: initial;
            margin-top: calc(var(--bs-gutter-y) * -1);
            margin-right: calc(var(--bs-gutter-x) * -.5);
            margin-left: calc(var(--bs-gutter-x) * -.5);
        }
    </style>
{/block}