{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}

{block "before-content"}
    <div class="row">
        <div class="columns large-12">
            {include "demo/blocks/_breadcrumbs.tpl"}
        </div>
    </div>
{/block}

{block "content"}
    {if $.request->getIsAjax()}
        {foreach $models as $item }
            {include "demo/blocks/_catalog_list_item.tpl" item=$item}
        {/foreach}
    {else}
        <section class="catalog-page">
        <div class="row">
            <div class="columns large-2 show-for-large">
                {block "catalog-sidebar"}

                {/block}
                {*{include "demo/blocks/_category_filter.tpl"}*}
            </div>

            <div class="columns large-10">
                <div class="top-block pcont">
                {block "content-top"}

                {/block}
                </div>


                {include "demo/catalog/_state_line.tpl"}
                <div class="page_count hide-for-large">
                    <span class="count">10</span> / <span class="full">100</span> items shown
                </div>

                <div class="product-items tile-view">
                    {foreach $models as $item }
                        {include "demo/blocks/_catalog_list_item.tpl" item=$item}
                    {/foreach}
                </div>
                {include "demo/catalog/_state_line.tpl"}
            </div>
        </div>
    </section>
    {/if}


{/block}

{block 'after-content'}
    {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
{/block}