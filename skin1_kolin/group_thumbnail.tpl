{foreach from=$product->getFrontendChilds() item=child name=group}
   {if $smarty.foreach.group.index < 4}
       {assign var=thumbnail value=$child->getThumbnail()}
       {if $thumbnail}
            <img {if $lazy}data-lazy="{include file="product_image_src.tpl" tmbn_url=$thumbnail->getUrl()}"{/if}
                 style="width:50%; height: 50%; float:left; padding:0; border: 4px solid white; box-sizing: border-box;"
                 {if !$lazy}src="{include file="product_image_src.tpl" tmbn_url=$thumbnail->getUrl()}"{/if}
                 title="{$child->getTitle()|escape}" alt="{$child->getTitle()|escape}" data-product-id="{$child->productid}"  />
        {/if}
   {/if}
{/foreach}
