{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block name=$section_title}
        <p style="margin-left: 20px">The person responsible for answering product questions</p>
        {raw $form->render()}
        <p style="margin-left: 20px">
            <a class="admin_link" href="{url route='admin:section'
            params = ['mid' => $distributorModel->manufacturerid, 'section' => 3]}">
                Select product question contact person here
            </a>
        </p>
    {/smarty_admin_block}
{/block}