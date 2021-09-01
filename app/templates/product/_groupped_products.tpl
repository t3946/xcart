<div class="row">
    <div class="columns small-12">
        {set $state_title}{t 'Product line'}{/set}
        {include "catalog/parts/_state_line.tpl" hide_filter_button=true hide_sort=true state_title=$state_title}

        <div class="mobile_page_count hide-for-large page_count_wrap">
        </div>

        <div class="product-items tile-view" itemscope itemprop="mainEntity" itemtype="http://schema.org/OfferCatalog">
        </div>
        {include "catalog/parts/_state_line.tpl" hide_filter_button=true hide_sort=true state_title=$state_title}

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