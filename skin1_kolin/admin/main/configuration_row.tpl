<tr>
    {cycle values="class='TableSubHead', " assign=subcycle}
    <td width="3%">&nbsp;</td>
    <td {$subcycle} width="37%">
        {$model->comment}:
    </td>
    <td {$subcycle} width="60%">
        {if $model->type == 'text'}
            <input type="text" style="width: 370px;" name="{$model->name}[]" value="{$model->value|escape:html}" />
        {/if}
        {if $glob->type == 'textarea'}
            <textarea class="new_editor" rows="30" cols="60" name="{$model->name}[]">
                        {$model->value|escape:html}
            </textarea>
        {/if}
    </td>
</tr>