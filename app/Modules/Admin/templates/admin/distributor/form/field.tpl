<tr {if $field->isHidden()}style="display: none;"{/if}>
    <td colspan="3">
        <div class="row">
            <div class="col-3">
                <b>{raw $label}</b>
                {if $field->required}<span class="Star">*</span>{/if}
                {if $field->hasHint()}{raw $hint}{/if}
            </div>
            <div class="col-8">
                {raw $input}
                {raw $errors}
                {raw $ext}
            </div>
        </div>
    </td>
</tr>
