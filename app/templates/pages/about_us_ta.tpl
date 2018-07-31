{extends "pages/base.tpl"}
{block 'schema_page_type'}itemtype="http://schema.org/AboutPage"{/block}
{block 'content'}
    <article class="pages page about-us">
        <section class="heading">
            <div class="row w1280">
                <div class="columns large-12">
                    <div class="head">
                        <div class="cont">
                            <div class="row align-center">
                                <div class="column large-12">
                                    <h1>{$model->name}</h1>
                                </div>
                            </div>
                            {set $mass = $helper->getClearContent($model->content)}
                            <div class="row">
                                <div class="column small-12 medium-10">
                                    <div class="row">
                                        <div class="desc-cont">
                                            <div class="column large-12">
                                                <section class="desc">
                                                    Touche Airbrush is a new online store in the airbrush industry that has been specially created to sell only top-quality equipment and accessories from the most trusted brands in the US, Canada, and worldwide.
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="image-wrap">
                            <div class="row align-center">
                                <div class="column large-12 text-right">
                                    <div class="photo-video">
                                        <img data-src="/static/frontend/dist/images/pages/about-us/our_team.jpg" alt="our team" class="lazy lazy-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        {*<div class="hide-for-medium">*}
        {*<div class="row">*}
        {*<div class="column large-12">*}
        {*<div class="photo-video">*}
        {*<img src="/static/frontend/dist/images/p/about-us/our_team.jpg" alt="our team">*}
        {*</div>*}
        {*</div>*}
        {*</div>*}
        {*</div>*}

        <div class="row w1280">
            <div class="column large-12">
                <div class="content ">
                    <div class="">
                        {raw $mass['content']}
                    </div>





                        <section class="team">
                            <div class="row small-up-2 medium-up-3 ml-up-4 align-center">
                                {foreach $mass['members'] as $member}
                                    <div class="column column-block">
                                        <div class="info" itemscope itemtype="http://schema.org/Person">

                                            <div class="photo effect__img_zoom" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                                <img data-src="{$member['photo']}" alt="{$member['name']|escape}" class="lazy lazy-img">
                                                <link itemprop="url" href="{$member['photo']}">
                                            </div>

                                            <div class="name" itemprop="name">
                                                {$member['name']}
                                            </div>
                                            <div class="tag" itemprop="jobTitle">
                                                {$member['post']}
                                            </div>

                                        </div>
                                    </div>
                                {/foreach}
                            </div>
                        </section>


                    {*<section class="recognition">*}
                    {*<div class="row">*}
                    {*<div class="columns small-12">*}
                    {*<h3 class="weight-light">Recognition</h3>*}
                    {*</div>*}
                    {*</div>*}
                    {*</section>*}
                </div>
            </div>
        </div>





    </article>
{/block}