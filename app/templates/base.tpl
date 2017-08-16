{extends "base/head.tpl"}
{block "wrapper"}
<section id="main_wrapper" class="off-canvas-wrapper">
    <div class="off-canvas position-left" id="offCanvasLeft" data-off-canvas data-transition="push">

    {cache key = "_parts/_menu_mobile.tpl"}
        {insert "_parts/_menu_mobile.tpl"}
    {/cache}
    </div>
    <div class="off-canvas-content" data-off-canvas-content>
        <header itemscope itemtype="http://schema.org/WPHeader">
            <section class="top-header hide-for-small-only">
                <div class="row">
                    <div class="columns small-4">
                        <ul class="our-websites no-bullet">
                            <li class="current"><span>Artist</span></li>
                            <li><a href="#">Teacher</a></li>
                            <li><a href="#">Kids</a></li>
                            <li><a href="#">Sport</a></li>
                        </ul>
                    </div>
                    <div class="columns small-8">
                        <section class="call_lang">
                            {insert "demo/blocks/_call_in_hours.tpl"}
                            {*{include "demo/blocks/_call_after_hours.tpl"}*}

                            <a href="#" class="lang"> </a>
                        </section>
                    </div>
                </div>
            </section>

            <section class="logo_menu">
                <div class="row align-justify">
                    <div class="columns small-2 medium-1 show-for-small hide-for-large">
                        <a href="#" data-toggle="offCanvasLeft" class="mobile_menu middle-inline-block hamburger"></a>
                    </div>
                    <div class="columns small-3 medium-2">
                        <img src="/static/frontend/demo_images/home/1280/artist_supply_sourсe_logo.svg" alt="{$.getSiteConfig->company_name->value}" class="show-for-large logo-big">
                        <img src="/static/frontend/demo_images/home/768/logo.svg" alt="{$.getSiteConfig->company_name->value}" class="show-for-small hide-for-large logo-small">
                    </div>

                    <div class="columns small-3 medium-7 large-push-3">
                        <section class="main-menu-wrap">
                            <ul class="main-menu no-bullet show-for-medium">
                                {get_menu code='main-menu'}
                            </ul>
                        </section>
                    </div>

                    <div class="columns hide-for-large small-2 medium-1">
                        <a class="mobile__search-btn middle-inline-block" data-toggle="search_container"></a>
                    </div>

                    <div class="columns hide-for-large small-2 medium-1">
                        <a href="{url "catalog:cart:list"}" class="mobile__cart middle-inline-block">
                            <span class="count">
                                <span class="mc_block">
                                    {$.app->cart->getQuantity()}
                                </span>
                            </span>
                        </a>
                    </div>

                </div>
            </section>
            <div class="row">
                <div class="columns small-12">
                    <div class="hr hide-for-large"></div>
                </div>
            </div>
        </header>

        <section id="content-wrapper">
            <div class="shadow"></div>
            <div data-sticky-container class="sticky-container">
                <div class="sticky def-zi2" data-sticky data-sticky-on="large" data-options="marginTop:0; anchor:content-wrapper;" data-btm-anchor="content:bottom">
                    <section id="search_container" class="desktop_menu_search_cart show-for-large" data-toggler="show-for-large" >
                        <div class="row" >
                            <div class="columns large-3 show-for-large">

                                <section class="category-menu-container"  >
                                    <div class="category-menu">
                                        <span class="menu-icon"></span>
                                        <span class="category-menu-title" >Departments</span>
                                    </div>
                                </section>
                                {cache key = '_parts/_menu_desktop.tpl'}
                                    {insert "_parts/_menu_desktop.tpl"}
                                {/cache}
                            </div>
                            <div class="columns small-12 large-7">
                                {insert "_parts/_search.tpl"}
                            </div>

                            <div class="columns large-2 show-for-large">
                                {include "_parts/_cart.tpl"}
                            </div>
                        </div>
                    </section>

                </div>
            </div>

            <section id="content">
                <section class="before-content">
                    {block "before-content"}
                        <div class="row">
                            <div class="columns large-12">
                                {insert "base/_breadcrumbs.tpl"}
                            </div>
                        </div>
                    {/block}
                </section>

                {render_flash:raw template='base/_flash.tpl'}

                {block "content"}{/block}

                <section class="after-content">
                    {block "after-content"}{/block}
                </section>
            </section>
        </section>

    </div>
</section>
{/block}