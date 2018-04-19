<div class="item minimal product{if $item->isOutOfStock()} out_of_stock{/if} {if $item->isGroupRoot()} group{/if}"
     data-product="{$item->productid}"
     {*data-uid="{$item->getUniqueId()}"*}
     {*data-prices='{$item->getPrices()|json_encode}'*}
     {*{if $item->getFrontendPrice() < $item->list_price}*}
     {*data-list-price="{$item->list_price}"*}
     {*{/if}*}
     {*data-price-precalc*}
     {*data-cart-action="{url 'cart:quantity:set:post' key=$item->getUniqueId()}"*}
     title="{$item->getFrontendName()}"
     itemscope
     itemtype="http://schema.org/Product"
     itemprop="itemListElement">

        <div class="image_container container">
            <a href="{$item->getAbsoluteUrl()}" class="link">


                {if $item->isGroupRoot()}
                    {set $childrens = $item->getFrontendChilds()->limit(1)->all()}
                    <div class="images images-many images-{$childrens|count}">
                        {foreach $childrens as $child}
                            {include "catalog/parts/_item_image.tpl" model=$child}
                        {/foreach}
                    </div>
                {else}
                    <div class="images images-1">
                        {include "catalog/parts/_item_image.tpl" model=$item}
                    </div>
                {/if}


                {*{if $item->isNewProduct()}*}
                    {*<span class="splash splash-new show-for-large">New</span>*}
                {*{/if}*}

                {*{if $item->isSaleSticker()}*}
                    {*<span class="splash splash-sale show-for-large">Sale</span>*}
                {*{/if}*}

                {*{if $item->isOutOfStock()}*}
                    {*<span class="splash splash-out">Out of stock</span>*}
                {*{/if}*}

            </a>
            {*<a href="#" class="button yellow-white button-quick-view hide waves">quick view</a>*}
        </div>
        {*<div class="info_container container">*}
            {*<h4 class="title " itemprop="name">*}
                {*{set $title = $item->getFrontendName()}*}

                {*<a href="{$item->getAbsoluteUrl()}" title="{$title}">*}
                    {*{if $q!}*}
                        {*{raw $title|words_highlight:$q:"span.highlight"}*}
                    {*{else}*}
                        {*{raw $title}*}
                    {*{/if}*}
                {*</a>*}
            {*</h4>*}

            {*<div class="sku show-for-large">*}
                {*<span class="value">*}
                    {*SKU: <span class="style" itemprop="sku">{$item.productcode}</span>*}
                {*</span>*}
                {*<a data-tooltip class="has-tip right " title="What is SKU">?</a>*}
            {*</div>*}

            {*{add $brand = $item->cache()->brand}*}

            {*{if $brand}*}
            {*<div class="brand show-for-small">*}

                {*Brand:*}
                {*<a class="value" itemprop="brand"  href="{$brand->getAbsoluteUrl()}">*}
                    {*{$brand->brand}*}
                {*</a>*}
            {*</div>*}
            {*{/if}*}

            {*{if $item->getFrontendDescription()}*}
                {*{set $description = $item->getFrontendDescription()}*}

                {*<div class="description show-for-medium" >*}
                    {*<span itemprop="description">*}
                        {*{set $description = $description|br2nl|strip_tags|truncate:140:'...'|nl2space}*}

                        {*{if $q!}*}
                            {*{raw $description|words_highlight:$q:"span.highlight"}*}
                        {*{else}*}
                            {*{raw $description}*}
                        {*{/if}*}
                    {*</span>*}

                    {*<a href="{$item->getAbsoluteUrl()}" class="show-for-medium see">See details</a>*}
                {*</div>*}

                {*<noindex>*}
                    {*<div class="description show-for-small hide-for-medium">*}
                        {*{set $description = $description|br2nl|strip_tags|truncate:70:'...'|nl2space}*}

                        {*{if $q!}*}
                            {*{raw $description|words_highlight:$q:"span.highlight"}*}
                        {*{else}*}
                            {*{raw $description}*}
                        {*{/if}*}
                    {*</div>*}
                {*</noindex>*}
            {*{/if}*}


            {*{set $p_list = $item->getParamList()}*}
            {*{if $p_list}*}
                {*<div class="parameters show-for-medium">*}
                    {*<ul class="no-bullet">*}
                        {*{foreach $p_list as $param index=$index}*}
                            {*<li>*}
                                {*{$param.name}: {raw $param.values|join}*}
                            {*</li>*}

                            {*{if $index >= 3}*}
                                {*{break}*}
                            {*{/if}*}
                        {*{/foreach}*}

                    {*</ul>*}
                {*</div>*}
            {*{/if}*}

        {*</div>*}
</div>