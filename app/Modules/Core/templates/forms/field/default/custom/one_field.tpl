{set $hasErrors = $field->getErrors() ? 'invalid' : ''}
{set $success = $field->filledOutSuccessfully() ? 'success' : ''}

{if $field->extend}

    {set $fieldExt = $form->getField($field->extend)}
    {set $hasErrorsExt = ($field->getErrors() || $fieldExt->getErrors()) ? 'invalid' : ''}
    {set $successExt = ($field->filledOutSuccessfully() && !$fieldExt->hasErrors()) ? 'success' : ''}

    <div class="form-field {$name} {$hasErrorsExt} {$successExt} compound-field">

        {insert 'field_compound.tpl'}

    </div>

{elseif !$field->extends}

    <div class="form-field {$name} {$hasErrors} {$success}">

        {insert 'field.tpl'}

    </div>

{/if}