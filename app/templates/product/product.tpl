{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}

{block "before-content"}
    {if !$.request->getIsAjax()}
    <div class="row">
        <div class="columns large-12">
            {insert "base/_breadcrumbs.tpl"}
        </div>
    </div>
    {/if}
{/block}

{block "content"}
<section class="product-page page">

    <section class="title hide-for-large">
        <div class="row">
            <div class="column large-12">
                <h1>{$model->getFrontendName()} </h1>
            </div>
        </div>
    </section>

    <section class="images_prices">
        <div class="row">
            <div class="column small-12 large-5 block__image">
                <div class="product__images-slider">
                    {add $site = $model->sites->limit(1)->get()}
                    {set $images = $model->images->order(['orderby'])->all()}


                    <div class="slider-thumbs">
                        <div class="swiper-wrapper">

                            {foreach $images as $image}
                                <div class="swiper-slide active">
                                    {add $image_displayed = $image}
                                    <img src="//cdn.{$site->getBaseDomain()}{$image->getUrl()}" alt="">
                                </div>
                            {/foreach}
                        </div>
                    </div>

                    <div class="slider">
                        <div class="swiper-wrapper">

                            {*<img data-original="//cdn.{$site->getBaseDomain()}{$image_displayed->getUrl()}" alt="" class="layz lazy-img">*}
                            {*{foreach $images as $image}*}
                                {*<div class="swiper-slide">*}
                                    {*<img src="//cdn.{$site->getBaseDomain()}{$image->getUrl()}" alt="">*}
                                {*</div>*}
                            {*{/foreach}*}
                        </div>
                                        <!-- Add Arrows -->
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                    </div>


                </div>
            </div>
            <div class="column small-12 large-7 block__title_price">
                <div class="title show-for-large">
                    <h1>{$model->getFrontendName()} </h1>
                </div>

                <div class="notifications">
                    notifications
                </div>

                <div class="prices">
                    table prices
                </div>
            </div>
        </div>
    </section>


    {include 'product/_tabs.tpl' model=$model}

    <section class="descriptions"></section>

    <section class="groupped-products">groupped products</section>
</section>
{/block}

{block 'after-content'}
    {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
{/block}

{block 'js'}
    <script>
        (function(){
            window.app.afterReady.push(function () {
//                var galleryTop = new Swiper('.images_prices .slider', {
//                    controlBy:'container',
//                    nextButton: '.swiper-button-next',
//                    prevButton: '.swiper-button-prev',
//                    direction: 'vertical',
//                    spaceBetween: 10,
//                });

                var galleryThumbs = new Swiper('.images_prices .slider-thumbs', {
                    spaceBetween: 5,
//                    centeredSlides: true,
                    slidesPerView: 'auto',
                    direction: 'vertical',
                    slidesPerColumnFill: 'column',
                    touchRatio: 0.2,
                    slideToClickedSlide: true,
                    paginationClickable: true,
                    autoHeight: true,
                    onClick: function(swiper, e) {

//                        console.log(swiper, e);
                        console.log(swiper.clickedIndex);
                        swiper.slideTo(swiper.clickedIndex);
                    },
                    onSlideChangeEnd: function(swiper) {
                        console.log(swiper.realIndex)
                    },
                    onInit: function(swiper) {
                        console.log(swiper.realIndex);
                    }
                });
//                galleryTop.params.control = galleryThumbs;
//                galleryThumbs.params.control = galleryTop;
            });
        })();
    </script>
{/block}