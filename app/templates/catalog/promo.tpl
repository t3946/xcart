{extends  "catalog/base.tpl"}

{if !$.request->getIsAjax()}
    {block "catalog-filter"}

    {/block}

    {block "content-top"}
        <div class="row">
            <div class="columns large-2 show-for-large">
                <div class="top-block">
                    {*<div class="image" id="image_left-top">*}
                        {*<img src="/static/frontend/demo_images/category/1280/image.png" alt="image" itemprop="image" />*}
                    {*</div>*}
                </div>
            </div>
            <div class="columns large-10">
                <h1 class="title" itemprop="name">
                    {$title}
                </h1>

                {*{if $model->description}*}
                {*<section class="description show-for-medium">*}
                    {*<div class="row">*}
                        {*<div class="columns large-10">*}
                            {*<article class="content must-show-less" data-text-more="Read more" data-text-less="Read less" itemprop="description">*}
                                {*{raw $model->description}*}
                            {*</article>*}
                        {*</div>*}
                    {*</div>*}
                {*</section>*}
                {*{/if}*}

            </div>
        </div>
    {/block}


    {block 'after-content'}
        {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
    {/block}
{/if}