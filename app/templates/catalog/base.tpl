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
            {include "catalog/parts/_catalog_list_item.tpl" item=$item}
        {/foreach}
    {else}
        <section class="catalog-page">
        <div class="row">
            <div class="columns large-2 show-for-large">
                <form action="" type="get" name="fform">
                    {block "catalog-sidebar"}

                    {/block}
                    {include "catalog/parts/_filter.tpl" qs=$pager}
                    <button class="button">APPLY</button>

                </form>
            </div>

            <div class="columns large-10">
                <div class="top-block pcont">
                {block "content-top"}

                {/block}
                </div>


                {include "catalog/parts/_state_line.tpl"}
                <div class="page_count hide-for-large">
                    {include 'catalog/parts/_page_count.tpl'}
                </div>

                <div class="product-items {if $.isBot}tile-view{/if}">
                    {foreach $pager->paginate() as $item }
                        {include "catalog/parts/_catalog_list_item.tpl" item=$item}
                    {/foreach}
                </div>
                {include "catalog/parts/_state_line.tpl"}

                {raw $pager->render()}
            </div>
        </div>
    </section>
    {/if}


{/block}

{block 'after-content'}
    {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
{/block}