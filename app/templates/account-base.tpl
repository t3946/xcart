{extends "wrapper.tpl"}
{block "wrapper"}
    <div id="main_wrapper" class="off-canvas-wrapper">
        <div class="off-canvas-content" data-off-canvas-content>
            <div id="content-wrapper">
                {block "header"}
                    <div id="top-header-content">
                        <div id="top-header-menu">
                            <header id="top-header" itemscope itemtype="http://schema.org/WPHeader">
                                {render_static_notifications}
                                <div class="logo_menu">
                                    <div class="container">
                                        <div class="row align-justify">
                                            <div class="col-2 col-md-1 show-for-small hide-for-large">
                                                <a href="#" data-toggle="offCanvasLeft"
                                                   class="mobile_menu middle-inline-block hamburger"></a>
                                            </div>

                                            <div class="col-3 col-md-2">
                                                <a href="/">
                                                    <img src="{$uri}/static/frontend/dist/images/logos/sites/{$.getSite->code|lower}/logo.svg"
                                                         alt="{$.getSiteConfig->company_name->value}"
                                                         class="show-for-large logo-big">
                                                    <img src="{$uri}/static/frontend/dist/images/logos/sites/{$.getSite->code|lower}/logo-small.svg"
                                                         alt="{$.getSiteConfig->company_name->value}"
                                                         class="show-for-small hide-for-large logo-small">
                                                </a>
                                            </div>

                                            <div class="col-1 col-md-5 show-for-large">
                                                <div class="main-menu-wrap">
                                                    <ul class="main-menu no-bullet show-for-medium">
                                                        {get_menu code='main-menu'}
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-6 col-md-4 hide-for-large small-offset-0 medium-offset-0 text-align--right mobile-header">

                                                <a href="tel:18009292431" class="mobile__call-btn middle-inline-block right-icon"></a>

                                                <a class="mobile__search-btn middle-inline-block right-icon" data-swich="search_container"></a>

                                                <a href="{url "cart:list"}" class="mobile__cart middle-inline-block right-icon">
                                                    <span class="count">
                                                        <span class="mc_count">
                                                            {*{$.app->cart->getQuantity()}*}
                                                        </span>
                                                    </span>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mobile-banner hide-for-medium">
                                    <div class="row align-justify">
                                        <div class="columns banner">
                                            {if $config.flat_shipping_enabled !== 'N'}
                                                <img src="{$uri}/static/frontend/dist/images/flat.png">
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            </header>
                        </div>
                        <div class="shadow"></div>
                    </div>
                {/block}

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