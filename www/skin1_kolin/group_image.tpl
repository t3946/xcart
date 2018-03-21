{assign var=thumbnail value=$product->getThumbnail()}
{if $thumbnail}
    <img {if $lazy}class="lazy" data-lazy="{include file="product_image_src.tpl" tmbn_url=$thumbnail->getUrl()}"{/if}
         style="width:50%; height: 50%; float:left; padding:0; border: 4px solid white; box-sizing: border-box;"
            {if !$lazy}src="{include file="product_image_src.tpl" tmbn_url=$thumbnail->getUrl()}"{/if}
         title="{$product->getFrontendName()|escape}" alt="{$product->getFrontendName()|escape}" data-product-id="{$product->productid}"  />
{/if}