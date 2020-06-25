{extends "admin/base.tpl"}

{block 'heading'}
    <h1>{$admin->name}</h1>
{/block}

{block 'main_block'}
    <div class="email-all admin-page all-page">
        {include 'admin/list/_list.tpl'}
    </div>
{/block}