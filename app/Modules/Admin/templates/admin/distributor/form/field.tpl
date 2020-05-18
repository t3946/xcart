<tr {if $field->isHidden()}style="display: none;"{/if}>
    <td width="30%" class="FormButton">
        {raw $label}
        {if $field->hasHint()}
            {raw $hint}
        {/if}
    </td>
    <td width="60%">
        {raw $input}
        {raw $errors}
    </td>
    <td></td>
    {raw $ext}
</tr>

