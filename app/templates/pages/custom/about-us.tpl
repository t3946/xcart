{extends "pages/base.tpl"}
{block 'schema_page_type'}itemtype="http://schema.org/AboutPage"{/block}
{block 'content'}
    <article class="pages page about-us">
        <section class="heading">
            <div class="row w1280">
                <div class="col-12">
                    <div class="head container">
                        <div class="cont">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <h1>{$model->name}</h1>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-10 ps-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="desc-cont">
                                                <section class="desc">
                                                    S3 Stores, Inc. sells the finest quality goods and merchandise from the best known brands in the USA, Canada, and worldwide.
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="image-wrap">
                            <div class="row justify-content-center">
                                <div class="col-12 text-right">
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

        <div class="container">
            <div class="row w1280">
            <div class="col-12">
                <div class="content ">
                    <div class="">
                        {raw $model->content}
                    </div>


                    <section class="managements">
                        <div class="row">
                            <div class="col-12 justify-content-center d-flex">
                                <div class="statistics-header">
                                    Since the creation of our company in 2005, we have shipped hundreds of thousands of orders to satisfied customers all over the world! We are grateful for your continued support!
                                </div>
                            </div>
                        </div>

                        <div class="row about-us-statistic_counters">
                            <div class="col-12 col-md-3 counting">
                                <div class="count">
                                    20
                                </div>
                                <div class="title">
                                    Stores
                                </div>
                            </div>

                            <div class="col-12 col-md-3 counting">
                                <div class="count">
                                    80000
                                </div>
                                <div class="title">
                                    Buyers
                                </div>
                            </div>

                            <div class="col-12 col-md-3 counting">
                                <div class="count">
                                    9
                                </div>
                                <div class="title">
                                    Countries
                                </div>
                            </div>

                            <div class="col-12 col-md-3 counting">
                                <div class="count">
                                    13
                                </div>
                                <div class="title">
                                    Working years
                                </div>
                            </div>

                        </div>
                    </section>

                    {set $team = $.getTeam}

                    {if $team}
                    <section class="team">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="block-title text-center weight-light">
                                    Leadership team
                                </h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="info" itemscope itemtype="http://schema.org/Person">
                                    <div class="photo">
                                        <img data-src="{$team[0]->getField('photo')->getUrl()}" alt="{$team[0]->name|escape}" class="lazy lazy-img" itemprop="image">
                                    </div>
                                    <div class="name" itemprop="name">
                                        {$team[0]->name}
                                    </div>
                                    <div class="tag" itemprop="jobTitle">
                                        {$team[0]->post}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {unset $team[0]}

                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 justify-content-center">
                            {foreach $team as $item}
                                <div class="col column-block">
                                <div class="info" itemscope itemtype="http://schema.org/Person">

                                    {if $item->photo2->getValue()}
                                        <div class="photo effect__flip effect__flip--vertical">
                                            <div class="front" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                                <img data-src="{$item->getField('photo')->getUrl()}" alt="{$item->name|escape}" class="lazy lazy-img">
                                                <link itemprop="url" href="{$item->getField('photo')->getUrl()}">
                                            </div>
                                            <div class="back" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                                <img data-src="{$item->getField('photo2')->getUrl()}" alt="{$item->name|escape}" class="lazy lazy-img">
                                                <link itemprop="url" href="{$item->getField('photo2')->getUrl()}">
                                            </div>
                                        </div>

                                    {else}
                                        <div class="photo effect__img_zoom" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                            <img data-src="{$item->getField('photo')->getUrl()}" alt="{$item->name|escape}" class="lazy lazy-img">
                                            <link itemprop="url" href="{$item->getField('photo')->getUrl()}">
                                        </div>
                                    {/if}
                                    <div class="name" itemprop="name">
                                        {$item->name}
                                    </div>
                                    <div class="tag" itemprop="jobTitle">
                                        {$item->post}
                                    </div>
                                </div>
                            </div>
                            {/foreach}
                        </div>
                    </section>
                    {/if}
                </div>
            </div>
        </div>
        </div>
    </article>
{/block}