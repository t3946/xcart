{extends 'dashboard/layouts/dashboard_layout.tpl'}


{block 'heading'}
    <h1 align="center">Group {if $model->getIsNewRecord()}create{else}"{$model}" edit{/if}.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name = 'Form'}
        <div class="row">
            <div class="columns large-12">
                {include 'core/form/errors.tpl' model=$model}
            </div>
        </div>
        <div class="row">
            <div class="columns large-12">
                <form action="{$model->getAdminUrl()}" method="POST">
                    {set $class_name = $model->classNameShort()}

                    <ul class="ul-main">
                        <li>
                            {include 'core/form/model_form_field.tpl' model=$model field='name' class='big'}
                        </li>

                    </ul>

                    {include 'core/form/buttons.tpl'}
                </form>
            </div>
        </div>
    {/smarty_admin_block}

{/block}