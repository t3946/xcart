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
                {include 'group/product/group.tpl' brand=$brand group_phrase=$brand->getNotModelAttribute('group_phrase') count=$brand->getNotModelAttribute('count') index=$brand@index}
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
            var th = $(this);

            th.toggleClass('open');

            if (th.hasClass('open')) {
                $.get(
                    th.data('url'),
                    {
                        level: parseInt(th.data('level')) + 1,
                        group_phrase: th.data('group-phrase'),
                        ajax: true
                    },
                    function(data) {
                        console.log(data)
                    }
                );
            }
        })
    </script>
{/block}