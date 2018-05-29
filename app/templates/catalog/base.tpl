{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}


{block 'schema_page_type'}itemtype="http://schema.org/CollectionPage"{/block}
{block "content"}
    {if $.request->getIsAjax()}
        {foreach $pager->paginate() as $item }
            {include "catalog/parts/_catalog_list_item.tpl" item=$item}
        {/foreach}
    {else}
    <section class="catalog-page default-content-page">
        {block "content-top"}

        {/block}
        <div class="row">
            <div class="columns large-2 show-for-large" itemscope itemtype="http://schema.org/WPSideBar">
                <div class="firm_cont">
                    {if $filters!}
                    <form action="{$.request->getMatchingUrl(['q' => $.request->get->get('q')])}" method="get" id="filter_form" data-ajax-send="off">
                        <div class="filters_section advanced">
                            {block "catalog-filter"}
                                {*{include "catalog/parts/_filter.tpl" modal_class='filter advanced' filters=[]}*}
                            {/block}
                        </div>
                        <div class="filters_section default">
                            {include "catalog/parts/_filter.tpl" modal_class='filter default'}
                        </div>

                        <section class="buttons">
                            <button class="button waves">Apply</button>

                            <a href="{$.request->getMatchingUrl(['q' => $.request->get->get('q')])}" class="reset_filter waves waves-dark" rel="nofollow">
                                <span class="text">
                                    Reset filters
                                </span>
                            </a>
                        </section>

                    </form>
                    {/if}
                </div>

                {block "catalog-sidebar"}

                {/block}
            </div>

            <div class="columns large-10">
                {insert "catalog/parts/_state_line.tpl"}
                <div class="mobile-reset-filter hide-for-large">
                    {insert "catalog/parts/_filter_reset.tpl"}
                </div>

                <div class="mobile_page_count hide-for-large page_count_wrap">
                    {*{insert 'catalog/parts/_page_count.tpl'}*}
                </div>

                <div class="product-items tile-view" itemscope itemprop="mainEntity" itemtype="http://schema.org/OfferCatalog">
                    {*{foreach $pager->paginate() as $item }*}
                        {*{include "catalog/parts/_catalog_list_item.tpl" item=$item}*}
                    {*{/foreach}*}
                </div>
                {insert "catalog/parts/_state_line.tpl"}

                {raw $pager->render()}

                {add_asset_block type="js"}
                    <script>
                        window.app.afterReady.push(function(){
                            endless_paginate();
                        });
                    </script>
                {/add_asset_block}
            </div>
        </div>
    </section>
    {/if}


{/block}

