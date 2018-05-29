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
<div class="product-page default-content-page"
         data-product="{$model->productid}"
         data-name="{$model->getFrontendName()|escape}"
         data-category="{$category->category|escape}"
         data-source="detail-page"
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

                    {if $model->isGroupRoot()}
                        {set $images = []}

                        {set $childrens = $model->getFrontendChilds()->limit(4)->all()}
                        {foreach $childrens as $child}
                            {set $images[] = $child->preview->order(['orderby'])->limit(1)->get()}
                        {/foreach}
                    {else}
                        {set $images = $model->images->order(['orderby'])->all()}
                    {/if}
                    <datalist>
                        {foreach $images as $image}
                            <option value="//cdn.{$site->getBaseDomain()}{$image->getUrl()}"
                                    data-thumb="//cdn.{$site->getBaseDomain()}{$image->getUrl()}"
                                    data-id="{$image->imageid}">
                            </option>
                        {/foreach}

                        {*<option value="https://www.youtube.com/watch?v=dQw4w9WgXcQ" type="video"></option>*}
                        {*<option value="https://www.youtube.com/watch?v=yPYZpwSpKmA" type="video"></option>*}
                        {*<option value="https://www.youtube.com/watch?v=dQw4w9WgXcQ" type="video"></option>*}
                        {*<option value="https://www.youtube.com/watch?v=yPYZpwSpKmA" type="video"></option>*}
                        {*<option value="https://www.youtube.com/watch?v=dQw4w9WgXcQ" type="video"></option>*}
                        {*<option value="https://www.youtube.com/watch?v=yPYZpwSpKmA" type="video"></option>*}

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

                {*<div class="float-right show-for-large godaddy">*}
                    {*<img src="/static/frontend/dist/images/icons/item_product/gd_label.png" alt="GODADDY Verified & secured" class="gd">*}
                {*</div>*}
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

                    {if $model->isGroupChild()}
                        {set $parent = $model->parent}
                        <div class="link__group_root">
                            <a href="{$parent->getAbsoluteUrl()}">
                                Full {$parent->getFrontendName()} product line
                            </a>
                        </div>
                    {/if}

                    {*{if $model->isOutOfStock()}*}
                    {*{else}*}
                    {*{/if}*}
                </div>
                {else}
                    <div class="full_line__group_root buttons">
                        {ignore}
                            <a onclick="$('html, body').animate({scrollTop: $('#products').offset().top}, 1000);" class="button yellow waves waves-orange waves-effect default-style">Full product line</a>
                            <div class="info">Click here to see full product line</div>
                        {/ignore}
                    </div>
                {/if}
                {*<div class="godaddy hide-for-medium hide-for-large text-align--center">*}
                    {*<img src="/static/frontend/dist/images/icons/item_product/gd_label.png" alt="GODADDY Verified & secured" class="gd">*}
                {*</div>*}
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
