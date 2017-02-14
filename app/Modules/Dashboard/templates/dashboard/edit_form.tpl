{extends 'dashboard/dashboard_form.tpl'}

{block 'heading'}
    <h1 align="center">Filter {if $model->getIsNewRecord()}create{else}"{$model}" edit{/if}.</h1>
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

                    <fieldset class="expanded-force" rel="0">
                        <legend>Filter options</legend>

                        <ul class="ul-main">
                            <li>
                                {include 'core/form/model_form_field.tpl' model=$model field='name' class='big'}
                            </li>
                            <li>
                                {include 'core/form/model_form_field.tpl' model=$model field='position_row'}
                            </li>
                            <li>
                                {include 'core/form/model_form_field.tpl' model=$model field='position_column'}
                            </li>
                            <li>
                                {include 'core/form/model_form_field.tpl' model=$model field='enabled' type='checkbox'}
                            </li>
                            <li>
                                {include 'core/form/model_form_field.tpl' model=$model field='bold' type='checkbox'}
                            </li>
                            <li>
                                {include 'core/form/model_form_field.tpl' model=$model field='tag'}
                            </li>
                            <li>
                                {include 'core/form/model_form_field.tpl' model=$model field='color' type='color'}
                            </li>
                        </ul>
                    </fieldset>

                    {include 'dashboard/form_fields.tpl' full_expanded = true}
                    {include 'core/form/buttons.tpl'}
                </form>
            </div>
        </div>
    {/smarty_admin_block}

{/block}