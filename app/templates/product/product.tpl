{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}

{block 'css_preload'}
    {insert '_parts/_css_preload.tpl'}
{/block}

{block 'head'}
    {add $brand = $model->brand}
    <script>
        // Measure a view of product details.
        dataLayer = window.dataLayer || [];
        dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
        dataLayer.push({
            'ecommerce': {
                'detail': {
                    'products': [{
                        'name': {$model->getFrontendName()|escape:'js'},         // Name or ID is required.
                        'id': '{$model->productid}',
                        'price': '{$model->getFrontendPrice()}',
                        'brand': {$brand->brand|escape:'js'},
                        'category': {$category->category|escape:'js'},
                    }]
                }
            }
        });
    </script>

{/block}

{block 'product_og'}
    <meta property="og:site_name" content="{$site->getName()}">
    <meta property="og:title" content="{$model->getFrontendName()}">
    <meta property="og:url" content="{$model->getAbsoluteUrl(true)}">
    <meta property="og:description" content="{$model->getFrontendDescription()|escape}">
    <meta property="og:type" content="product">
    <meta property="og:image" content="{$model->getMainImage()}">
{/block}

{block 'seo'}
    {parent}
    <link rel="amphtml" href="{$model->getAmpAbsoluteUrl(true)}">
{/block}

{block "before-content"}
    {if !$.request->getIsAjax()}
        <div class="container">
            <div class="row">
                <div class="col-12">
                    {insert "base/_breadcrumbs.tpl"}
                </div>
            </div>
        </div>
    {/if}
{/block}

{block "content"}
    {add $brand = $model->brand}
<div class="product-page default-content-page"
         data-product="{$model->productid}"
         data-name="{$model->getFrontendName()|escape}"
         data-category="{$category->category|escape}"
         data-source="detail-page"
         data-brand="{$brand->brand|escape}"
         data-prices='{$model->getPrices()|json_encode}'
         data-price="{$model->getFrontendPrice()}"
         data-currency="{$site_currency->currency_code}"
         data-rows="2"
         {if $model->getFrontendPrice() < $model->list_price}
         data-list-price="{$model->list_price}"
         {/if}
>
    <div class="container">
        <section class="product-title product-title-small">
            <div class="row">
                <div class="col-12">
                    <h1 class="fw-bold">
                        {$model->getFrontendName()}

                        {if $model->retail_trust_enabled}
                            <i class="icon retailTrust"></i>
                        {/if}
                    </h1>

                    <div class="row align-justify align-middle">
                        <div class="col shrink sku">
                            <span class="value">
                                {t 'SKU'}: <span class="style">{$model->productcode}</span>
                            </span>
                        </div>

                        <div class="col shrink notifications hide-for-ml product_notifications">
                            <div class="notifications-info small-collapse">
                                <div class="column shrink">
                                    {include "product/messages/_messages.tpl" model=$model fill=true class="product_label"}
                                </div>
                            </div>
                        </div>
                    </div>

                    <span class="clearfix"></span>
                </div>
            </div>
        </section>

        <section class="images_prices row row-cols-1 row-cols-lg-2">
            <div class="col block__image mb-10">
                <div class="product-slider-sticky-container">
                    <div class="product__images-slider">
                    {add $site = $model->sites->limit(1)->get()}

                    {set $images = $model->getImages()}

                    {if $images}
                        <div class="product-slider-skeleton-wrapper">
                            <div class="product-slider-images-skeleton-wrapper d-none d-lg-block">
                                <div class="sceleton product-slider-images-skeleton-arrow"></div>

                                {for $i=1 to=5}
                                    <div class="sceleton product-slider-images-skeleton-thumb product-slider-skeleton-wrapper__thumb"></div>
                                {/for}

                                <div class="sceleton product-slider-images-skeleton-arrow"></div>
                            </div>
                            <div class="sceleton product-slider-big-img-skeleton"></div>
                        </div>

                        <noscript>
                            {foreach $images as $image}
                                {if $image}
                                    <img src="{$image->getCdnURL('detail')}"
                                         alt="{$model->getFrontendName()|escape}"/>
                                {/if}
                            {/foreach}
                        </noscript>
                        <datalist>
                            {foreach $images as $image}
                                {if $image}
                                    <option value="{$image->getCdnURL('detail')}"
                                            data-thumb="{$image->getCdnURL('thumb')}"
                                            data-preview="{$image->getCdnURL('preview')}"
                                            data-id="{$image->pk}"
                                            data-width="{$image->getAttribute('image_x')}"
                                            data-height="{$image->getAttribute('image_y')}"
                                            type="image">
                                    </option>
                                {/if}
                            {/foreach}

                            {if $videos}
                                {foreach $videos as $video}
                                    <option data-video='{json_encode($video)}'
                                            type="video">
                                    </option>
                                {/foreach}
                            {/if}
                        </datalist>
                    {else}
                        <div class="not-avail-thumb">
                            <p>{t 'Image not available'}</p>
                        </div>
                    {/if}

                    </div>
                </div>
            </div>

            <div class="col block__title_price">
                <div id="product-labels-target"></div>

                {if $model->descr}
                    <div class="highlights show-for-ml">
                        {raw $model->descr|html_entity_decode}
                    </div>
                {/if}
                    {if !$model->isGroupRoot()}
                        <div class="prices">
                            {include "product/price/_table_prices.tpl" model=$model form=$form}
                        </div>
                    {else}
                        <div class="full_line__group_root buttons">
                            <a {ignore}onclick="$('html, body').animate({scrollTop: $('#products').offset().top}, 1000);"{/ignore}
                             class="button yellow waves waves-orange waves-effect default-style">{t 'Full product line'}</a>
                             <div class="info">{t 'Click here to see full product line'}</div>
                        </div>
                    {/if}
                </div>
        </section>

        {include 'product/_tabs.tpl' model=$model}

        {if $model->isGroupRoot()}
        {set $pager_data = ['pageSize' => $pager->getPageSize(), 'currentPage' => $pager->getPage(), 'paginateCount' => count($pager->paginate()), 'total' => $pager->getTotal()]}
        <section>
            <div class="row">
                <div data-sorting-options='{str_replace("'", '&#39;', json_encode($sort_arr))}'
                     data-current-sorting-key="{$sort}"
                     data-hide-sort="true"
                     data-pager='{str_replace("'", '&#39;', json_encode($pager_data))}'
                     data-catalog-url="{$pager->createView()->getUrl(1)}"
                     data-checkout-url="{$.call.Modules.Order.Helpers.OrderHelper::getCheckoutUrl()}"
                     data-mode="group-product"
                     class="col groupped-products"
                     id="products">
                </div>
            </div>
        </section>
    {/if}
    </div>
</div>
{/block}

{block 'after-content'}
    <div class="row">
        <div class="col-12 slider-also_bought">
            {set $link}{url 'api:also_boundApi' id=$model->pk}{/set}
            {set $lbl}{t 'Customers Who Bought This Item Also Bought'}{/set}
            {include 'slider/base_product_slider.tpl' title=$lbl link=$link hide=false hide_link=true}
        </div>
    </div>
    <div class="row">
        <div class="col-12 slider-related">
            {set $link}{url 'api:relatedApi' id=$model->pk}{/set}
            {set $lbl}{t 'Similar products'}{/set}
            {include 'slider/base_product_slider.tpl' title=$lbl link=$link hide=false hide_link=true}
        </div>
    </div>
    <div class="row">
        <div class="col-12 slider-viewed">
            {set $link}{url 'api:viewedApiProduct' id=$model->pk}{/set}
            {set $lbl}{t 'You recently viewed items'}{/set}
            {include 'slider/base_product_slider.tpl' title=$lbl link=$link hide=false hide_link=true}
        </div>
    </div>
{/block}

{block 'js'}
    {set $main_image = $model->getMainImage()}
    {add $brand = $model->brand}

    <script type="application/ld+json">
    {$helper->getJsonSchema($model)}
    </script>
    <script>
        po = document.createElement('script');
        po.type = 'text/javascript';
        po.src = '//assets.pinterest.com/js/pinit.js';
        s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(po, s);
    </script>

{/block}