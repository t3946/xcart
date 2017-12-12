{if $brands}
    {foreach $brands as $brand}
        {include 'group/product/group.tpl' brand=$brand group_phrase=$brand->getFromQueryAttribute('group_phrase') count=$brand->getFromQueryAttribute('count') index=$brand@index}
    {/foreach}
{/if}