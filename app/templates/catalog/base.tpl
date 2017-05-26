{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}

{block "before-content"}
    {if !$.request->getIsAjax()}
    <div class="row">
        <div class="columns large-12">
            {include "base/_breadcrumbs.tpl"}
        </div>
    </div>
    {/if}
{/block}

{block "content"}
    {if $.request->getIsAjax()}
        {foreach $pager->paginate() as $item }
            {include "catalog/_parts/_catalog_list_item.tpl" item=$item}
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


                {include "catalog/_parts/_state_line.tpl"}
                <div class="page_count hide-for-large">
                    {include 'catalog/_parts/_page_count.tpl'}
                </div>

                <div class="product-items tile-view">
                    {foreach $pager->paginate() as $item }
                        {include "catalog/_parts/_catalog_list_item.tpl" item=$item}
                    {/foreach}
                </div>
                {include "catalog/_parts/_state_line.tpl"}

                {raw $pager->render()}
            </div>
        </div>
    </section>
    {/if}


{/block}

{block 'after-content'}
    {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
{/block}