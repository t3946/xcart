{if $brands}
    {foreach $brands as $brand}
        {include 'group/product/group.tpl' brand=$brand group_phrase=$brand->getNotModelAttribute('group_phrase') count=$brand->getNotModelAttribute('count') index=$brand@index}
    {/foreach}
{/if}