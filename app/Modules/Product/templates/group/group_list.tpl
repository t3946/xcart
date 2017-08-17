{extends 'group/layouts/group_list.tpl'}

{block 'group_list'}
    <table class="product_group" width="100%" cellspacing="1" cellpadding="3">
        <tr class="TableHead">
            <td width="1%"></td>
            <td width="20%" align="center">Group phrase</td>
            <td width="20%" align="center">Products</td>
        </tr>

        {if $brands}
            {foreach $brands as $brand}
                {include 'group/product/_group.tpl' brand=$brand index=$brand@index}
            {/foreach}
        {else}
            <tr>
                <td align="center" colspan="5">No data found</td>
            </tr>
        {/if}
    </table>
{/block}

{block 'js'}
    <script type="text/javascript">
        $('.product_group').on('click', '.tree_cell', function(){
            var url_group_level = '{url 'product:group' id=$batch_id}';

            $(this).toggleClass('open');
            if ($(this).hasClass('open')) {
                $.get()
            }
        })
    </script>
{/block}