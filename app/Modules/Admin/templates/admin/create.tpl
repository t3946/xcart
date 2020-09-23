{extends "admin/base.tpl"}
{block 'wrapper_block_class'}admin{/block}

{block 'heading'}
    {if $model->pk !== null}
        {set $actionTitle=$form->getName()~':'~~$model}
    {else}
        {set $actionTitle='Adding a ' ~ $model|strtolower}
    {/if}
    <h1>{$actionTitle}</h1>
{/block}

{block 'content'}
    {smarty_admin_block name=$actionTitle}
        <div class="form-page {block 'page_class'}create{/block}">
            <form action="{$.request->getUrl()}" enctype="multipart/form-data" method="post">
                <div class="form-data">
                    {include 'admin/form/_form.tpl'}
                </div>
                <div class="actions-panel">
                    <div class="buttons">
                        <button type="submit" name="save" value="save" class="button pad round">
                            Save
                        </button>

                        <button type="submit" name="save" value="save-stay" class="button transparent pad round">
                            Save and continue
                        </button>
                    </div>

                    <div class="links">
                        {if $form->getBottomUrls()}
                            {foreach $form->getBottomUrls() as $url}
                                <a target="_blank" href="{$url['url']}">
                                    <i class="icon-watch_on_site"></i>
                                    <span class="text">{$url['anchor']}</span>
                                </a>
                            {/foreach}
                        {/if}
                        {if $model->pk && $.php.method_exists($model, 'getAbsoluteUrl')}
                            <a target="_blank" href="{$model->getAbsoluteUrl()}">
                                <i class="icon-watch_on_site"></i>
                                <span class="text">
                                Show on site
                            </span>
                            </a>
                        {/if}

                        {if $model->pk}
                            <a href="{$admin->getRemoveUrl($model->pk)}" data-all="{$admin->getAllUrl()}"
                               data-prevention data-title="Are You Sure?" data-trigger="form-removed">
                                <i class="icon-delete_in_filter"></i>
                                <span class="text">
                                Delete
                            </span>
                            </a>
                        {/if}
                    </div>
                </div>
            </form>
        </div>
    {/smarty_admin_block}
{/block}

{block 'before-content'}
    {include 'admin/sections.tpl'}
{/block}