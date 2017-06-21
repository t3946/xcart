{extends  "catalog/base.tpl"}
{set $brand_page = $model}

{if !$.request->getIsAjax()}
    {block "catalog-filter"}

    {/block}

    {block "content-top"}
        <div class="row">
            <div class="columns large-2 show-for-large">
                <div class="top-block">
                    {set $image = $model->getImage()}
                    {if $image}
                    <div class="image">
                        <img src="//cdn.{$site->getBaseDomain()}{$image->getURL()}" alt="{$model.brand}" itemprop="image">
                    </div>
                    {/if}
                </div>
            </div>
            <div class="columns large-10">
                <h1 class="title">{$model->brand}</h1>

                {if $model->descr}
                <section class="description show-for-medium">
                    <div class="row">
                        <div class="columns large-10">
                            <article class="content must-show-less" data-text-more="Read more" data-text-less="Read less">
                                {raw $model->descr}
                            </article>
                        </div>
                    </div>
                </section>
                {/if}

            </div>
        </div>
    {/block}


    {block 'after-content'}
        {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
    {/block}
{/if}