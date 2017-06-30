{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}


{block 'schema_page_type'}itemtype="http://schema.org/CollectionPage"{/block}
{block "content"}
    {if $.request->getIsAjax()}
        {foreach $pager->paginate() as $item }
            {include "catalog/parts/_catalog_list_item.tpl" item=$item}
        {/foreach}
    {else}
    <section class="catalog-page">
        {block "content-top"}

        {/block}
        <div class="row">
            <div class="columns large-2 show-for-large" itemscope itemtype="http://schema.org/WPSideBar">
                <div class="firm_cont">
                    <form action="{$.request->getMatchingUrl(['q' => $.request->get->get('q')])}" type="get"  id="filter_form" data-ajax-send="off">
                        <div class="filters_section advanced">
                            {block "catalog-filter"}
                                {*{include "catalog/parts/_filter.tpl" modal_class='filter advanced' filters=[]}*}
                            {/block}
                        </div>
                        <div class="filters_section default">
                            {include "catalog/parts/_filter.tpl" modal_class='filter default'}
                        </div>

                        <section class="buttons">
                            <button class="button">Apply</button>

                            <a href="{$.request->getMatchingUrl(['q' => $.request->get->get('q')])}" class="reset_filter" rel="nofollow">
                                <span class="text">
                                    Reset filters
                                </span>
                            </a>
                        </section>

                    </form>
                </div>

                {block "catalog-sidebar"}

                {/block}
            </div>

            <div class="columns large-10">
                {include "catalog/parts/_state_line.tpl"}
                <div class="mobile-reset-filter hide-for-large">
                    {include "catalog/parts/_filter_reset.tpl"}
                </div>

                <div class="mobile_page_count hide-for-large">
                    {include 'catalog/parts/_page_count.tpl'}
                </div>

                <div class="product-items {if $.isBot}tile-view{/if}" itemscope itemprop="mainEntity" itemtype="http://schema.org/OfferCatalog">
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