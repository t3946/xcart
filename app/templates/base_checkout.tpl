{extends "wrapper.tpl"}
{block "wrapper"}
<section id="main_wrapper" class="off-canvas-wrapper">

    <div class="off-canvas-content" data-off-canvas-content>
        <section id="content-wrapper">
            <header itemscope itemtype="http://schema.org/WPHeader">
                <section class="top-header hide-for-small-only">
                    <div class="row">
                        <div class="columns small-4">
                            <ul class="our-websites no-bullet">
                                <li class="current"><span>{$.getSite->short_name}</span></li>
                                {*<li><a href="#">Teacher</a></li>*}
                                {*<li><a href="#">Kids</a></li>*}
                                {*<li><a href="#">Sport</a></li>*}
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
                            <a href="/">
                                <img src="/static/frontend/demo_images/home/1280/artist_supply_sourсe_logo.svg" alt="{$.getSiteConfig->company_name->value}" class="show-for-large logo-big">
                                <img src="/static/frontend/demo_images/home/768/logo.svg" alt="{$.getSiteConfig->company_name->value}" class="show-for-small hide-for-large logo-small">
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

                {block "content-wrapper"}
                    {block "content"}{/block}
                {/block}

                <section class="after-content">
                    {block "after-content"}
                        <div class="row">
                            <div class="small-12 column slider-viewed">
                                {set $link}{url 'catalog:viewed'}{/set}
                                {include 'slider/base_product_slider.tpl' title="You recently viewed items" link=$link hide=true hide_link=true}
                            </div>
                        </div>
                    {/block}
                </section>
            </section>
        </section>

        {insert "_parts/_footer.tpl"}
    </div>
    <div class="off-canvas position-left hide" id="offCanvasLeft" data-off-canvas data-transition="push">
        {insert "_parts/_menu_mobile.tpl"}
    </div>
    <div class="off-canvas position-right hide" id="offCanvasRight" data-off-canvas data-transition="push">
        {insert "_parts/_menu_mobile_pages.tpl"}
    </div>

</section>
{/block}