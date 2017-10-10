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
<section class="product-page page"
         data-product="{$model->productid}"
         data-prices='{$model->getPrices()|json_encode}'
         {if $model->getFrontendPrice() < $model->list_price}
         data-list-price="{$model->list_price}"
         {/if}>

    <section class="product-title product-title-small hide-for-large">
        <div class="row">
            <div class="column large-12">
                <h1>
                    {$model->getFrontendName()}

                    {if $model->retail_trust_enabled}
                        <i class="icon retailTrust"></i>
                    {/if}

                </h1>

                <div class="float-right show-for-medium-only show-for-ml-only godaddy">
                    <img src="/static/frontend/dist/images/icons/item_product/gd_label.png" alt="GODADDY Verified & secured" class="gd">
                </div>

                <div class="sku">
                    <span class="value">
                        SKU: <span class="style" itemprop="sku">{$model->productcode}</span>
                    </span>
                </div>

                <span class="clearfix"></span>
            </div>
        </div>
    </section>

    <section class="images_prices">
        <div class="row">
            <div class="column small-12 large-5 block__image">
                <div class="product__images-slider">
                    {add $site = $model->sites->limit(1)->get()}
                    {set $images = $model->images->order(['orderby'])->all()}

                    <datalist>
                        {foreach $images as $image}
                            <option value="//cdn.{$site->getBaseDomain()}{$image->getUrl()}"
                                    data-thumb="//cdn.{$site->getBaseDomain()}{$image->getUrl()}"
                                    data-id="{$image->imageid}"
                                    type="image">
                            </option>
                        {/foreach}

                        <option value="https://www.youtube.com/watch?v=dQw4w9WgXcQ" type="video"></option>
                        <option value="https://www.youtube.com/watch?v=yPYZpwSpKmA" type="video"></option>

                    </datalist>

                </div>
            </div>
            <div class="column small-12 large-7 block__title_price">
                <noindex>
                <div class="product-title show-for-large">

                    <h1>
                        {$model->getFrontendName()}
                        {if $model->retail_trust_enabled}
                            <i class="icon retailTrust"></i>
                        {/if}
                    </h1>

                </div>

                <div class="float-right show-for-large godaddy">
                    <img src="/static/frontend/dist/images/icons/item_product/gd_label.png" alt="GODADDY Verified & secured" class="gd">
                </div>
                <div class="sku show-for-large">
                    <span class="value">
                        SKU: <span class="style" itemprop="sku">{$model->productcode}</span>
                    </span>
                </div>
                <span class="clearfix"></span>
                </noindex>


                <div class="notifications">

                    <div class="row">
                        <div class="column small-12 medium-10 large-9">
                            {include "product/messages/_messages.tpl" model=$model fill=true}
                        </div>
                    </div>
                </div>


                {if !$model->isGroupRoot()}
                <div class="prices">
                    {include "product/price/_table_prices.tpl" model=$model}
                    {if $model->isOutOfStock()}
                        {*{}*}
                    {else}

                    {/if}
                </div>
                {/if}
                <div class="godaddy hide-for-medium hide-for-large text-align--center">
                    <img src="/static/frontend/dist/images/icons/item_product/gd_label.png" alt="GODADDY Verified & secured" class="gd">
                </div>
            </div>
        </div>
    </section>


    {include 'product/_tabs.tpl' model=$model}

    <section class="groupped-products" id="products">groupped products</section>
</section>
{/block}

{block 'after-content'}
    {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
{/block}

{block 'js'}
    <script>
        (function(){

        })();
    </script>
{/block}