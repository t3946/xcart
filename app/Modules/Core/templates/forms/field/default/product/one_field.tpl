{set $hasErrors = $field->getErrors() ? 'invalid' : ''}
{set $success = $field->filledOutSuccessfully() ? 'success' : ''}

<div class="form-field {$name} {$hasErrors} {$success}">
    {insert 'forms/field/default/product/field.tpl'}
</div>