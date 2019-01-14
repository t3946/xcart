{extends 'dashboard/layouts/dashboard_layout.tpl'}


{block 'heading'}
    <h1 align="center">{if $model->getIsNewRecord()}Create filter{else}Edit "{$model}" filter{/if}.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name = 'Form'}
        <div class="row">
            <div class="columns large-12">
                {include 'core/form/errors.tpl' model=$model}
            </div>
        </div>

        <form action="{$model->getAdminUrl()}" method="POST">
            {set $class_name = $model->classNameShort()}

            <fieldset class="expanded-force" rel="0">
                <legend>Filter options</legend>

                <ul class="ul-main">
                    <li>
                        {include 'core/form/model_form_field.tpl' model=$model field='name' class='big'}
                    </li>
                    <li>
                        {include 'core/form/model_form_field.tpl' model=$model field='position_row' type='number'}
                    </li>
                    <li>
                        {include 'core/form/model_form_field.tpl' model=$model field='position_column' type='number'}
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
                    <li>
                        {include 'core/form/model_form_field.tpl' model=$model field='direct_url' class='big'}
                    </li>
                    <li>
                        {include 'core/form/model_form_field.tpl' model=$model field='sorting' type='select' class='big'}
                    </li>
                    <li>
                        {include 'core/form/model_form_field.tpl' model=$model field='group_id' type='select' selected=$model->group_id choises=$groups class='big'}
                    </li>
                </ul>
            </fieldset>

            {include 'dashboard/_filter_fields.tpl' full_expanded = true}
            {include 'core/form/buttons.tpl'}
        </form>
    {/smarty_admin_block}

{/block}