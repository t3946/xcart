{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block is_show_title=false}
        {var $form = $admin->getForm()}
        {raw $form->renderBegin([
        'action' => $admin->getUpdateAllUrl(),
        'method' => 'POST',
        ])}
        <div class="all-page">
            {include 'admin/distributor/form/list/_list.tpl'}
        </div>

        {raw $form->renderEnd()}
    {/smarty_admin_block}
{/block}