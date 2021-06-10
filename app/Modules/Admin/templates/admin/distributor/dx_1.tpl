{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {if $distributorModel->getIsNewRecord()}
        {set $url = $.app->router->url('admin:dx_add')}
    {else}
        {set $url = $.app->router->url('admin:section', ['mid' => $distributorModel->manufacturerid, 'section' => $section])}
    {/if}
    {smarty_admin_block name=$section_title}
    {raw $form->renderBegin([
    'action' => $url,
    'method' => 'POST',
    'enctype' => 'multipart/form-data'
    ])}
    {raw $form->render()}
    <div class="row" style="margin-top: 15px;">
        <div class="col-3"></div>
        <div class="col-9">
            <button type="submit" class="button_outline">Save</button>
        </div>
    </div>

    {raw $form->renderEnd()}
    {/smarty_admin_block}
{/block}