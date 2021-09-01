{extends 'dashboard/layouts/search_layout.tpl'}

{block 'content'}

    {smarty_admin_block name='Order reports'}
        {include 'reports/admin/_reports.tpl'}

        <form action="{url 'reports:view'}" method="GET" id="report_form" target="_blank">
        {include 'reports/_report_fields.tpl'}
        <fieldset>

        {include 'dashboard/_filter_fields.tpl'}
        </fieldset>
        {include 'reports/layouts/_search_form_block.tpl'}
    </form>
    {/smarty_admin_block}

{/block}

{block 'js'}
{parent}

{/block}


