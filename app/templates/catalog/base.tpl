{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}


{block 'schema_page_type'}itemtype="http://schema.org/CollectionPage"{/block}
{block "content"}
    {if $.request->getIsAjax() && $pager}
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
                    <form action="{$.request->getMatchingUrl(['q' => $.request->get->get('q')])}" method="get"  id="filter_form" data-ajax-send="off">
                        <div class="filters_section advanced">
                            {block "catalog-filter"}
                                {*{include "catalog/parts/_filter.tpl" modal_class='filter advanced' filters=[]}*}
                            {/block}
                        </div>
                        <div class="filters_section default">
                            {include "catalog/parts/_filter.tpl" modal_class='filter default'}
                        </div>

                        <div class="buttons">
                            <button class="button waves">{t 'Apply'}</button>

                            <a href="{$.request->getMatchingUrl(['q' => $.request->get->get('q')])}" class="reset_filter waves waves-dark" rel="nofollow">
                                <span class="text">
                                    {t 'Reset filters'}
                                </span>
                            </a>
                        </div>

                    </form>
                    {/if}
                </div>

                {block "catalog-sidebar"}

                {/block}
            </div>

            <div class="columns large-10">
                <div class="catalog-component"
                     data-sorting-options='{str_replace("'", '&#39;', json_encode($sort_arr))}'
                     data-current-sorting-key="{$sort}"
                     data-hide-sort="{$hide_sort}"
                     data-checkout-url="{Modules\Order\Helpers\OrderHelper::getCheckoutUrl()}"
                     data-search-text="{$q|escape}"
                >
                    {*скелеты*}
                    <div class="catalog-skeleton">
                        <div class="sceleton products-state-line"></div>
                        <div class="product-items tile-view product-items__tile">
                            {foreach 1..20 as $counter}
                                <div class="catalog-product__tile catalog-product_tile catalog-product item">
                                    <div class="sceleton" style="margin: 0 0 10px 0; height: 172px"></div>
                                    <div class="sceleton" style="margin: 0 0 5px 0; height: 40px"></div>
                                    <div class="sceleton" style="margin: 0 0 5px 0; height: 15px"></div>
                                    <div style="justify-content: space-between; display: flex">
                                        <div class="sceleton" style="width: 47.5%; height: 35px"></div>
                                        <div class="sceleton" style="width: 47.5%; height: 35px"></div>
                                    </div>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {/if}


{/block}

