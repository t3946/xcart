<tr {if $field->isHidden()}style="display: none;"{/if}>
    <td colspan="2" class="FormButton">
        <div>
            <b>{raw $label}</b>
            {if $field->required}<span class="Star">*</span>{/if}
            {if $field->hasHint()}{raw $hint}{/if}
        </div>
        <div>
            {raw $input}
            {raw $errors}
        </div>
    </td>
    <td></td>
    {raw $ext}
</tr>