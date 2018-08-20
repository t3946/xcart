{set $hasErrors = $field->getErrors() ? 'invalid' : ''}
{set $success = $field->filledOutSuccessfully() ? 'success' : ''}

<div class="form-field {$name} {$hasErrors} {$success}">
    <div class="field-title">
        {raw $label}
    </div>

    <div class="field">
        <div class="input-container {$field->className}">
            {raw $input}
        </div>

        <div class="errors-content">
            {raw $errors}
        </div>
    </div>
</div>