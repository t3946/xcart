
{foreach $fields as $name}

    {set $field = $form->getField($name)}
    {set $errors = $field->renderErrors()}
    {set $hasErrors = $errors ? 'invalid' : ''}
    11{var_dump(trim($errors))}
    {if $field->extend}
        {set $fieldExt = $form->getField($field->extend)}
        <div class="form-field {$name} {$hasErrors} compound-field">
            {raw $field->render($fieldExt)}
        </div>
    {elseif !$field->extends}
        <div class="form-field {$name} {$hasErrors}">
            {raw $field->render()}
        </div>
    {/if}
{/foreach}