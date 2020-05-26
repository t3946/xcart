{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block name='Distributor contacts'}
        <div>
            <b>Here indicate the address of the main distributor warehouse.</b>
            <br/>
            <br/>
        </div>
        {raw $form->renderBegin([
        'action' => $.app->router->url('admin:section', ['mid' => $distributorModel->manufacturerid, 'section' => $section]),
        'method' => 'POST',
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