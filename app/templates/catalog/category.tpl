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
                <div class="description show-for-medium">
                    <div class="row">
                        <div class="columns large-10 must-show-less">
                            <div class="relative">
                                <article class="content must-show-less" itemprop="description">
                                    {raw $model->description}
                                </article>
                                <div class="gradient collapse-gradient"></div>
                            </div>

                            {ignore}
                            <a class="show_more" onclick="$(this).hide().siblings('.show_less').show().end().siblings('.relative').find('article.must-show-less').addClass('full').end().find('.gradient').removeClass('gradient')">Read more</a>
                            <a class="show_less" onclick="$(this).hide().siblings('.show_more').show().end().siblings('.relative').find('article.must-show-less').removeClass('full').end().find('.collapse-gradient').addClass('gradient')">Read less</a>
                            {/ignore}
                        </div>
                    </div>
                </div>
                {/if}

                {*{cache key = 'category:' ~ $model->categoryid}*}
                {set $subcategories = $model->getSubcategories()}

                {if $subcategories|count > 0}
                <div class="subcategories">
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
                </div>
                {/if}
                {*{/cache}*}
            </div>
        </div>
    {/block}

{/if}