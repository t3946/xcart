{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}

{block 'content'}
<section class="banners-section">
    {*<div class="row">*}

        <div class="promotion">
            {renderSlider 'promo-sly-slider'}
        </div>

        <div class="product-of-the-day">
            <div class="banner__product-of-the-day banner__wrapper">
                <a href="#">
                    <div class="product-of-the-day_cover banner__cover">
                        <div class="product-of-the-day__caption">Product оf the day</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="right-banners">

            <div class="bestsellers">
                <a href="#">
                    <div class="bestsellers_cover banner__cover">
                        <div class="bestsellers__caption">Bestsellers</div>
                        <div class="bestsellers__description">Try it for 90 days. Enjoy it for 25 years > </div>
                    </div>
                </a>
            </div>

            <div class="whatsnew">
                <a href="#">
                    <div class="whatsnew_cover banner__cover">
                        <div class="whatsnew__caption">What’s new</div>
                    </div>
                </a>
            </div>
        </div>

    {*</div>*}
</section>

{/block}