{extends "wrapper.tpl"}
{block "wrapper"}
    <div id="main_wrapper" class="off-canvas-wrapper">
        <div class="off-canvas-content" data-off-canvas-content>
            <div id="content-wrapper">
                {block "header"}
                    <div id="top-header-content-container">1</div>
                {/block}

                <div id="content">
                    {block "search-menu"}
                        <div class="sticky-menu-container">
                            <div class="sticky def-zi2" style="width: 100%">
                                <div id="search_container" class="desktop_menu_search_cart show-for-large"
                                     data-toggler="show-for-large">
                                    <div class="row">
                                        <div class="columns large-3 show-for-large">

                                            <div class="category-menu-container">
                                                <div class="category-menu">
                                                    <span class="menu-icon"></span>
                                                    <span class="category-menu-title">{t 'Departments'}</span>
                                                </div>
                                            </div>
                                            {if constant('APP_LOCAL')}
                                                {cache key = '_parts/_menu_desktop.tpl'}
                                                {insert "_parts/_menu_desktop.tpl"}
                                                {/cache}
                                            {else}
                                                {insert "_parts/_menu_desktop.tpl"}
                                            {/if}
                                        </div>

                                        <div class="large-9 columns flex-container align-middle">
                                            {insert "_parts/_search.tpl"}
                                            <div class="show-for-large search-line_old-buttons flex-container">
                                                <a href="{$.app->router->url('account:login')}" class="hat-login-button">log in</a>

                                                {include "_parts/_cart.tpl"}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    {/block}

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

                    <div class="after-content">
                        {block "after-content"}
                            <div class="row">
                                <div class="small-12 column slider-viewed">
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

        {*{block 'offcanvas-menu-right'}*}
        {*<div class="off-canvas position-right hide" id="offCanvasRight" data-off-canvas data-transition="push">*}
        {*{insert "_parts/_menu_mobile_pages.tpl"}*}
        {*</div>*}
        {*{/block}*}

    </div>
{/block}
{block "noindex"}
    <link rel="stylesheet" href="/static/frontend/dist/css/bootstrap.min.css#{mt_rand(0, 1000)}">
{/block}
