<div class="hide-for-large mobile-errors-content">
    {raw $errors}
</div>
<div class="field-row">
    <div class="field-title">
        {raw $label}
        {raw $hint}
    </div>
    <div class="field">
        <div class="input-container" {if $field->userClear == 'input_text'}data-clear="true"{/if}>
            {raw $input}
        </div>
        <div class="show-for-medium input-info">
            <span class="show-success"></span>
            <span class="show-error"></span>
        </div>
        <div class="show-for-large large-errors-content">
            {raw $errors}
        </div>
    </div>
</div>
