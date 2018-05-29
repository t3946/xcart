<div class="row">
    <div class="columns small-12">
        {include "catalog/parts/_state_line.tpl" hide_filter_button=true hide_sort=true}

        <div class="mobile_page_count hide-for-large page_count_wrap">
            {*{insert 'catalog/parts/_page_count.tpl'}*}
        </div>

        <div class="product-items tile-view" itemscope itemprop="mainEntity" itemtype="http://schema.org/OfferCatalog">
            {*{foreach $pager->paginate() as $item }*}
            {*{include "catalog/parts/_catalog_list_item.tpl" item=$item}*}
            {*{/foreach}*}
        </div>
        {include "catalog/parts/_state_line.tpl" hide_filter_button=true hide_sort=true}

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