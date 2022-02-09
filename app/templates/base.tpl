{extends "wrapper.tpl"}
{block "wrapper"}
    <div id="main_wrapper" class="off-canvas-wrapper account-main-wrapper account_main-wrapper">
        <div class="off-canvas-content" data-off-canvas-content>
            <div id="content-wrapper">
                {block "header"}
                    <div id="header-target">
                        <div class="container skeleton d-none d-lg-block">
                            <div class="skeleton-box hat-skeleton"></div>
                        </div>

                        <div class="skeleton-box hat-skeleton d-lg-none"></div>
                    </div>
                {/block}

                <div id="content" class="container">
                    {block "search-menu"}{/block}
                    <div id="hat-search-line-target"></div>

                    <div class="before-content container">
                        {block "before-content"}
                            <div class="row">
                                <div class="col-12">
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

                    <div class="after-content container">
                        {block "after-content"}
                            <div class="row">
                                <div class="col-12 slider-viewed">
                                    {set $link}{url 'catalog:viewed'}{/set}
                                    {set $lbl}{t 'You recently viewed items'}{/set}
                                    {include 'slider/base_product_slider.tpl' title=$lbl link=$link hide=false hide_link=true}
                                </div>
                            </div>
                        {/block}
                    </div>
                </div>
            </div>

            {insert "_parts/_footer.tpl"}
        </div>


        {block 'offcanvas-menu-left'}
            <div class="off-canvas position-left hide" id="offCanvasLeft" data-off-canvas data-transition="push">
                {insert "_parts/_menu_mobile.tpl"}
            </div>
        {/block}
    </div>
{/block}
{block "noindex"}
    <link rel="stylesheet" href="/static/frontend/dist/css/bootstrap.min.css#{mt_rand(0, 1000)}">
{/block}
