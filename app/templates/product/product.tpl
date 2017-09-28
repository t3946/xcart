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
                        {foreach $images as $image}
                            <option value="//cdn.{$site->getBaseDomain()}{$image->getUrl()}"
                                    data-thumb="//cdn.{$site->getBaseDomain()}{$image->getUrl()}"
                                    data-id="{$image->imageid}"
                                    type="image">
                            </option>
                        {/foreach}

                        <option value="https://www.youtube.com/watch?v=dQw4w9WgXcQ" type="video"></option>
                        <option value="https://www.youtube.com/watch?v=yPYZpwSpKmA" type="video"></option>
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


//            window.app.afterReady.push(function () {
//
//
//            });
        })();
    </script>
{/block}