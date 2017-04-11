{extends "base/head.tpl"}
{block "wrapper"}
<section id="main_wrapper" class="off-canvas-wrapper">
    <div class="off-canvas position-left" id="offCanvasLeft" data-off-canvas data-transition="push">
        {include "demo/blocks/_menu_mobile.tpl"}
    </div>
    <div class="off-canvas-content" data-off-canvas-content>
        <header>
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
                            {include "demo/blocks/_call_in_hours.tpl"}
                            {*{include "demo/blocks/_call_after_hours.tpl"}*}

                            <a href="#" class="lang"> </a>
                        </section>
                    </div>
                </div>
            </section>

            <section class="logo_menu">
                <div class="row align-justify">
                    <div class="columns small-2 medium-1 show-for-small hide-for-large">
                        <a href="#" data-toggle="offCanvasLeft" class="mobile_menu middle-inline-block"></a>
                    </div>
                    <div class="columns small-3 medium-2">
                        <img src="/static/frontend/dist/images/home/1280/artist_supply_sourсe_logo.svg" alt="Artist Supply Source" class="show-for-large logo-big">
                        <img src="/static/frontend/dist/images/home/768/logo.svg" alt="Artist Supply Source" class="show-for-small hide-for-large logo-small">
                    </div>

                    <div class="columns small-3 medium-7 large-push-3">
                        <section class="main-menu-wrap">
                            <menu class="main-menu no-bullet show-for-medium">
                                {foreach $.getMenu('main-menu') as $item index=$index}
                                    <li class="{$item.class} {if $index > 1}hide-in-medium{/if}">
                                        <a href="{$item.link}">
                                            {$item.name}
                                        </a>
                                    </li>
                                {/foreach}
                            </menu>
                        </section>
                    </div>

                    <div class="columns hide-for-large small-2 medium-1">
                        <a class="mobile__search-btn middle-inline-block" href="#search"></a>
                    </div>

                    <div class="columns hide-for-large small-2 medium-1">
                        <a href="#" class="mobile__cart middle-inline-block">
                            <span class="count">
                                <span class="mc_block">
                                    15
                                </span>
                            </span>
                        </a>
                    </div>

                </div>
            </section>
            <div data-sticky-container>
                <section class="desktop_menu_search_cart show-for-large" data-sticky data-options="marginTop:0; anchor:content; " data-btm-anchor="content:bottom">
                    <div class="row" >
                        <div class="columns large-3">
                            {*{include "demo/blocks/_menu_desktop.tpl"}*}
                        </div>
                    </div>
                </section>
            </div>
        </header>


        <section id="content">
            {*{block "content"}{/block}*}
        </section>

    </div>
</section>
{/block}