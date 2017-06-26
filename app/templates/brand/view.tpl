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

                {if $categories|count > 0}
                    <section class="subcategories">
                    <a href="#subdepartmens" class="hide-for-large mmodal sub-dep_button">
                        See departments
                    </a>
                    <div class="show-for-large" >
                        <div id="subdepartmens">
                            <div class="block-title show-for-modal">
                                All departments
                            </div>

                            <div class="row small-up-1 medium-up-2 large-up-4 sub_list" id="sub_list">
                                {foreach $categories as $item index=$index}
                                    <div class="column {if $index > 11}more_items{/if}">
                                        <a href="{$item->getAbsoluteUrl()}" class="subcategory_item">
                                            {$item->category}
                                            {if $item->getFromQueryAttribute('pcount')}
                                                <span class="count">
                                                    ({$item->getFromQueryAttribute('pcount')})
                                                </span>
                                            {/if}
                                        </a>
                                    </div>
                                {/foreach}
                            </div>
                            <div class="row align-right">
                                <div class="columns large-3">

                                    <span class="hide-for-modal show_more" data-target="#sub_list" data-text-more="More categories" data-text-less="Less categories">
                                        More categories
                                    </span>
                                </div>
                            </div>
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