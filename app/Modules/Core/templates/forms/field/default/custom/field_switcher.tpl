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
            <div class="input-container switcher-input-container {$field->className} {$hasClose}" {if $field->userClear == 'input_text'}data-clear="true"{/if}>
                {raw $input}
                <span class="switcher-button field__switcher-button {$field->switcherClass}">
                    <svg class="icon switcher-button-icon switcher-button-icon-plus"><use xlink:href="/static/frontend/images/icons/sprite.svg#switcher-plus"></use></svg>
                    <svg class="icon switcher-button-icon switcher-button-icon-minus"><use xlink:href="/static/frontend/images/icons/sprite.svg#switcher-minus"></use></svg>
                </span>
            </div>
            <div class="show-for-medium input-info">
                <span class="show-success"></span>
                <span class="show-error"></span>
            </div>
        </div>
    </div>
</div>

<style>
    .common-input-container {
        width: 100%;
    }
    @media (min-width: 720px) and (max-width: 1024px) {
        .checkout-field-title {
            width: 50%;
        }

        .switcher-field {
            width: 50%;
            width: 320px;
        }
    }
</style>
