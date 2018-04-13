{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}

{block 'content'}
<div class="home-page">
    <section class="banners-section">

        <div class="promotion disable-global-swipe-horizontal">
            {renderSlider 'promo-sly-slider'}
        </div>

        <div class="static_banners">

            <div class="banners_column">
                <div class="banner product-of-the-day show-for-medium">
                    <a href="#" class="banner__cover" data-background="/static/frontend/dist/images/slider/{$.getSite->code|strtolower}/product_of_day.jpg">
                        <div class="product-of-the-day_cover banner__info">
                            <div class="product-of-the-day__caption">Product оf the day</div>
                        </div>
                    </a>
                </div>
            </div>



            <div class="banners_column right-banners show-for-large">

                <div class="banner bestsellers">
                    <a href="{url 'catalog:bestsellers'}" class="banner__cover" data-background="/static/frontend/dist/images/slider/{$.getSite->code|strtolower}/bestsellers.jpg">
                        <div class="banner__info">
                            <div class="caption">Bestsellers</div>
                            {*<div class="description">Try it for 90 days. Enjoy it for 25 years > </div>*}
                        </div>
                    </a>
                </div>

                <div class="banner whatsnew dark">
                    <a href="{$category_new->getAbsoluteUrl()}" class="banner__cover" data-background="/static/frontend/dist/images/slider/{$.getSite->code|strtolower}/what_is_new.jpg">
                        <div class="banner__info">
                            <div class="caption">What’s new</div>
                        </div>
                    </a>
                </div>
            </div>

        </div>


    </section>
    <div class="promo-links">

    </div>

    <div class="row">
        <div class="small-12 column">
            <div class="slider-block slider-featured-product">
                <div class="title_container">
                    <div class="title-section">
                        Featured product

                        <a href="" class="link">
                            Show all
                        </a>
                    </div>
                </div>
                <div class="slider-data" data-url="/goods/api/slider/featured"></div>
            </div>
        </div>
    </div>
</div>

{/block}