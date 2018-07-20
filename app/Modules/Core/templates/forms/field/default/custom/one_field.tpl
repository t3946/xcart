{set $hasErrors = $field->getErrors() ? 'invalid' : ''}
{set $success = $field->filledOutSuccessfully() ? 'success' : ''}

{if $field->extend}

    {set $fieldExt = $field->getForm()->getField($field->extend)}
    {set $hasErrorsExt = ($field->getErrors() || $fieldExt->getErrors()) ? 'invalid' : ''}
    {set $successExt = ($field->filledOutSuccessfully() && !$fieldExt->hasErrors()) ? 'success' : ''}

    <div class="form-field {$name} {$hasErrorsExt} {$successExt} compound-field">

        {insert 'forms/field/default/custom/field_compound.tpl'}

    </div>

{elseif !$field->extends}

    <div class="form-field {$name} {$hasErrors} {$success}">

        {insert 'forms/field/default/custom/field.tpl'}

    </div>

{/if}