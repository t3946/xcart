{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block name=$section_title}
        {var $form = $admin->getForm()}

        <div class="admin-page all-page" >
            <div data-id="{$admin->ownerPk}" class="dx-mails"></div>
        </div>

    {/smarty_admin_block}
{/block}