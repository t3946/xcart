{extends "admin/base.tpl"}

{block 'wrapper_block_class'}admin{/block}

{block 'heading'}
    <h1>{$admin->name}</h1>
{/block}

{block 'main_block'}
    {if $filter_form}
        <div class="admin-filter">
            {include $admin->filterTemplate form=$filter_form}
        </div>
    {/if}
    <div class="admin-page all-page">
        {include $admin->allList}
    </div>
{/block}