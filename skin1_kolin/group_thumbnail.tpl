{foreach from=$product->getFrontendChilds() item=child name=group}
   {if $smarty.foreach.group.index < 4}
        {include file="group_image.tpl" product=$child}
   {/if}
{/foreach}
