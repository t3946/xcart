{extends "base/head.tpl"}
{block "wrapper"}
<div id="main_wrapper">
    <div class="shadow"></div>
    <div class="container">
        <div class="row row-offcanvas row-offcanvas-left">
            <div class="col-xs-60 col-sm-60 col-md-60 hidden-lg">
                <div class="navbar-offcanvas sidebar-offcanvas navbar-offcanvas-touch accordion-mob hidden-lg" id="departments-offcanvas">

                    {include "demo/blocks/_menu.tpl"}
                </div>
            </div>
            <div class="col-xs-60 col-sm-60 col-md-60 col-lg-60 main-canvas">
                <header id="main_header">
                    <div class="container">
                        <div class="desktop-wrapper">
                            <div class="top-header-row">
                                <div class="row">
                                    <div class="col-sm-28 col-md-20 col-lg-20">
                                        <ul class="our-websites">
                                            <li class="current"><a href="#">Artist</a></li>
                                            <li><a href="#">Teacher</a></li>
                                            <li><a href="#">Kids</a></li>
                                            <li><a href="#">Sport</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-32 col-md-40 col-lg-40">
                                        {*{include "demo/blocks/_call_in_hours.tpl"}*}
                                        {include "demo/blocks/_call_after_hours.tpl"}
                                    </div>
                                </div>
                            </div>
                            <div class="second-header-row hidden-xs hidden-sm hidden-md">
                                <div class="row">
                                    <div class="col-lg-15">
                                        <div class="main-logo">
                                            <a href="#" class="main-logo__image">
                                                <img src="/static/frontend/dist/images/home/1280/artist_supply_sourсe_logo.svg" alt="Artist Supply Source"
                                                     width="174" height="62" title="Artist Supply Source">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-45">
                                        <menu class="main-menu">
                                            <li><a href="#">Shipping & Delivery</a></li>
                                            <li><a href="#">Safe & Secure Shopping</a></li>
                                            <li><a href="#">About Us</a></li>
                                            <li><a href="#">Contact Us</a></li>
                                            <li><a href="#">Testimonials</a></li>
                                        </menu>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mobile">
                            <div class="row">
                                <div class="col-xs-10 col-sm-5 col-md-4">
                                    <a href="#" class="mobile__departments offcanvas-toggle" type="button" data-toggle="offcanvas" data-target="#departments-offcanvas"></a>
                                </div>
                                <div class="col-xs-30 col-sm-11 col-md-8">
                                    <a href="#" class="mobile__logo"></a>
                                </div>
                                <div class="hidden-xs col-sm-32 col-md-40">
                                    <div class="main-menu-block">
                                        <menu class="main-menu">
                                            <li><a href="#">Shipping & Delivery</a></li>
                                            <li><a href="#">Safe & Secure Shopping</a></li>
                                            <li class="hidden-sm"><a href="#">About Us</a></li>
                                            <li class="hidden-sm"><a href="#">Contact Us</a></li>
                                            <li class="hidden-sm"><a href="#">Testimonials</a></li>
                                        </menu>
                                    </div>
                                </div>
                                <div class="col-xs-10 col-sm-6 col-md-4">
                                    <a class="mobile__search-btn" role="button" data-toggle="collapse" href="#search"></a>
                                </div>
                                <div class="col-xs-10 col-sm-6 col-md-4">
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



                    <div class="top-block affix-top" data-spy="affix" data-offset-top="106">
                        <div class="top-block-inner">
                            <div class="row">
                                <div class="col-lg-15 hidden-xs hidden-sm hidden-md departments__toggle-container">
                                    <div class="departments__toggle">
                                        <a href="#" class="departments__link">Departments</a>
                                    </div>
                                    <div class="departments-menu-desktop hidden-xs hidden-sm hidden-md">
                                        <nav class="departments-menu-desktop__categories">
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15 departments__menu-desktop__category_block">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="artist-brush">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/artist_brush.svg" alt="Artist Brush"><span>Artist Brush</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright artist-brush">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Brushes by Medium or Technique</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom"><a href="#">Brushes by Name or Shape</a></h4>

                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Artist Brush departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="arts-crafts">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/arts_crafts.svg" alt="Arts and Crafts"><span>Arts & Crafts</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright arts-crafts">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Arts and Crafts</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom"><a href="#"> Brushes by Name or Shape</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Arts and Crafts departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="markers-pens">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/markers_pans.svg" alt="Markers and Pens"><span>Markers and Pens</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright markers-pens">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Markers and Pens</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom"><a href="#">Brushes by Name or Shape</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a> </h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Markers and Pens departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="stencils-arch">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/stenscils.svg" alt="Stencils and Architectural Templates"><span>Stencils and Architectural Templates</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright stencils-arch">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Stencils and Architectural Templates</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom"><a href="#">Brushes by Name or Shape</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Stencils and Architectural Templates departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="adhesives-fasteners">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/adhesives_fasteners.svg" alt="Adhesives and Fasteners"><span>Adhesives and Fasteners</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright adhesives-fasteners">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Adhesives and Fasteners</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom"><a href="#">Brushes by Name or Shape</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Adhesives and Fasteners departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="airbrushing">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/airbrushing.svg" alt="Airbrushing"><span>Airbrushing</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright airbrushing">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Airbrushing</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom"><a href="#">Brushes by Name or Shape</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Airbrushing departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="books-dvd">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/books_dvd.svg" alt="Books and DVDs"><span>Books and DVDs</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright books-dvd">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Books and DVDs</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom"><a href="#">Brushes by Name or Shape</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Books and DVDs departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="canvas-linen">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/canvas.svg" alt="Canvas, Linen and Painting Surfaces"><span>Canvas, Linen and Painting Surfaces</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright canvas-linen">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Canvas, Linen and Painting Surfaces</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom"><a href="#">Brushes by Name or Shape</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a> </h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Canvas, Linen and Painting Surfaces departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="ceramics-pottery">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/ceramics_pottery.svg" alt="Ceramics and Pottery"><span>Ceramics and Pottery</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright ceramics-pottery">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Ceramics and Pottery</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom"><a href="#">Brushes by Name or Shape</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Ceramics and Pottery departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="cleaning-supplies">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/cleaning_supplies.svg" alt="Cleaning Supplies for Craft Mishaps"><span>Cleaning Supplies for Craft Mishaps</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright cleaning-supplies">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Cleaning Supplies for Craft Mishaps</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom"><a href="#">Brushes by Name or Shape</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Cleaning Supplies for Craft Mishaps departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="cutting-tools">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/cutting_tools.svg" alt="Cutting Tools"><span>Cutting Tools</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright cutting-tools">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Cutting Tools</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom">Brushes by Name or Shape </h4>
                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Cutting Tools departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="departments-menu-desktop__block">
                                                <div class="row">
                                                    <div class="col-lg-15">
                                                        <h4 class="departments-menu-desktop__category_header" data-toggle="drafting-arch">
                                                            <a href="#">
                                                                <img src="/static/frontend/dist/images/home/1280/subdepartments/draftind_architecture.svg" alt="Drafting and Architecture"><span>Drafting and Architecture</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-45 hidden-lg departments-menu-desktop__dropright_block">
                                                        <div class="departments-menu-desktop__dropright drafting-arch">
                                                            <nav class="departments-menu-desktop__dropright-nav">
                                                                <div class="row">
                                                                    <div class="col-lg-15">
                                                                        <h4><a href="#">Drafting and Architecture</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Acrylic and Oil Brushes</a></li>
                                                                            <li><a href="#">Brush Techniques Demonstration</a></li>
                                                                            <li><a href="#">Paper</a></li>
                                                                            <li><a href="#">Ceramic and Glazing Brushes</a></li>
                                                                            <li><a href="#">Decorative and Miniature Brushes</a></li>
                                                                            <li><a href="#">Encaustic Brushes</a></li>
                                                                            <li><a href="#">Faux Finishing Brushes and Tools</a></li>
                                                                            <li><a href="#">Gilding Brushes</a></li>
                                                                            <li><a href="#">Lettering Brushes</a></li>
                                                                            <li><a href="#">Multi-Purpose and Utility Brushes</a></li>
                                                                            <li><a href="#">Mural and Fresco Brushes</a></li>
                                                                            <li><a href="#">Oriental and Sumi Brushes</a></li>
                                                                            <li><a href="#">Paint Rollers</a></li>
                                                                            <li><a href="#">Painting and Palette Knives</a></li>
                                                                            <li><a href="#">Stencil Brushes</a></li>
                                                                            <li><a href="#">Striping Brushes</a></li>
                                                                            <li><a href="#">Varnish and Gesso Brushes</a></li>
                                                                            <li><a href="#">Watercolor Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5">
                                                                        <h4><a href="#">Brushes by Hair or Fiber</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Badger Brushes</a></li>
                                                                            <li><a href="#">Bristle Brushes</a></li>
                                                                            <li><a href="#">Sable/Kolinsky Brushes</a></li>
                                                                            <li><a href="#">Squirrel Brushes</a></li>
                                                                            <li><a href="#">Synthetic Brushes</a></li>
                                                                        </ul>

                                                                        <h4 class="departments-menu-desktop__dropright-nav-middle-bottom"><a href="#">Brushes by Name or Shape</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Angular</a></li>
                                                                            <li><a href="#">Bright</a></li>
                                                                            <li><a href="#">Fan</a></li>
                                                                            <li><a href="#">Filbert</a></li>
                                                                            <li><a href="#">Flat</a></li>
                                                                            <li><a href="#">Hake</a></li>
                                                                            <li><a href="#">Highliner</a></li>
                                                                            <li><a href="#">Mop</a></li>
                                                                            <li><a href="#">Mottler</a></li>
                                                                            <li><a href="#">One Stroke</a></li>
                                                                            <li><a href="#">Oval Wash</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-15 col-lg-offset-5 departments-menu-desktop__dropright-nav-right-container">
                                                                        <h4><a href="#">Scholastic Brushes</a></h4>
                                                                        <ul>
                                                                            <li><a href="#">Black Bristle</a></li>
                                                                            <li><a href="#">Camel/Pony</a></li>
                                                                            <li><a href="#">Colored Synthetic</a></li>
                                                                            <li><a href="#">Foam and Sponge Brushes</a></li>
                                                                            <li><a href="#">Golden Synthetic</a></li>
                                                                            <li><a href="#">Scholastic Sable</a></li>
                                                                            <li><a href="#">White Bristle</a></li>
                                                                            <li><a href="#">White Synthetic</a></li>
                                                                            <li><a href="#">Brushes</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <div class="departments-menu-desktop__sale">
                                                                <span class="departments-menu-desktop__sale-pink">Sale</span>
                                                                <span class="departments-menu-desktop__sale-description">of brushes for make-up</span>
                                                                <a href="#" class="departments-menu-desktop__sale-learn-more">Learn more</a>
                                                            </div>
                                                            <div class="departments-menu-desktop__dropright-view-all-row">
                                                                <a href="#" class="departments-menu-desktop__dropright-view-all">View
                                                                    all Drafting and Architecture departments</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="#" class="departments-menu-desktop__view-all">View all departments</a>
                                        </nav>
                                    </div>
                                </div>
                                <div class="col-lg-35 col-xs-60">
                                    <div class="search-lg panel-collapse collapse" id="search">
                                        <form action="search.php" name="search" method="get" class="search-lg__form">
                                            <input type="text" class="search-lg__input search-for" placeholder="" id="search_main_input" />
                                            <button type="submit" class="search-lg__button"></button>
                                            <div class="search-lg__remove-text remove-text"></div>
                                            <div class="hidden-search">
                                                <div class="block">
                                                    <span class="search__category">Search suggestions</span>
                                                    <div class="clear"></div>
                                                    <ul>
                                                        <li><a><b>Brushes</b> oil </a></li>
                                                        <li><a><b>Brushes</b> for  </a></li>
                                                        <li><a><b>Brushes</b> acrylic </a></li>
                                                        <li><a><b>Brushes</b> scrubber  </a></li>
                                                    </ul>
                                                </div><!--end block-->
                                                <div class="block search__categories_block">
                                                    <span class="search__category">Categories</span>
                                                    <div class="clear"></div>
                                                    <ul>
                                                        <li><a>Air<b>brush</b>ing</a></li>
                                                        <li><a><b>Brush</b> Accessories </a></li>
                                                        <li><a><b>Brush</b> Furniture for Artists</a></li>
                                                        <li><a>Paintings and Painting <b>Brush</b> Accessories</a></li>
                                                    </ul>
                                                </div><!--end block-->
                                                <div class="block with-icons">
                                                    <span class="search__category">Products</span>
                                                    <div class="clear"></div>
                                                    <ul>
                                                        <li><a><i class="image-block"><i><img src="/static/frontend/dist/images/home/1280/item1.jpg" alt=""></i></i>Reeves Artist <b>Brush</b> Roll </a></li>
                                                        <li><a><i class="image-block"><i><img src="/static/frontend/dist/images/home/1280/item1.jpg" alt=""></i></i>Reeves <b>Brush</b> Set: Watercolor </a></li>
                                                        <li><a><i class="image-block"><i><img src="/static/frontend/dist/images/home/1280/item1.jpg" alt=""></i></i>Mod Podge <b>Brush</b> Applicator</a></li>
                                                        <li><a><i class="image-block"><i><img src="/static/frontend/dist/images/home/1280/item1.jpg" alt=""></i></i>Copic Multiliner <b>Brush</b>: Medium </a></li>
                                                        <li><a><i class="image-block"><i><img src="/static/frontend/dist/images/home/1280/item1.jpg" alt=""></i></i>Alvin Mini Dusting <b>Brush</b></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-10 hidden-xs hidden-sm hidden-md cart-lg-container">
                                    <div class="cart-lg">
                                        <div class="cart-lg__wrapper">
                                            <a href="#" class="cart-lg__in"><span class="cart-lg__count">5</span></a>
                                            <a href="#" class="cart-lg__link">Cart</a>
                                        </div>
                                    </div>
                                    <div class="cart_hide">
                                        <div class="cart__scroll-block">
                                            <div class="cart__outer">
                                                <div class="cart__block">
                                                    <div class="cart__image-block">
                                                        <a class="cart__image-block_link" href="#">
                                                            <img src="/static/frontend/dist/images/home/1280/item1.jpg" alt="">
                                                        </a>
                                                    </div>
                                                    <a class="cart__del-button"></a>
                                                    <div class="cart__product">
                                                        <a class="cart__product_name" href="#">Wicked Color Airbrush Paint:
                                                            6-Color Set, Primary...</a>
                                                        <input type="text" class="cart__product_count" value="1">
                                                        <i class="cart__product_multiply">x</i>
                                                        <b class="cart__product_price">US$ 15.48</b>
                                                        <p class="clear"></p>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="cart__block">
                                                    <div class="cart__image-block">
                                                        <a class="cart__image-block_link" href="#">
                                                            <img src="/static/frontend/dist/images/home/1280/item1.jpg" alt="">
                                                        </a>
                                                    </div>
                                                    <a class="cart__del-button"></a>
                                                    <div class="cart__product">
                                                        <a class="cart__product_name" href="#">Wicked Color Airbrush Paint:
                                                            6-Color Set, Primary...</a>
                                                        <input type="text" class="cart__product_count" value="1">
                                                        <i class="cart__product_multiply">x</i>
                                                        <b class="cart__product_price">US$ 15.48</b>
                                                        <p class="clear"></p>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="cart__block">
                                                    <div class="cart__image-block">
                                                        <a class="cart__image-block_link" href="#">
                                                            <img src="/static/frontend/dist/images/home/1280/item1.jpg" alt="">
                                                        </a>
                                                    </div>
                                                    <a class="cart__del-button"></a>
                                                    <div class="cart__product">
                                                        <a class="cart__product_name" href="#">Wicked Color Airbrush Paint:
                                                            6-Color Set, Primary...</a>
                                                        <input type="text" class="cart__product_count" value="1">
                                                        <i class="cart__product_multiply">x</i>
                                                        <b class="cart__product_price">US$ 15.48</b>
                                                        <p class="clear"></p>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="cart__block">
                                                    <div class="cart__image-block">
                                                        <a class="cart__image-block_link" href="#">
                                                            <img src="/static/frontend/dist/images/home/1280/item1.jpg" alt="">
                                                        </a>
                                                    </div>
                                                    <a class="cart__del-button"></a>
                                                    <div class="cart__product">
                                                        <a class="cart__product_name" href="#">Wicked Color Airbrush Paint:
                                                            6-Color Set, Primary...</a>
                                                        <input type="text" class="cart__product_count" value="1">
                                                        <i class="cart__product_multiply">x</i>
                                                        <b class="cart__product_price">US$ 15.48</b>
                                                        <p class="clear"></p>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cart__button-block">
                                            <a class="cart__button_view" href="#">view cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </header>


                <main role="main" id="main_container">
                    <div class="container">
                        {block "content"}
                        {/block}
                    </div>
                </main>
                {include "base/footer.tpl"}
            </div>
        </div>
    </div>
    <div class="nav-page-fixed hidden-xs hidden-sm hidden-md">
        <a href="#main_header" class="nav-page-fixed__btn nav-page-fixed__btn_up">up</a>
        <a href="#main_footer" class="nav-page-fixed__btn nav-page-fixed__btn_down">down</a>
    </div>
</div>
    {include "demo/blocks/_quick_view.tpl"}
{/block}