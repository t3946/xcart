{set $allErrors = array_merge($field->getErrors(), $fieldExt->getErrors())}
{set $hasHint = $field->hasHint()}
<div class="checkout-field__row">
    <div class="common-field-error-wrapper">
        {raw $field->renderErrors($allErrors)}
    </div>
    <div class="checkout-field-row">
        <div class="checkout-field-title">
            {if $hasHint}
                <div class="medium-multiline">
                    {raw $label}
                    {raw $hint}
                </div>
            {else}
                {raw $label}
            {/if}
        </div>
        <div class="field compound-input {$field->fieldClass}">
        <div class="input-block {$field->containerClass}">
            <div class="input-container {$field->className} {$hasClose}" {if $field->userClear}data-clear="true"{/if}>
                {raw $input}
            </div>
            <div class="compound-field-container {$fieldExt->containerClass}">
                <label class="display-inline hide-for-medium {$fieldExt->shortHintClass}">X</label>
                <label class="display-inline show-for-medium common-hint {$fieldExt->longHintClass}">{t 'ext' }</label>
                <div class="input-container {$fieldExt->className} {$hasCloseExt}" {if $field->userClear == 'input_text'}data-clear="true"{/if}>
                    {raw $fieldExt->renderInput()}
                </div>
            </div>
        </div>
        <div class="show-for-mediun input-info">
            <span class="show-success"></span>
            <span class="show-error"></span>
        </div>
    </div>
    </div>
</div>
