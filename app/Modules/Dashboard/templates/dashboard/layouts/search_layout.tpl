{extends 'dashboard/layouts/dashboard_layout.tpl'}

{block 'heading'}
    <h1 align="center">Order search</h1>
{/block}

{block 'content'}

    {if !$form_collapse}
        {smarty_admin_block name='Search form'}
                {include 'dashboard/layouts/_search_form_block.tpl'}
        {/smarty_admin_block}
    {else}
        <fieldset class="{if $form_collapse}collapsed-force collapsed{else}expanded{/if}">
        <legend>Order search form</legend>
            {include 'dashboard/layouts/_search_form_block.tpl'}
        </fieldset>
    {/if}

    {if count($models) > 0}
        {*{smarty_admin_block name='Search results'}*}
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
        <div class="row">
            <div class="columns large-12">
                {if $new_template}
                    {include 'order/orders_list.tpl' orders=$models}
                {else}
                    {include 'order/orders_list_old.tpl' orders=$models}
                {/if}
            </div>
        </div>
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
        {*{/smarty_admin_block}*}
    {/if}
{/block}

{block 'menu_block'}

{/block}