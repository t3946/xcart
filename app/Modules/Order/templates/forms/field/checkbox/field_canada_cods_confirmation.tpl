<tr {if $field->isHidden()}style="display: none;"{/if}>
    <td width="30%" class="FormButton">
        <div class="form-field {$field->containerClass}">
            {raw $errors}
            <label class="canada-cods-field-group">
                {raw $input}
                {if $field->hasHint()}{raw $hint}{/if}
                {if $field->required}<span class="Star">*</span>{/if}
            </label>
        </div>
    </td>
    <td width="60%">
        {raw $errors}
    </td>
</tr>