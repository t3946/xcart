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
                {set $pager_data = ['pageSize' => $pager->getPageSize(), 'currentPage' => $pager->getPage(), 'paginateCount' => count($pager->paginate()), 'total' => $pager->getTotal()]}

                <div class="catalog-component"
                     data-sorting-options='{str_replace("'", '&#39;', json_encode($sort_arr))}'
                     data-current-sorting-key="{$sort}"
                     data-hide-sort="{$hide_sort}"
                     data-pager='{str_replace("'", '&#39;', json_encode($pager_data))}'
                     data-catalog-url="{$pager->createView()->getUrl(1)}"
                     data-checkout-url="{Modules\Order\Helpers\OrderHelper::getCheckoutUrl()}"
                     data-search-text="{$q|escape}"
                ></div>
            </div>
        </div>
    </section>
    {/if}


{/block}

