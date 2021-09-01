{extends 'dashboard/layouts/dashboard_layout.tpl'}

{block 'heading'}
    <h1 align="center">
        Order search ({$pager->getTotal()})

        <a href="#help_search" class="float-right mmodal">
            <i class="fa fa-question-circle"></i>
        </a>
    </h1>
{/block}

{block 'content'}

    {if !$form_collapse}
        {smarty_admin_block name='Search form'}
                {include 'dashboard/layouts/_search_form_block.tpl'}
        {/smarty_admin_block}
    {else}
        <fieldset class="{if $form_collapse}collapsed-force collapsed{else}expanded{/if} collapsible">
        <legend>Order search form</legend>
            {include 'dashboard/layouts/_search_form_block.tpl'}
        </fieldset>
    {/if}

    {if count($models) > 0}
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
        <div class="row">
            <div class="columns large-12">
                {include 'order/orders_list.tpl' orders=$models}
            </div>
        </div>
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
    {/if}

    <div class="hidden">
        <div id="help_search">
            {$.call.Modules.Core.Models.LanguageModel::translate('lbl_order_search_hint')}
        </div>
    </div>
{/block}