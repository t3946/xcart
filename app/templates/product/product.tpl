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
                <div class="slider-container">
                    {add $site = $model->sites->limit(1)->get()}

                    <div class="slider">
                        <div class="swiper-wrapper">


                            {foreach $model->images->order(['orderby'])->all() as $image}
                                <div class="swiper-slide">
                                    <img src="//cdn.{$site->getBaseDomain()}{$image->getUrl()}" alt="">
                                </div>
                            {/foreach}
                        </div>
                                        <!-- Add Arrows -->
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                    </div>
                    {*<div class="swiper-container gallery-thumbs">*}
                        {*<div class="swiper-wrapper">*}
                            {*<div class="swiper-slide" style="background-image:url(http://lorempixel.com/1200/1200/nature/1)"></div>*}
                            {*<div class="swiper-slide" style="background-image:url(http://lorempixel.com/1200/1200/nature/2)"></div>*}
                            {*<div class="swiper-slide" style="background-image:url(http://lorempixel.com/1200/1200/nature/3)"></div>*}
                            {*<div class="swiper-slide" style="background-image:url(http://lorempixel.com/1200/1200/nature/4)"></div>*}
                            {*<div class="swiper-slide" style="background-image:url(http://lorempixel.com/1200/1200/nature/5)"></div>*}
                            {*<div class="swiper-slide" style="background-image:url(http://lorempixel.com/1200/1200/nature/6)"></div>*}
                            {*<div class="swiper-slide" style="background-image:url(http://lorempixel.com/1200/1200/nature/7)"></div>*}
                            {*<div class="swiper-slide" style="background-image:url(http://lorempixel.com/1200/1200/nature/8)"></div>*}
                            {*<div class="swiper-slide" style="background-image:url(http://lorempixel.com/1200/1200/nature/9)"></div>*}
                            {*<div class="swiper-slide" style="background-image:url(http://lorempixel.com/1200/1200/nature/10)"></div>*}
                        {*</div>*}
                    {*</div>*}

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
                var galleryTop = new Swiper('.images_prices .slider', {
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    spaceBetween: 10,
                });
            });
        })();
    </script>
{/block}