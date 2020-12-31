{extends "admin/all.tpl"}

{block 'heading'}
    <h1>{$admin->name} |
        <a target="_blank" href="{url route='admin:list' params = ['module' => 'Forms', 'admin' => 'TemplateCategoryAdmin']}">
            Template categories
        </a>
    </h1>
{/block}