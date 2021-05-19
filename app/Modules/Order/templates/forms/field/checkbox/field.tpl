<tr {if $field->isHidden()}style="display: none;"{/if}>
    <td width="30%" class="FormButton">
        <b>{raw $label}</b>
        {if $field->required}<span class="Star">*</span>{/if}
        {if $field->hasHint()}{raw $hint}{/if}
    </td>
    <td width="60%">
        {raw $input}
        {raw $errors}
    </td>
    <td></td>
    {raw $ext}
</tr>