{extends 'dashboard/layouts/search_layout.tpl'}

{block 'content'}

    {smarty_admin_block name='Report options'}
        {include 'reports/admin/_reports.tpl'}

        <form action="{url 'reports:view'}" method="GET" target="_blank">
        {include 'reports/_report_fields.tpl'}
        <fieldset>
            <legend>
                Order search options
            </legend>
        {include 'dashboard/_filter_fields.tpl'}
        </fieldset>
        {include 'reports/layouts/_search_form_block.tpl'}
    </form>
    {/smarty_admin_block}

{/block}


