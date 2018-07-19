{set $allErrors = array_merge($field->getErrors(), $ext->getErrors())}
<div class="hide-for-large mobile-errors-content">
    {raw $field->renderErrors($allErrors)}
</div>
<div class="field-row">
    <div class="field-title">
        {raw $label}
        {raw $hint}
    </div>
    <div class="field compound-input">
            <div class="input-block">
                <div class="input-container {$field->className}" {if $field->userClear}data-clear="true"{/if}>
                    {raw $input}
                </div>
                <div class="compound-field-container">
                    <label class="display-inline hide-for-medium">X</label>
                    <label class="display-inline show-for-medium">{t 'ext' dict='order'}</label>
                    <div class="input-container {$field->className}" {if $field->userClear == 'input_text'}data-clear="true"{/if}>
                        {raw $ext->renderInput()}
                    </div>
                </div>
            </div>
            <div class="show-for-mediun input-info">
                <span class="show-success"></span>
                <span class="show-error"></span>
            </div>
            <div class="show-for-large large-errors-content">
                {raw $field->renderErrors($allErrors)}
            </div>
    </div>
</div>
