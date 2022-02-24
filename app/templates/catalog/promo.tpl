{extends  "catalog/base.tpl"}

{if !$.request->getIsAjax()}
    {block "catalog-filter"}

    {/block}

    {block "content-top"}
        <div class="row">
            <div class="col-2 d-none d-lg-block">
                <div class="top-block">
                    {*<div class="image" id="image_left-top">*}
                        {*<img src="/static/frontend/demo_images/category/1280/image.png" alt="image" itemprop="image" />*}
                    {*</div>*}
                </div>
            </div>

            <div class="col-12 col-lg-10">
                <h1 class="title fw-bold" itemprop="name">
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
                                <a class="show_more" onclick="$(this).hide().siblings('.show_less').show().end().siblings('.relative').find('article.must-show-less').addClass('full').end().find('.gradient').removeClass('gradient')">{/ignore}{t 'Read more'}{ignore}</a>
                                <a class="show_less" onclick="$(this).hide().siblings('.show_more').show().end().siblings('.relative').find('article.must-show-less').removeClass('full').end().find('.collapse-gradient').addClass('gradient')">{/ignore}{t 'Read less'}</a>

                        </div>
                    </div>
                </div>
                {/if}

            </div>
        </div>
    {/block}

{/if}