{extends 'group/layouts/group_layout.tpl'}

{block 'heading'}
    <h1 align="center">
        Products group
    </h1>
{/block}

{block 'content'}

    {smarty_admin_block name='Group Products'}
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
        <table width="100%" cellspacing="1" cellpadding="3">
            <tr class="TableHead">
                <td width="1%"></td>
                <td width="40%">Brand</td>
                <td width="20%" align="center">Group phrase</td>
                <td width="20%" align="center">Products</td>
            </tr>
            {if $brands}
                {foreach $brands as $brand}
                        {include 'group/product/_brand_group.tpl' brand=$brand index=$brand@index}
                {/foreach}
            {else}
                <tr>
                    <td align="center" colspan="5">No data found</td>
                </tr>
            {/if}
        </table>
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
    {/smarty_admin_block}
{/block}