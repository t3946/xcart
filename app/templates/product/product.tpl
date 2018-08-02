{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}

{block 'css_preload'}
    {insert '_parts/_css_preload.tpl'}
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
        <div class="row">
            <div class="columns large-12">
                {insert "base/_breadcrumbs.tpl"}
            </div>
        </div>
    {/if}
{/block}

{block "content"}
    <div class="product-page default-content-page"
         data-product="{$model->productid}"
         data-name="{$model->getFrontendName()|escape}"
         data-category="{$category->category|escape}"
         data-source="detail-page"
         data-prices='{$model->getPrices()|json_encode}'
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
                            SKU: <span class="style">{$model->productcode}</span>
                        </span>
                        </div>
                        <div class="column shrink notifications hide-for-ml">
                            <div class="row notifications-info small-collapse">
                                <div class="column shrink">
                                    {include "product/messages/_messages.tpl" model=$model fill=true}
                                </div>
                                {*<div class="column shrink godaddy show-for-medium">*}
                                {*<img src="/static/frontend/dist/images/icons/item_product/gd_label.png" alt="GODADDY Verified & secured" class="gd">*}
                                {*</div>*}
                            </div>
                        </div>
                    </div>
                    <span class="clearfix"></span>
                </div>
            </div>
        </section>

        <section class="images_prices">
            <div class="row">
                <div class="column small-12 ml-7 large-7 block__image">
                    <div class="product__images-slider">
                        {add $site = $model->sites->limit(1)->get()}

                        {if $model->isGroupRoot()}
                            {set $images = []}

                            {set $childrens = $model->getFrontendChilds()->limit(4)->all()}
                            {foreach $childrens as $child}
                                {set $images[] = $child->preview->order(['orderby'])->limit(1)->get()}
                            {/foreach}
                        {else}
                            {set $images = $model->images->order(['orderby'])->all()}
                        {/if}
                        {if $images}
                            <datalist>
                                {foreach $images as $image}
                                    {if $image}
                                        <option value="//cdn.{$site->getBaseDomain()}{$image->getUrl()}"
                                                data-thumb="//cdn.{$site->getBaseDomain()}{$image->getUrl()}"
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
                                <p>Image not available</p>
                            </div>
                        {/if}

                    </div>
                </div>
                <div class="column small-12 ml-5 large-5 block__title_price">

                    <div class="notifications show-for-ml">
                        <div class="row align-middle ml-collapse notifications-info">
                            <div class="column shrink">
                                {include "product/messages/_messages.tpl" model=$model fill=true}
                            </div>
                        </div>
                    </div>

                    {if $model->descr}
                        <div class="highlights show-for-ml">
                            <div class="h2 title">Product Highlights</div>
                            {raw $model->descr}
                        </div>
                    {/if}

                    {if !$model->isGroupRoot()}
                        <div class="prices">
                            {include "product/price/_table_prices.tpl" model=$model}

                            {if $model->isGroupChild()}
                                {set $parent = $model->parent}
                                {if $parent}
                                    <div class="link__group_root">
                                        <a href="{$parent->getAbsoluteUrl()}">
                                            Full {$parent->getFrontendName()} product line
                                        </a>
                                    </div>
                                {/if}
                            {/if}
                        </div>
                    {else}
                        <div class="full_line__group_root buttons">
                            {ignore}
                                <a onclick="$('html, body').animate({scrollTop: $('#products').offset().top}, 1000);"
                                   class="button yellow waves waves-orange waves-effect default-style">Full product line</a>
                                <div class="info">Click here to see full product line</div>
                            {/ignore}
                        </div>
                    {/if}
                </div>
            </div>
        </section>


        {include 'product/_tabs.tpl' model=$model}

        {if $model->isGroupRoot()}
            <section class="groupped-products" id="products">
                {include "product/_groupped_products.tpl"}
            </section>
        {/if}
    </div>
{/block}

{block 'after-content'}
    <div class="row">
        <div class="small-12 column slider-also_bought">
            {set $link}{url 'catalog:also_bound' id=$model->pk}{/set}
            {include 'slider/base_product_slider.tpl' title="Customers Who Bought This Item Also Bought" link=$link hide=true hide_link=true}
        </div>
    </div>
    <div class="row">
        <div class="small-12 column slider-related">
            {set $link}{url 'catalog:related' id=$model->pk}{/set}
            {include 'slider/base_product_slider.tpl' title="Similar products" link=$link hide=true hide_link=true}
        </div>
    </div>
    <div class="row">
        <div class="small-12 column slider-viewed">
            {set $link}{url 'catalog:viewed'}{/set}
            {include 'slider/base_product_slider.tpl' title="You recently viewed items" link=$link hide=true hide_link=true}
        </div>
    </div>
{/block}

{block 'js'}
    {set $main_image = $model->images->limit(1)->get()}
    {set $brand = $model->brand}

    <script type="application/ld+json">
    {$helper->getJsonSchema($model)}
</script>
{/block}