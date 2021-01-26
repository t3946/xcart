{extends "admin/base.tpl"}
{block 'wrapper_block_class'}admin{/block}

{block 'heading'}
    {if $model->pk === null}
        {set $actionTitle='Adding a new' ~~ $model|strtolower}
    {else}
        {if $form->getName()}
            {set $actionTitle=$form->getName()~':'~~$model}
        {else}
            {set $actionTitle=$model}
        {/if}
    {/if}
    <h1>{$actionTitle}</h1>
{/block}

{block 'content'}
    {smarty_admin_block name=$actionTitle}
        <div class="{block 'page_class'}create{/block}">
            <form action="{$.request->getUrl()}" enctype="multipart/form-data" method="post">
                <div class="form-data">
                    {block 'before_form'}{/block}
                    {include $admin->formTemplate}
                    <div class="row" style="margin-top: 15px;">
                        <div class="column text-center">
                            <button name="save" type="submit" value="save-stay">Save</button>
                        </div>
                    </div>
                    {block 'after_form'}{/block}
                </div>
            </form>
        </div>
    {/smarty_admin_block}
{/block}

{block 'before-content'}
    {include 'admin/sections.tpl' current_section=$admin->section sections=$form->getSections()}
{/block}