{if $product->getThumbnails()}
    {foreach from=$product->getThumbnails() item=thumb name=group}
        {if $smarty.foreach.group.index < 4}
            <img style="width:50%; height: 50%; float:left; padding:0; border: 4px solid white; box-sizing: border-box;" src="{include file="product_image_src.tpl" tmbn_url=$thumb->getUrl()}" title="{$product->getTitle()|escape}" alt="{$product->getTitle()|escape}" />
        {/if}
    {/foreach}
{/if}