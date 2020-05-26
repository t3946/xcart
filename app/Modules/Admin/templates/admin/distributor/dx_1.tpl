{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {if $distributorModel->getIsNewRecord()}
        {set $url = $.app->router->url('admin:dx_add')}
    {else}
        {set $url = $.app->router->url('admin:section', ['mid' => $distributorModel->manufacturerid, 'section' => $section])}
    {/if}
    {smarty_admin_block name='General distributor information'}
    {raw $form->renderBegin([
    'action' => $url,
    'method' => 'POST',
    'enctype' => 'multipart/form-data'
    ])}
    {raw $form->render()}
        <div class="row" style="margin-top: 15px;">
            <div class="column text-center">
                <button type="submit">Save</button>
            </div>
        </div>

    {raw $form->renderEnd()}
    {/smarty_admin_block}
{/block}