{set $hasErrors = $field->getErrors() ? ' invalid' : ''}
{set $success = $field->filledOutSuccessfully() ? ' success' : ''}
{set $hasClose = $field->filledOutSuccessfully() ? ' hasClose' : ''}
{set $displayClass = ($field->type == 'hidden') ? ' display-none' : ''}

<div class="form-field {$name}{$hasErrors}{$success}{$displayClass}">
    {set $hasHint = $field->hasHint()}
    <div class="common-field-error-wrapper">
        {raw $errors}
    </div>
    <div class="checkout-field__row checkout-field-row">
        <div class="checkout-field-title field__title">
            {if $hasHint}
                <div class="medium-multiline">
                    {raw $label}
                    {raw $hint}
                </div>
            {else}
                {raw $label}
            {/if}
        </div>
        <div class="field switcher-field {$field->fieldClass}">
            <div class="input-container switcher-input-container {$field->className}" {if $field->userClear == 'input_text'}data-clear="true"{/if}>
                {raw $input}
                <span class="switcher-button field__switcher-button {$field->switcherClass}">
                    <svg class="icon switcher-button-icon switcher-button-icon-plus"><use xlink:href="/static/frontend/images/icons/sprite.svg#switcher-plus__ash"></use></svg>
                    <svg class="icon switcher-button-icon switcher-button-icon-minus"><use xlink:href="/static/frontend/images/icons/sprite.svg#switcher-minus__ash"></use></svg>
                </span>
            </div>
            <div class="show-for-medium input-info">
                <span class="show-success"></span>
                <span class="show-error"></span>
            </div>
        </div>
    </div>
</div>