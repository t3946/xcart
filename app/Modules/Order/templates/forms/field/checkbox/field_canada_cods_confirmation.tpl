<tr {if $field->isHidden()}style="display: none;"{/if}>
    <td width="30%" class="FormButton">
        <label class="form-field canada-cods-field-group {$field->containerClass}">
            {raw $input}
            {if $field->hasHint()}{raw $hint}{/if}
            {if $field->required}<span class="Star">*</span>{/if}
        </label>
    </td>
    <td width="60%">
        {raw $errors}
    </td>
</tr>