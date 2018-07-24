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

                {set $site = $.app->getModule('Sites')->getSite()}
                {set $description = $.fetch_info_block('bestsellers', null, ['sfcode' => $site->code])}

                {if $description}
                <div class="description show-for-medium">
                    <div class="row">
                        <div class="columns large-10 must-show-less">
                            <div class="relative">
                                <article class="content must-show-less" itemprop="description">
                                    {$description}
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

            </div>
        </div>
    {/block}

{/if}