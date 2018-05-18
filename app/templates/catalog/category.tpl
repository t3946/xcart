{extends  "catalog/base.tpl"}

{if !$.request->getIsAjax()}
    {block "catalog-filter"}

    {/block}

    {block "content-top"}
        <div class="row">
            <div class="columns large-2 show-for-large">
                <div class="top-block">
                    {*<div class="image" id="image_left-top">
                        <img src="/static/frontend/demo_images/category/1280/image.png" alt="image" itemprop="image" />
                    </div>*}
                </div>
            </div>
            <div class="columns large-10">
                <h1 class="title" itemprop="name">{$model->category}</h1>

                {if $model->description}
                <section class="description show-for-medium">
                    <div class="row">
                        <div class="columns large-10">
                            <article class="content must-show-less" data-text-more="Read more" data-text-less="Read less" itemprop="description">
                                {raw $model->description}
                            </article>
                        </div>
                    </div>
                </section>
                {/if}

                {*{cache key = 'category:' ~ $model->categoryid}*}
                {set $subcategories = $model->getSubcategories()}

                {if $subcategories|count > 0}
                <section class="subcategories">
                    <a href="#subdepartmens" class="hide-for-large mmodal sub-dep_button">
                        See subdepartments
                    </a>
                    <div class="show-for-large" >
                        <div id="subdepartmens">
                            <div class="block-title show-for-modal">
                                All subdepartments
                            </div>

                            <div class="row small-up-1 medium-up-2 large-up-4 sub_list">
                                {foreach $subcategories as $item index=$index}
                                    <div class="column {if $index > 11}more_items{/if}">
                                        <a href="{$item->getAbsoluteUrl()}" class="subcategory_item">
                                            {$item->category}
                                            {if $item->active_product_count}
                                                <span class="count">
                                                    ({$item->active_product_count})
                                                </span>
                                            {/if}
                                        </a>
                                    </div>
                                {/foreach}
                            </div>
                        </div>

                    </div>
                </section>
                {/if}
                {*{/cache}*}
            </div>
        </div>
    {/block}

{/if}