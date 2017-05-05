{extends 'amazon/layouts/amazon_layout.tpl'}

{block 'heading'}
    <h1 align="center">Amazon reordering</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Products for amazon reordering'}
        {foreach $amazon_products as $distributor => $products}
            <fieldset {if $amazon_products@first}class="expanded"{/if}>
                <legend>{$distributor} ({count($products)})</legend>
                {include 'amazon/reordering/_distributor_products.tpl'}
            </fieldset>
        {/foreach}
    {/smarty_admin_block}
{/block}