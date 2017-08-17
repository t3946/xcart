{extends 'group/layouts/group_list.tpl'}

{block 'group_list'}
    {if $brands}
        {foreach $brands as $brand}
            {include 'group/product/_brand_group.tpl' brand=$brand index=$brand@index}
        {/foreach}
    {else}
        <tr>
            <td align="center" colspan="5">No data found</td>
        </tr>
    {/if}

{/block}

