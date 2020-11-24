{extends "admin/base.tpl"}

{block 'wrapper_block_class'}admin{/block}

{block 'heading'}
    {if $model->pk !== null}
        {set $actionTitle=$form->getName()}
    {else}
        {set $actionTitle='Adding a ' ~ $model|strtolower}
    {/if}
    <h1>{$actionTitle}</h1>
{/block}

{block 'content'}
    {smarty_admin_block name=$actionTitle}
        <div class="{block 'page_class'}create{/block}">
            <form action="{$.request->getUrl()}" enctype="multipart/form-data" method="post">
                <div class="form-data">
                    {include 'deposited/_form.tpl'}
                </div>
            </form>
        </div>
    {/smarty_admin_block}
{/block}

{block 'before-content'}
    {include 'admin/sections.tpl'}
{/block}