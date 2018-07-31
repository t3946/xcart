{set $hasErrors = $field->getErrors() ? 'invalid' : ''}
{set $success = $field->filledOutSuccessfully() ? 'success' : ''}
{set $hasClose = $field->filledOutSuccessfully() ? 'hasClose' : ''}

{if $field->extend}

    {set $fieldExt = $field->getForm()->getField($field->extend)}
    {set $hasErrorsExt = ($field->getErrors() || $fieldExt->getErrors()) ? 'invalid' : ''}
    {set $successExt = ($field->filledOutSuccessfully() && !$fieldExt->hasErrors()) ? 'success' : ''}
    {set $hasCloseExt = ($field->filledOutSuccessfully() && !$fieldExt->hasErrors()) ? 'hasClose' : ''}

    <div class="form-field {$name} {$hasErrorsExt} {$successExt} compound-field">

        {insert 'forms/field/default/custom/field_compound.tpl'}

    </div>

{elseif !$field->extends}

    <div class="form-field {$name} {$hasErrors} {$success}">

        {insert 'forms/field/default/custom/field.tpl'}

    </div>

{/if}