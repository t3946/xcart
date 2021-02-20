{set $allErrors = array_merge($field->getErrors(), $fieldExt->getErrors())}
{set $hasHint = $field->hasHint()}
<div class="hide-for-large mobile-errors-content">
    {raw $field->renderErrors($allErrors)}
</div>
<div class="field-row">
    <div class="field-title">
        {if $hasHint}
            <div class="medium-multiline">
                {raw $label}
                {raw $hint}
            </div>
        {else}
            {raw $label}
        {/if}
    </div>
    <div class="field compound-input">
        <div class="input-block {$field->containerClass}">
            <div class="input-container {$hasClose}" {if $field->userClear}data-clear="true"{/if}>
                {raw $input}
            </div>
            <div class="compound-field-container {$fieldExt->containerClass}">
                <label class="display-inline hide-for-medium">X</label>
                <label class="display-inline show-for-medium">{t 'ext' }</label>
                <div class="input-container {$field->className} {$hasCloseExt}" {if $field->userClear == 'input_text'}data-clear="true"{/if}>
                    {raw $fieldExt->renderInput()}
                </div>
            </div>
        </div>
        <div class="input-info">
            <span class="show-success"></span>
            <span class="show-error"></span>
        </div>
        <div class="show-for-large large-errors-content">
            {raw $field->renderErrors($allErrors)}
        </div>
    </div>
</div>
