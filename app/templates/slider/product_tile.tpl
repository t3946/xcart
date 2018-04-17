<div class="item product{if $item->isOutOfStock()} out_of_stock{/if} {if $item->isGroupRoot()} group{/if}"
     data-product="{$item->productid}"
    {*data-uid="{$item->getUniqueId()}"*}
     data-prices='{$item->getPrices()|json_encode}'
    {if $item->getFrontendPrice() < $item->list_price}
     data-list-price="{$item->list_price}"
    {/if}
>
    <div class="image_container container">
        <a href="{$item->getAbsoluteUrl()}" title="{$item.product}" class="link">


            {if $item->isGroupRoot()}
                {set $childrens = $item->getFrontendChilds()->limit(4)->all()}
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


            {if $item->isNewProduct()}
                <span class="splash splash-new show-for-large">New</span>
            {/if}

            {if $item->isSaleSticker()}
                <span class="splash splash-sale show-for-large">Sale</span>
            {/if}
        </a>
    </div>


</div>