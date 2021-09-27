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
    <meta property="og:image" content="https:{$model->images->filter(['avail' => 'Y'])->limit(1)->get()}">
{/block}

{block 'seo'}
    {parent}
    <link rel="amphtml" href="{$model->getAmpAbsoluteUrl()}">
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
         {/if}>

    <section class="product-title product-title-small">
        <div class="row">
            <div class="column large-12">
                <h1>
                    {$model->getFrontendName()}

                    {if $model->retail_trust_enabled}
                        <i class="icon retailTrust"></i>
                    {/if}

                </h1>

                {*<div class="float-right show-for-medium-only show-for-ml-only godaddy">*}
                    {*<img src="/static/frontend/dist/images/icons/item_product/gd_label.png" alt="GODADDY Verified & secured" class="gd">*}
                {*</div>*}
                <div class="row align-justify align-middle">
                    <div class="column shrink sku">
                        <span class="value">
                            {t 'SKU'}: <span class="style">{$model->productcode}</span>
                        </span>
                    </div>
                    <div class="column shrink notifications hide-for-ml product_notifications">
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

    <section class="images_prices">
        <div class="row">
            <div class="column small-12 ml-6 large-6 block__image">
                <div class="product__images-slider">
                    {add $site = $model->sites->limit(1)->get()}

                    {if $model->isGroupRoot()}
                        {set $images = []}

                        {set $childrens = $model->getFrontendChilds()->limit(4)->all()}
                        {foreach $childrens as $child}
                            {set $images[] = $child->images->filter(['avail' => 'Y'])->order(['orderby'])->limit(1)->get()}
                        {/foreach}
                    {else}
                        {set $images = $model->images->filter(['avail' => 'Y'])->order(['orderby'])->all()}
                    {/if}

                    {if $images}
                        <div class="product-slider-sceleton-wrapper">
                            <div class="product-slider-imgs-sceleton-wrapper">
                                <div class="sceleton" style="max-width: 52px; height: 60px; margin-bottom: 10px"></div>
                                <div class="sceleton" style="max-width: 52px; height: 60px; margin-bottom: 10px"></div>
                                <div class="sceleton" style="max-width: 52px; height: 60px; margin-bottom: 10px"></div>
                                <div class="sceleton" style="max-width: 52px; height: 60px; margin-bottom: 10px"></div>
                                <div class="sceleton" style="max-width: 52px; height: 60px; margin-bottom: 10px"></div>
                            </div>
                            <div class="sceleton product-slider-big-img-sceleton"></div>
                        </div>

                        <noscript>
                            {foreach $images as $image}
                                {if $image}
                                    <img src="//cdn.{$site->getBaseDomain()}{$image->getUrl(520)}"
                                         alt="{$model->getFrontendName()|escape}"/>
                                {/if}
                            {/foreach}
                        </noscript>
                        <datalist>
                            {foreach $images as $image}
                                {if $image}
                                    <option value="//cdn.{$site->getBaseDomain()}{$image->getUrl()}"
                                            data-thumb="//cdn.{$site->getBaseDomain()}{$image->getUrl(50)}"
                                            data-preview="//cdn.{$site->getBaseDomain()}{$image->getUrl(520)}"
                                            data-id="{$image->imageid}"
                                            type="image">
                                    </option>
                                {/if}
                            {/foreach}
                            {if $videos}
                                {foreach $videos as $video}
                                    <option value="{$video->video}"
                                            data-thumb=""
                                            data-id="{$video->id}"
                                            type="video">

                                    </option>
                                {/foreach}
                            {/if}

                            {*<option value="https://www.youtube.com/watch?v=dQw4w9WgXcQ"*}
                            {*type="video"*}
                            {*data-thumb=""*}
                            {*data-id="{$video->id}"*}
                            {*></option>*}
                            {*<option value="https://www.youtube.com/watch?v=yPYZpwSpKmA" type="video"></option>*}
                            {*<option value="https://www.youtube.com/watch?v=dQw4w9WgXcQ" type="video"></option>*}
                            {*<option value="https://www.youtube.com/watch?v=yPYZpwSpKmA" type="video"></option>*}
                            {*<option value="https://www.youtube.com/watch?v=dQw4w9WgXcQ" type="video"></option>*}
                            {*<option value="https://www.youtube.com/watch?v=yPYZpwSpKmA" type="video"></option>*}

                        </datalist>
                    {else}
                        <div class="not-avail-thumb">
                            <p>{t 'Image not available'}</p>
                        </div>
                    {/if}

                    </div>
                </div>
                <div class="column small-12 ml-6 large-6 block__title_price">

                <div class="notifications show-for-ml product_notifications">
                    <div class="row align-middle ml-collapse notifications-info">
                        <div class="column shrink ">
                            {include "product/messages/_messages.tpl" model=$model fill=true class="product_label"}
                        </div>
                    </div>
                </div>

                {if $model->descr}
                    <div class="highlights show-for-ml">
                        {raw $model->descr}
                    </div>
                {/if}

                    {if !$model->isGroupRoot()}
                        <div class="prices">
                            {include "product/price/_table_prices.tpl" model=$model form=$form}

                            {if $model->isGroupChild()}
                                {set $parent = $model->parent}
                                {if $parent}
                                    <div class="link__group_root">
                                        <a href="{$parent->getAbsoluteUrl()}">
                                            {t 'Full'} {$parent->getFrontendName()} {t 'product line'}
                                        </a>
                                    </div>
                                {/if}
                            {/if}
                        </div>
                    {else}
                        <div class="full_line__group_root buttons">
                            <a {ignore}onclick="$('html, body').animate({scrollTop: $('#products').offset().top}, 1000);"{/ignore}
                             class="button yellow waves waves-orange waves-effect default-style">{t 'Full product line'}</a>
                             <div class="info">{t 'Click here to see full product line'}</div>
                        </div>
                    {/if}
                </div>
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
                     data-checkout-url="{Modules\Order\Helpers\OrderHelper::getCheckoutUrl()}"
                     data-mode="group-product"
                     class="column groupped-products"
                     id="products">
                </div>
            </div>
        </section>
    {/if}

</div>
{/block}

{block 'after-content'}
    <div class="row">
        <div class="small-12 column slider-also_bought">
            {set $link}{url 'catalog:also_bound' id=$model->pk}{/set}
            {set $lbl}{t 'Customers Who Bought This Item Also Bought'}{/set}
            {include 'slider/base_product_slider.tpl' title=$lbl link=$link hide=false hide_link=true}
        </div>
    </div>
    <div class="row">
        <div class="small-12 column slider-related">
            {set $link}{url 'catalog:related' id=$model->pk}{/set}
            {set $lbl}{t 'Similar products'}{/set}
            {include 'slider/base_product_slider.tpl' title=$lbl link=$link hide=false hide_link=true}
        </div>
    </div>
    <div class="row">
        <div class="small-12 column slider-viewed">
            {set $link}{url 'catalog:viewed'}{/set}
            {set $lbl}{t 'You recently viewed items'}{/set}
            {include 'slider/base_product_slider.tpl' title=$lbl link=$link hide=false hide_link=true}
        </div>
    </div>
{/block}

{block 'js'}
    {set $main_image = $model->images->limit(1)->get()}
    {add $brand = $model->brand}

    <script type="application/ld+json">
    {$helper->getJsonSchema($model)}
    </script>

{/block}