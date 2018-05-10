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

                            <div class="row">
                                <div class="column small-12 medium-10">
                                    <div class="row">
                                        <div class="desc-cont">
                                            <div class="column large-12">
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
                            <div class="row align-center">
                                <div class="column large-12 text-right">
                                    <div class="photo-video">
                                        <img data-src="/static/frontend/dist/images/p/about-us/our_team.jpg" alt="our team" class="lazy lazy-img">
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
                    <div class="large-2column">
                        {raw $model->content}
                    </div>


                    <section class="managements">
                        <div class="row">
                            <div class="column large-12">
                                <div class="text">
                                    Since the creation of our company in 2005, we have shipped hundreds of thousands of orders to satisfied customers all over the world!
                                    &shy;
                                    We are grateful for your continued support!
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 medium-3 counting">
                                <div class="count">
                                    20
                                </div>
                                <div class="title">
                                    Stores
                                </div>
                            </div>

                            <div class="columns small-12 medium-3 counting">
                                <div class="count">
                                    80000
                                </div>
                                <div class="title">
                                    Buyers
                                </div>
                            </div>

                            <div class="columns small-12 medium-3 counting">
                                <div class="count">
                                    9
                                </div>
                                <div class="title">
                                    Countries
                                </div>
                            </div>

                            <div class="columns small-12 medium-3 counting">
                                <div class="count">
                                    11
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
                                <div class="column small-12">
                                    <h3 class="block-title text-center weight-light">
                                        Leadership team
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column small-12">
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

                            <div class="row small-up-2 medium-up-3 ml-up-4 align-center">

                                {foreach $team as $item}

                                    <div class="column column-block">
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