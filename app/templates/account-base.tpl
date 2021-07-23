{extends "wrapper.tpl"}
{block "wrapper"}
    <div id="main_wrapper" class="off-canvas-wrapper">
        <div class="off-canvas-content" data-off-canvas-content>
            <div id="content-wrapper">
                <div id="content">
                    {block "search-menu"}
                        <div class="sticky-menu-container">
                            <div class="sticky def-zi2" style="width: 100%">
                                <div id="search_container" class="desktop_menu_search_cart show-for-large"
                                     data-toggler="show-for-large">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-3 show-for-large">

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

                                            <div class="col-lg-5 col-sm-12">
                                                {insert "_parts/_search.tpl"}
                                            </div>

                                            <div class="large-2 show-for-large hat-login-button-column">
                                                <a href="/account/login/" class="hat-login-button">log in</a>
                                            </div>

                                            <div class="col-lg-2 show-for-large">
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
                </div>
            </div>
        </div>
    </div>
{/block}