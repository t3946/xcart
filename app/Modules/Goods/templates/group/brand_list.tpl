{extends 'group/layouts/group_list.tpl'}

{block 'group_list'}
    {if $brands}
        {raw $pager}
        <table width="100%">

            <tr class="TableHead">
                <td>Brand</td>
                <td>Group phrase</td>
                <td>Products count</td>
            </tr>
            {foreach $brands as $brand}
                {include 'group/product/_brand_group.tpl' brand=$brand index=$brands@index}
            {/foreach}
        </table>
        {raw $pager}
    {else}
        <tr>
            <td align="center" colspan="5">No data found</td>
        </tr>
    {/if}

{/block}

