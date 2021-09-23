<div class="item minimal product {if $item->isOutOfStockFrontend()} out_of_stock{/if} {if $item->isGroupRoot()} group{/if}"
     data-product="{$item->productid}"
     title="{$item->getFrontendName()}"
     itemscope
     itemtype="http://schema.org/Product"
     itemprop="itemListElement">

    <div class="image_container container">
        <a href="{$item->getAbsoluteUrl()}" class="link catalog-product-image-link">
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
        </a>
    </div>
</div>