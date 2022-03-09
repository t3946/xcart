{extends  "catalog/base.tpl"}
{set $brand = $model}

{if !$.request->getIsAjax()}
    {block "catalog-filter"}

    {/block}

    {block "content-top"}
        <div class="row">
            <div class="col-2 d-none d-lg-block">
                <div class="top-block">
                    {set $image = $model->getImage()}
                    {if $image}
                    <div class="image test">
                        <img src="//cdn.{$.getSite->getBaseDomain()}/{$image}" alt="{$model.brand}" itemprop="image">
                    </div>
                    {/if}
                </div>
            </div>

            <div class="col-12 col-lg-10">
                <h1 class="title">{$model->brand}</h1>

                {if $model->descr}
                <div class="description show-for-medium">
                    <div class="row">
                        <div class="col-10 must-show-less">
                            <div class="relative">
                                <article class="content must-show-less" itemprop="description">
                                {raw $model->descr}
                                </article>
                                <div class="gradient collapse-gradient"></div>
                            </div>

                            {ignore}
                                <a class="show_more" onclick="$(this).hide().siblings('.show_less').show().end().siblings('.relative').find('article.must-show-less').addClass('full').end().find('.gradient').removeClass('gradient')">{/ignore}{t 'Read more'}{ignore}</a>
                                <a class="show_less" onclick="$(this).hide().siblings('.show_more').show().end().siblings('.relative').find('article.must-show-less').removeClass('full').end().find('.collapse-gradient').addClass('gradient')">{/ignore}{t 'Read less'}</a>

                        </div>
                    </div>
                </div>
                {/if}

                {if $categories|count > 0}
                    <div class="subcategories">
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
                                            {if $item->active_product_count}
                                                <span class="count">
                                                    ({$item->active_product_count})
                                                </span>
                                            {/if}
                                        </a>
                                    </div>
                                {/foreach}
                            </div>
                            <div class="row align-right">
                                <div class="columns large-12">

                                    <span class="hide-for-modal show_more" data-target="#sub_list" data-text-more="{t 'More categories'}" data-text-less="{t 'Less categories'}">
                                        {t 'More categories'}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                {/if}

            </div>
        </div>
    {/block}
{/if}