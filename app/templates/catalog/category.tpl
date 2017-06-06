{extends  "catalog/base.tpl"}

{if !$.request->getIsAjax()}
    {block "catalog-sidebar"}
        <div class="top-block">
            <div class="image" id="image_left-top">
                <img src="/static/frontend/demo_images/category/1280/image.png" alt="image" />
            </div>
        </div>
    {/block}

    {block "content-top"}
        <h1 class="title">{$model->category}</h1>

        {if $model->description}
            <section class="description show-for-medium">
                <div class="row">
                    <div class="columns large-10">
                        <article class="content must-show-less" data-text-more="Read more" data-text-less="Read less">
                            {raw $model->description}
                        </article>
                    </div>
                </div>
            </section>
        {/if}

        {set $subcategories = $model->getSubcategories()}

        {if $subcategories|count > 0}
            <section class="subcategories">
                <div class="row small-up-1 medium-up-2 large-up-4">
                    {foreach $subcategories as $item index=$index}
                        <div class="column {if $index > 11}more_items{/if}">
                            <a href="{$item->getAbsoluteUrl()}" class="subcategory_item">{$item->category} ({$item->getFromQueryAttribute('pcount')})</a>
                        </div>
                    {/foreach}
                </div>
            </section>
        {/if}
    {/block}


    {block 'after-content'}
        {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
    {/block}
{/if}