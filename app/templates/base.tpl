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
                        <ul class="our-websites">
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
                <div class="row">
                    <div class="columns small-1 show-for-small hide-for-large">
                        <a href="#" data-toggle="offCanvasLeft" class="mobile_menu"></a>
                    </div>
                    <div class="columns small-3">
                        <img src="/static/frontend/dist/images/home/1280/artist_supply_sourсe_logo.svg" alt="Artist Supply Source" class="show-for-large logo-big">
                        <img src="/static/frontend/dist/images/home/768/logo.svg" alt="Artist Supply Source" class="show-for-small hide-for-large logo-small">
                    </div>
                </div>
            </section>

        </header>
    </div>


</section>



<div id="main_wrapper2" class="hide">
    <div class="shadow"></div>
    <div class="">
        <div class="row-offcanvas row-offcanvas-left">
            <div class="hidden-lg">
                <div class="navbar-offcanvas sidebar-offcanvas navbar-offcanvas-touch accordion-mob hidden-lg" id="departments-offcanvas">

                    {include "demo/blocks/_menu_mobile.tpl"}
                </div>
            </div>
            <div class="main-canvas">
                <header id="main_header">
                    <div class="">

                        <div class="">

                            <div class="container-fluid second-header-row ">
                                <div class="row1">
                                    <div class="col col-xs-30 col-sm-5 col-md-4 ">
                                        <a href="#" class="mobile__departments offcanvas-toggle hidden-lg" type="button" data-toggle="offcanvas" data-target="#departments-offcanvas"></a>

                                        <div class="main-logo">
                                            <a href="#" class="main-logo__image mobile__logo">
                                                <img src="/static/frontend/dist/images/home/1280/artist_supply_sourсe_logo.svg" alt="Artist Supply Source" class="visible-lg">
                                                <img src="/static/frontend/dist/images/home/768/logo.svg" alt="Artist Supply Source" class="hidden-lg">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col hidden-xs col-lg-45 col-sm-32 col-md-40">
                                        <menu class="main-menu">
                                            <li><a href="#">Shipping & Delivery</a></li>
                                            <li><a href="#">Safe & Secure Shopping</a></li>
                                            <li class="hidden-sm"><a href="#">About Us</a></li>
                                            <li class="hidden-sm"><a href="#">Contact Us</a></li>
                                            <li class="hidden-sm"><a href="#">Testimonials</a></li>
                                        </menu>
                                    </div>

                                    <div class="col col-xs-10 col-sm-6 col-md-4 visible-xs visible-sm">
                                        <a class="mobile__search-btn" role="button" data-toggle="collapse" href="#search"></a>
                                    </div>

                                    <div class="col col-xs-10 col-sm-6 col-md-4 hidden-lg">
                                        <a href="#" class="mobile__cart">
                                        <span class="mobile__count">
                                            <span class="mc_block">
                                                15
                                            </span>
                                        </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    {*<div class="top-block affix-top" data-spy="affix" data-offset-top="106">*}
                        {*<div class="container top-block-inner">*}
                            {*<div class="row">*}
                                {*<div class="col col-lg-15 hidden-xs hidden-sm hidden-md departments__toggle-container">*}
                                    {*{include "demo/blocks/_menu_desktop.tpl"}*}
                                {*</div>*}

                                {*<div class="col col-lg-35 col col-xs-60">*}
                                    {*{include "demo/blocks/_search.tpl"}*}
                                {*</div>*}

                                {*<div class="col col-lg-10 hidden-xs hidden-sm hidden-md cart-lg-container">*}
                                    {*{include "demo/blocks/_cart_desktop.tpl"}*}
                                {*</div>*}
                            {*</div>*}

                        {*</div>*}
                    {*</div>*}
                </header>


                <main role="main" id="main_container">
                    <div class="container">
                        {*{block "content"}{/block}*}

                        <a class="up-btn hidden-sm hidden-md hidden-lg" href="#main_header">up</a>
                    </div>
                </main>
                {*{include "base/_footer.tpl"}*}
            </div>
        </div>
    </div>
    {*<div class="nav-page-fixed hidden-xs hidden-sm hidden-md">*}
        {*<a href="#main_header" class="nav-page-fixed__btn nav-page-fixed__btn_up">up</a>*}
        {*<a href="#main_footer" class="nav-page-fixed__btn nav-page-fixed__btn_down">down</a>*}
    {*</div>*}
</div>
    {*{include "demo/blocks/_quick_view.tpl"}*}
{/block}