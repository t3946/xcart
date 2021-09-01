{if $model}
{set $class_name = $model->classNameShort()}
<li>
    {include 'core/form/model_form_field.tpl' model=$model field='name' class='big'}
</li>
{/if}

