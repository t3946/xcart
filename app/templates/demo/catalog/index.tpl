{extends  "/demo/catalog/base.tpl"}

{block "catalog-sidebar"}
    <div class="top-block">
        <div class="image" id="image_left-top">
            <img src="/static/frontend/demo_images/category/1280/image.png" alt="image" />
        </div>
    </div>


    {include "demo/blocks/_search_departments.tpl"}
{/block}

{block "content-top"}
    <h1 class="title">[CATEGORY NAME] Oil Painting Sets</h1>

    {if rand(0,1)}
        <section class="description show-for-medium">
            <div class="row">
                <div class="columns large-10">
                    <article class="content must-show-less" data-text-more="Read more" data-text-less="Read less">
                        An artist deserves only the best brush and sets in order that a work of perfection is created. <br>
                        Of the various items that an artist requires, a brush is the single most important link between the artist and his creation.
                        An artist deserves only the best brush and sets in order that a work of perfection is created.
                        Of the various items that an artist requires, a brush is the single most important link between the artist and his creation.
                        Of the various items that an artist requires, a brush is the single most important link between the artist and his creation.
                        An artist deserves only the best brush and sets in order that a work of perfection is created.
                        Of the various items that an artist requires, a brush is the single most important link between the artist and his creation.
                        Of the various items that an artist requires, a brush is the single most important link between the artist and his creation.
                        An artist deserves only the best brush and sets in order that a work of perfection is created.
                        Of the various items that an artist requires, a brush is the single most important link between the artist and his creation.
                    </article>
                </div>
            </div>
        </section>
    {/if}

    {set $menus = $.getRandomSubmenu()}
    {set $menu = []}

    {foreach $menus.menu as $item}
        {set $menu[] = $item}
    {/foreach}
    {foreach $menus.menu as $item}
        {set $menu[] = $item}
    {/foreach}
    {foreach $menus.menu as $item}
        {set $menu[] = $item}
    {/foreach}

    {if $menus.menu|count > 0}
        <div class="subcategories">
            <div class="row small-up-1 medium-up-2 large-up-4">
                {foreach $menu as $item index=$index}
                    <div class="column {if $index > 11}more_items{/if}">
                        <a href="{$item.link}" class="subcategory_item">{$item.name} ({rand(1,1000)})</a>
                    </div>
                {/foreach}
            </div>
        </div>
    {/if}
{/block}


{block 'after-content'}
    {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
{/block}