{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}

{block 'css_preload'}
    {insert '_parts/_css_preload.tpl'}
{/block}


{block 'content'}
<div class="default-content-page home-page">
    <div class="container">
        <section class="banners-section">
            <div class="promotion disable-global-swipe-horizontal banner">
                {renderSlider 'promo-sly-slider'}
            </div>

            <div class="static_banners">
                <div class="banners_column">
                    <div class="banner product-of-the-day show-for-medium dark">
                        {if $product}
                        <a href="{$product->getAbsoluteUrl()}" class="lazy-bg banner__cover" data-src="{$.call.Modules.Goods.Helpers.PromotionalProductsHelper::getProductOfTheDayImage($product)}">
                            <div class="product-of-the-day_cover banner__info">
                                <div class="product-of-the-day__caption">{t 'Product of the day'}</div>
                            </div>
                        </a>
                        {/if}
                    </div>
                </div>

                <div class="banners_column right-banners show-for-large">
                    <div class="banner bestsellers dark">
                        <a href="{url 'catalog:bestsellers'}" class="lazy-bg banner__cover" data-src="{$.call.Modules.Goods.Helpers.PromotionalProductsHelper::getBestSelllerImage($best_seller)}">
                            <div class="banner__info">
                                <div class="caption">{t 'Bestsellers'}</div>
                            </div>
                        </a>
                    </div>

                    <div class="banner whatsnew dark">
                        {if $category_new}
                            <a href="{$category_new->getAbsoluteUrl()}" class="lazy-bg banner__cover" data-src="{$.call.Modules.Goods.Helpers.PromotionalProductsHelper::getNewProductImage($new_product)}">
                                <div class="banner__info">
                                    <div class="caption">{t "What's new"}</div>
                                </div>
                            </a>
                        {/if}
                    </div>
                </div>
            </div>
        </section>

        <div class="promo-links show-for-medium">
            <div class="row">
                <div class="col-12">
                    <div class="links">
                        <a class="icon brands show-for-large"
                           href="{url 'brand:list'}">{t 'Brands'}</a>
                        {if ($category_new)}
                        <a class="icon new"
                           href="{$category_new->getAbsoluteUrl()}">{t "What's new"}</a>
                        {/if}

                        <a class="icon bestsellers"
                           href="{url 'catalog:bestsellers'}">{t 'Bestsellers'}</a>

                        {if $product}
                        <a class="icon day"
                           href="{$product->getAbsoluteUrl()}">{t 'Product of the day'}</a>
                        {/if}

                        <a class="icon featured"
                           href="{url 'catalog:featured'}">{t 'Featured products'}</a>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12 slider-products slider-featured-product">
                {set $link}{url 'catalog:featured'}{/set}
                {set $datalink}{url 'api:featuredApi'}{/set}
                {set $title}{t 'Featured products'}{/set}
                {include 'slider/base_product_slider.tpl' title=$title link=$link data_link=$datalink}
            </div>
        </div>

        <div class="row">
            <div class="col-12 slider-products slider-bestsellers">
                {set $link}{url 'catalog:bestsellers'}{/set}
                {set $datalink}{url 'api:bestsellersApi'}{/set}
                {set $title}{t "Bestsellers"}{/set}
                {include 'slider/base_product_slider.tpl' title=$title link=$link data_link=$datalink}
            </div>
        </div>

        <div class="row">
            <div class="col-12 slider-products slider-new">
                {set $link}{url 'catalog:new'}{/set}
                {set $datalink}{url 'api:newApi'}{/set}
                {if $category_new}
                    {set $title}{t "What's new"}{/set}
                    {include 'slider/base_product_slider.tpl' title=$title link=$category_new->getAbsoluteUrl() data_link=$datalink}
                {/if}
            </div>
        </div>


        {add $main_html = $.fetch_info_block('mainpage', null, ['sfcode' => $site->code, 'lang' => $site->lang->lang_code])}
        {if $main_html}
            <div class="row">
                <div class="col-12">
                    <div class="pages page">
                        {$main_html}
                    </div>
                </div>
            </div>
        {/if}
    </div>
</div>

{/block}