{extends 'reports/layouts/search_layout.tpl'}

{block 'heading'}
    <h1 align="center">Report {if $model->getIsNewRecord()}create{else}"{$model->name}" edit{/if}.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name = 'Report options'}

        <form action="{$model->getAdminUrl()}" id="report_edit_form" method="POST">
            {include 'reports/_report_fields.tpl'}
            <fieldset>
                <legend>
                    Order search options
                </legend>
                {include 'dashboard/_filter_fields.tpl'}
            </fieldset>
            {include 'core/form/buttons.tpl'}
        </form>
    {/smarty_admin_block}
{/block}