{extends 'amazon/layouts/amazon_layout.tpl'}

{block 'heading'}
    <h1 align="center">Amazon reordering</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Products Filter'}
        {include 'amazon/reordering/_filter_products.tpl'}
    {/smarty_admin_block}

    {smarty_admin_block name='Products for amazon reordering'}
    {foreach $amazon_products as $distributor => $products}
        <fieldset {if $amazon_products@first}class="expanded"{/if}>
            <legend>{$distributor} ({count($products)})</legend>
            {include 'amazon/reordering/_distributor_products.tpl'}
        </fieldset>
    {/foreach}
    {/smarty_admin_block}
{/block}

{block 'js'}
    {parent}
    <script type="text/javascript">
        (function(){
            $('input.group-apply').click(function(){
                var fill_val = $(this).siblings('input.group-apply-val');
                $(this).closest('table').find('input.restocking-qty').each(function(){
                    $(this).val(fill_val.val());
                })
            });
        })();
    </script>
{/block}
