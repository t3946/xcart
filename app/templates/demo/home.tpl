{extends "base.tpl"}

{block "content"}
    <section class="banners-section">
        <div class="row">
            <div class="col col-lg-36 col col-md-36 col col-sm-45 col col-xs-60 promotion__block">
                <div class="promotion__active-wrapper slider-horizontal__active-wrapper" id="promotion-block">
                    <div class="promotion__frame slider-horizontal__frame"
                         id="promotion_frame">
                        <ul>
                            <li class="promotion__item slider-horizontal__item">
                                <a href="#" class="slider-horizontal__item_link"> <div
                                            class="promotion__item_cover banner__cover">
                                        <h3 class="promotion__item_caption">Promotional product</h3>

                                        <p class="promotion__item_description">Try it for 90 days. Enjoy it for 25 years > </p>
                                    </div> </a> </li>
                            <li class="promotion__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_link"> <div
                                            class="promotion__item_cover banner__cover">
                                        <h3 class="promotion__item_caption">Promotional product</h3>

                                        <p class="promotion__item_description">Try it for 90 days. Enjoy it for 25 years > </p>
                                    </div> </a> </li>
                            <li class="promotion__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_link"> <div
                                            class="promotion__item_cover banner__cover">
                                        <h3 class="promotion__item_caption">Promotional product</h3>

                                        <p class="promotion__item_description">Try it for 90 days. Enjoy it for 25 years > </p>
                                    </div> </a> </li>
                        </ul>
                    </div>
                    <ul class="promotion__pages">
                        <li> </li>
                        <li> </li>
                        <li> </li>
                    </ul>
                </div>
            </div>
            <div class="col col-lg-12 col col-md-12 col col-sm-15 hidden-xs product-of-the-day__block">
                <div class="banner__product-of-the-day banner__wrapper">
                    <a href="#" class="banner__product-of-the-day_link banner__link">
                        <div class="product-of-the-day_cover banner__cover">
                            <div class="product-of-the-day__caption">Product оf the day</div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col col-lg-12 col col-md-12 hidden-sm hidden-xs right-banners__block">
                <div class="banner__bestsellers banner__wrapper">
                    <a href="#" class="banner__bestsellers_link banner__link">
                        <div class="bestsellers_cover banner__cover">
                            <div class="bestsellers__caption">Bestsellers</div>
                            <div class="bestsellers__description">Try it for 90 days. Enjoy it for 25 years > </div>
                        </div>
                    </a>
                </div>
                <div class="banner__whatsnew banner__wrapper">
                    <a href="#" class="banner__whatsnew_link banner__link">
                        <div class="whatsnew_cover banner__cover">
                            <div class="whatsnew__caption">What’s new</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <nav class="home-menu hidden-xs">
        <ul class="home-menu__list">
            <li class="home-menu__item home-menu__item_brands hidden-sm hidden-md"> <a href="#">Brands</a> </li>
            <li class="home-menu__item home-menu__item_whats-new"> <a href="#">What’s New</a> </li>
            <li class="home-menu__item home-menu__item_bestsellers"> <a href="#">Bestsellers</a> </li>
            <li class="home-menu__item home-menu__item_product-day hidden-sm hidden-md"> <a href="#">Product of the day</a> </li>
            <li class="home-menu__item home-menu__item_featured"> <a href="#">Featured products</a> </li>
            <li class="home-menu__item home-menu__item_sale"> <a href="#">Sale</a> </li>
        </ul>
    </nav>
    <div class="content-container">
        <section class="featured-products slider-horizontal" id="featured-products-block">
            <h3 class="featured-products__title section__title slider-horizontal__title">Featured products</h3>
            <a href="#" class="featured-products__view-all slider-horizontal__view-all section__view-all">View all</a>
            <div class="featured-products__active-wrapper slider-horizontal__active-wrapper">
                <div class="featured-products__frame slider-horizontal__frame" id="featured-prod_frame">

                    <ul>
                        <li class="featured-products__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_image-link"> <img src="/static/frontend/dist/images/home/1280/alv-rf11ds-1.png" alt="Item1"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>

                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>

                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/mak-aj-1.png" alt="Item2"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/mak-1014-3.png" alt="Item3"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/alv-4603.png" alt="Item4"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/022-alv-5207-02-1.png" alt="Item5"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/005-alv-st59-716.png" alt="Item6"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/alv-rf11ds-1.png" alt="Item1"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/mak-aj-1.png" alt="Item2"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/mak-1014-3.png" alt="Item3"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/alv-4603.png" alt="Item4"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/022-alv-5207-02-1.png" alt="Item5"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/005-alv-st59-716.png" alt="Item6"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/alv-rf11ds-1.png" alt="Item1"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/mak-aj-1.png" alt="Item2"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/mak-1014-3.png" alt="Item3"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/alv-4603.png" alt="Item4"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/022-alv-5207-02-1.png" alt="Item5"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="featured-products__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/005-alv-st59-716.png" alt="Item6"
                                                                                                                                                class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                    </ul>
                </div>
                <div class="featured-products__controls slider-horizontal__controls">
                    <a href="#" class="featured-products__controls_btn featured-products__controls_prev-page slider-horizontal__controls_btn slider-horizontal__controls_prev-page"> </a>
                    <a href="#" class="featured-products__controls_btn featured-products__controls_next-page slider-horizontal__controls_btn slider-horizontal__controls_next-page"> </a>
                </div>
                <div class="featured-products__scrollbar slider-horizontal__scrollbar">
                    <div class="featured-products__scrollbar_handle slider-horizontal__scrollbar_handle">
                        <div class="featured-products__scrollbar_mousearea slider-horizontal__scrollbar_mousearea"> </div>
                    </div>
                </div>
            </div>

        </section>
        <section class="whats-new slider-horizontal" id="whats-new-block">
            <h3 class="whats-new__title section__title slider-horizontal__title">What’s new</h3>
            <a href="#" class="whats-new__view-all slider-horizontal__view-all section__view-all">View all</a>
            <div class="whats-new__active-wrapper slider-horizontal__active-wrapper">
                <div class="whats-new__frame slider-horizontal__frame" id="whats-new_frame">
                    <ul>
                        <li class="whats-new__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_image-link"> <img src="/static/frontend/dist/images/home/1280/alv-c100516301-1.png" alt="Item1"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/08-alv-ws00301.png" alt="Item2"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/large-2048.png" alt="Item3"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/mfw-1275--.png" alt="Item4"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/act-160-2.png" alt="Item5"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/alv-17202-1.png" alt="Item6"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_image-link"> <img src="/static/frontend/dist/images/home/1280/alv-c100516301-1.png" alt="Item1"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/08-alv-ws00301.png" alt="Item2"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/large-2048.png" alt="Item3"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/mfw-1275--.png" alt="Item4"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/act-160-2.png" alt="Item5"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/alv-17202-1.png" alt="Item6"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_image-link"> <img src="/static/frontend/dist/images/home/1280/alv-c100516301-1.png" alt="Item1"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/08-alv-ws00301.png" alt="Item2"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/large-2048.png" alt="Item3"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/mfw-1275--.png" alt="Item4"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/act-160-2.png" alt="Item5"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>
                        <li class="whats-new__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/alv-17202-1.png" alt="Item6"
                                                                                                                                        class="slider-horizontal__item_image"> </a>
                            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="slider-horizontal__item_quick-view">quick view</a>
                            <h4 class="slider-horizontal__item_caption"> <a href="">Wicked Color
                                    Airbrush Paint: 6-Color Set<span
                                            class="slider-horizontal__item_caption-type">, Primary</span> </a>
                            </h4>

                            <div class="slider-horizontal__item_price"> <span
                                        class="slider-horizontal__item_price-old">US$ 25.50</span> <span
                                        class="slider-horizontal__item_price-new">US$ 15.48</span> </div>
                        </li>

                    </ul>
                </div>
                <div class="whats-new__controls slider-horizontal__controls">
                    <a href="#" class="whats-new__controls_btn whats-new__controls_prev-page slider-horizontal__controls_btn slider-horizontal__controls_prev-page"> </a>
                    <a href="#" class="whats-new__controls_btn whats-new__controls_next-page slider-horizontal__controls_btn slider-horizontal__controls_next-page"> </a>
                </div>
                <div class="whats-new__scrollbar slider-horizontal__scrollbar">
                    <div class="whats-new__scrollbar_handle slider-horizontal__scrollbar_handle">
                        <div class="whats-new__scrollbar_mousearea slider-horizontal__scrollbar_mousearea"> </div>
                    </div>
                </div>
            </div>

        </section>
        <section class="top-categories slider-horizontal hidden-xs" id="top-categories-block">
            <h3 class="top-categories__title section__title slider-horizontal__title">Top Categories</h3>
            <a href="#" class="top-categories__view-all slider-horizontal__view-all section__view-all">View all</a>
            <div class="top-categories__active-wrapper slider-horizontal__active-wrapper">
                <div class="top-categories__frame slider-horizontal__frame" id="top-categories_frame">
                    <ul>
                        <li class="top-categories__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_image-link"> <img src="/static/frontend/dist/images/home/1280/b321-1-copy.png" alt="Item1" class="slider-horizontal__item_image"> </a>

                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#" >Artist Brush</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/shutterstock-27871360.png" alt="Item2"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#">Drafting and Architecture</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/aerograph-competition-auto-art-1.png" alt="Item3"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#" >Artist Brush</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/simple-sewing-tools-mat-and-cutters.png" alt="Item4"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#">Drafting and Architecture</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/b321-1-copy.png" alt="Item5"
                                                                                                                                             class="slider-horizontal__item_image" /> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#" >Artist Brush</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/shutterstock-27871360.png" alt="Item6"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#">Drafting and Architecture</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/aerograph-competition-auto-art-1.png" alt="Item1"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#" >Artist Brush</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/simple-sewing-tools-mat-and-cutters.png" alt="Item2"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#">Drafting and Architecture</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/b321-1-copy.png" alt="Item3"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#" >Artist Brush</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/shutterstock-27871360.png" alt="Item4"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#" >Artist Brush</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/aerograph-competition-auto-art-1.png" alt="Item5"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#">Drafting and Architecture</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/simple-sewing-tools-mat-and-cutters.png" alt="Item6"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#" >Artist Brush</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/alv-rf11ds-1.png" alt="Item1"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#">Drafting and Architecture</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/mak-aj-1.png" alt="Item2"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#" >Artist Brush</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/mak-1014-3.png" alt="Item3"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#">Drafting and Architecture</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/alv-4603.png" alt="Item4"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#" >Artist Brush</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/022-alv-5207-02-1.png" alt="Item5"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#">Drafting and Architecture</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                        <li class="top-categories__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/005-alv-st59-716.png" alt="Item6"
                                                                                                                                             class="slider-horizontal__item_image"> </a>
                            <div class="slider-horizontal__item_info">
                                <h4 class="slider-horizontal__item_title"> <a href="#" >Artist Brush</a> </h4>

                                <p class="slider-horizontal__item_description">The most advantageous offer popular
                                    brands</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="top-categories__controls slider-horizontal__controls">
                    <a href="#" class="top-categories__controls_btn top-categories__controls_prev-page slider-horizontal__controls_btn slider-horizontal__controls_prev-page"> </a>
                    <a href="#" class="top-categories__controls_btn top-categories__controls_next-page slider-horizontal__controls_btn slider-horizontal__controls_next-page"> </a>
                </div>
                <div class="top-categories__scrollbar slider-horizontal__scrollbar">
                    <div class="top-categories__scrollbar_handle slider-horizontal__scrollbar_handle">
                        <div class="top-categories__scrollbar_mousearea slider-horizontal__scrollbar_mousearea"> </div>
                    </div>
                </div>
            </div>

        </section>
        <section class="brands slider-horizontal" id="brands-block">
            <h3 class="brands__title section__title slider-horizontal__title">Brands</h3>
            <a href="#" class="brands__view-all slider-horizontal__view-all section__view-all">View all</a>

            <div class="brands__active-wrapper slider-horizontal__active-wrapper">
                <div class="brands__frame slider-horizontal__frame" id="brands_frame">
                    <ul>
                        <li class="brands__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_image-link"> <img src="/static/frontend/dist/images/home/1280/004-alvin.png" alt="Item1"
                                                                                                                                     class="slider-horizontal__item_image"> </a>

                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/layer-7.png" alt="Item2"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/golden-logo.png" alt="Item3"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/paascheairbrush-logo.png" alt="Item4"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/rangerlogo-rgb.png" alt="Item5"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/talens-logo.png" alt="Item6"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_image-link"> <img src="/static/frontend/dist/images/home/1280/004-alvin.png" alt="Item1"
                                                                                                                                     class="slider-horizontal__item_image"> </a>

                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/layer-7.png" alt="Item2"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/golden-logo.png" alt="Item3"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/paascheairbrush-logo.png" alt="Item4"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/rangerlogo-rgb.png" alt="Item5"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/talens-logo.png" alt="Item6"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_image-link"> <img src="/static/frontend/dist/images/home/1280/004-alvin.png" alt="Item1"
                                                                                                                                     class="slider-horizontal__item_image"> </a>

                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/layer-7.png" alt="Item2"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/golden-logo.png" alt="Item3"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/paascheairbrush-logo.png" alt="Item4"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/rangerlogo-rgb.png" alt="Item5"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                        <li class="brands__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/home/1280/talens-logo.png" alt="Item6"
                                                                                                                                     class="slider-horizontal__item_image"> </a>


                        </li>
                    </ul>
                </div>
                <div class="brands__controls slider-horizontal__controls">
                    <a href="#" class="brands__controls_btn slider-horizontal__controls_btn brands__controls_prev-page slider-horizontal__controls_prev-page"> </a>
                    <a href="#" class="brands__controls_btn slider-horizontal__controls_btn brands__controls_next-page slider-horizontal__controls_next-page"> </a>
                </div>
                <div class="brands__scrollbar slider-horizontal__scrollbar">
                    <div class="brands__scrollbar_handle slider-horizontal__scrollbar_handle">
                        <div class="brands__scrollbar_mousearea slider-horizontal__scrollbar_mousearea"> </div>
                    </div>
                </div>
            </div>

        </section>
        <div class="recently-viewed-wrapper">
            <section class="recently-viewed slider-horizontal" id="recently-viewed-block">
                <h3 class="recently-viewed__title section__title slider-horizontal__title">Your recently viewed items</h3>
                <a href="#" class="recently-viewed__view-all slider-horizontal__view-all section__view-all">View all</a>

                <div class="recently-viewed__active-wrapper slider-horizontal__active-wrapper">
                    <div class="recently-viewed__frame slider-horizontal__frame" id="recently-viewed_frame">
                        <ul>
                            <li class="recently-viewed__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_image-link"> <img src="/static/frontend/dist/images/category/1280/act-6200-1-jpg.png" alt="Item1"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>
                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/198-media-catalog-product-7-g-7g12643-1-jpg.png" alt="Item2"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-rack08-1-jpg.png" alt="Item3"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-apb-series-08-jpg.png" alt="Item4"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-bpt5-jpg.png" alt="Item5"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-ebdz1216-1-jpg.png" alt="Item6"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/large-1-601-jpg.png" alt="Item1"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-1334a-1-jpg.png" alt="Item2"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/47832-jpg.png" alt="Item3"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-111pi-1-jpg.png" alt="Item4"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/198-media-catalog-product-thtdpk40330-jpg.png" alt="Item5"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-9224ma-1xl-3-jpg.png" alt="Item6"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a href="#" class="slider-horizontal__item_image-link"> <img src="/static/frontend/dist/images/category/1280/act-6200-1-jpg.png" alt="Item1"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>
                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/198-media-catalog-product-7-g-7g12643-1-jpg.png" alt="Item2"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-rack08-1-jpg.png" alt="Item3"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-apb-series-08-jpg.png" alt="Item4"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-bpt5-jpg.png" alt="Item5"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-ebdz1216-1-jpg.png" alt="Item6"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/large-1-601-jpg.png" alt="Item1"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-1334a-1-jpg.png" alt="Item2"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/47832-jpg.png" alt="Item3"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-111pi-1-jpg.png" alt="Item4"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/198-media-catalog-product-thtdpk40330-jpg.png" alt="Item5"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>
                            <li class="recently-viewed__item slider-horizontal__item"> <a class="slider-horizontal__item_image-link" href="#"> <img src="/static/frontend/dist/images/category/1280/alv-9224ma-1xl-3-jpg.png" alt="Item6"
                                                                                                                                                  class="slider-horizontal__item_image"> </a>
                                <a href="#" class="slider-horizontal__item_add-to-cart"> </a>

                            </li>

                        </ul>
                    </div>
                    <div class="recently-viewed__controls slider-horizontal__controls">
                        <a href="#" class="recently-viewed__controls_btn slider-horizontal__controls_btn recently-viewed__controls_prev-page slider-horizontal__controls_prev-page"> </a>
                        <a href="#" class="recently-viewed__controls_btn slider-horizontal__controls_btn recently-viewed__controls_next-page slider-horizontal__controls_next-page"> </a>
                    </div>
                    <div class="recently-viewed__scrollbar slider-horizontal__scrollbar">
                        <div class="recently-viewed__scrollbar_handle slider-horizontal__scrollbar_handle">
                            <div class="recently-viewed__scrollbar_mousearea slider-horizontal__scrollbar_mousearea"> </div>
                        </div>
                    </div>
                </div>

            </section>
            <a class="up-btn hidden-sm hidden-md hidden-lg" href="#main_header">up</a>
        </div>
    </div>
{/block}