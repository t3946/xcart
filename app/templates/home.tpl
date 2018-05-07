{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}

{block 'content'}
<div class="default-content-page home-page">
    <section class="banners-section">

        <div class="promotion disable-global-swipe-horizontal">
            {renderSlider 'promo-sly-slider'}
        </div>

        <div class="static_banners">

            <div class="banners_column">
                <div class="banner product-of-the-day show-for-medium">
                    <a href="{$product->getAbsoluteUrl()}" class="banner__cover" data-background="/static/frontend/dist/images/slider/{$.getSite->code|strtolower}/product_of_day.jpg">
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

    <div class="promo-links show-for-medium">
        <div class="row">
            <div class="column small-12">
                <div class="links">

                    <a class="icon brands show-for-large"
                       href="{url 'brand:list'}">Brands</a>

                    <a class="icon new"
                       href="{$category_new->getAbsoluteUrl()}">What's new</a>

                    <a class="icon bestsellers"
                       href="{url 'catalog:bestsellers'}">Bestsellers</a>

                    <a class="icon day"
                       href="#">Product of the day</a>

                    <a class="icon featured"
                       href="{url 'catalog:featured'}">Featured products</a>

                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="small-12 column slider-products slider-featured-product">
            {set $link}{url 'catalog:featured'}{/set}
            {include 'slider/base_product_slider.tpl' title='Featured product' link=$link}
        </div>
    </div>

    <div class="row">
        <div class="small-12 column slider-products slider-new">
            {set $link}{url 'catalog:new'}{/set}
            {include 'slider/base_product_slider.tpl' title="What's new" link=$category_new->getAbsoluteUrl() data_link=$link}
        </div>
    </div>

    <div class="row">
        <div class="small-12 column top-categories">

        </div>
    </div>

    {*<div class="row">*}
        {*<div class="small-12 column brands">*}
            {*{set $link}{url 'brand:list'}{/set}*}
            {*{set $link_data}{url 'catalog:brands'}{/set}*}
            {*{include 'slider/base_product_slider.tpl' title="Brands" link=$link data_link=$link_data}*}
        {*</div>*}
    {*</div>*}

    {add $main_html = $.getSite->getConfig().cidev_main_page_code}
    {if $main_html}
    <div class="row">
        <div class="small-12 column">
            <div class="pages page">
                {$main_html}
            </div>
        </div>
    </div>
    {/if}
</div>

{/block}

{block 'after-content'}
    <div class="row">
        <div class="small-12 column slider-viewed">
            {set $link}{url 'catalog:viewed'}{/set}
            {include 'slider/base_product_slider.tpl' title="You recently viewed items" link=$link hide=true hide_link=true}
        </div>
    </div>
{/block}