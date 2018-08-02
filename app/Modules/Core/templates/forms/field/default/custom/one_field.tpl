{set $hasErrors = $field->getErrors() ? ' invalid' : ''}
{set $success = $field->filledOutSuccessfully() ? ' success' : ''}
{set $hasClose = $field->filledOutSuccessfully() ? ' hasClose' : ''}
{set $displayClass = ($field->type == 'hidden') ? ' display-none' : ''}

{if $field->extend}

    {set $fieldExt = $field->getForm()->getField($field->extend)}
    {set $hasErrorsExt = ($field->getErrors() || $fieldExt->getErrors()) ? ' invalid' : ''}
    {set $successExt = ($field->filledOutSuccessfully() && !$fieldExt->hasErrors()) ? ' success' : ''}
    {set $hasCloseExt = ($field->filledOutSuccessfully() && !$fieldExt->hasErrors()) ? ' hasClose' : ''}

    <div class="form-field {$name}{$hasErrorsExt}{$successExt}{$displayClass} compound-field">

        {insert 'forms/field/default/custom/field_compound.tpl'}

    </div>

{elseif !$field->extends}

    <div class="form-field {$name}{$hasErrors}{$success}{$displayClass}">

        {insert 'forms/field/default/custom/field.tpl'}

    </div>

{/if}