<tr {if $field->isHidden()}style="display: none;"{/if}>
    <td width="30%" class="FormButton">
        <div class="form-field">
            <div class="field-no-label-input">{raw $input}</divstyle>
            {if $field->hasHint()}{raw $hint}{/if}
            {if $field->required}<span class="Star">*</span>{/if}
        </div>
    </td>
    <td width="60%">
        {raw $errors}
    </td>
</tr>