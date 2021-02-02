<span class='select-holder'>
        <select name="{$name}" id="{$id}" {raw $html}>
            {if $field->empty}
                <option {if !$field->getSelected()}selected="selected"{/if} value="{$field->empty}">{$field->empty}</option>
            {/if}
            {foreach $field->getChoices() as $key => $field_name}
                <option value="{$key}"
                        {if $key in list $field->getSelected()}selected="selected"{/if} {if $key in list $field->disabled}disabled{/if}>{$field_name}</option>
            {/foreach}
        </select>
</span>
{if $extended}
    {$field->getForm()->getField($extended)->renderInput()}
{/if}
<script>
    {if $field->depends}
    function reloadForm(form , form_class, field) {
        let data = form.serialize();
        data += (data.length ? "&" : "") + "form_class="+form_class+"&depend_field="+field;
        $.ajax({
            type: "POST",
            url: "{url route='admin:field_reload'}",
            data: data,
            success: function(data) {
                for(const index in data) {
                    if (data.hasOwnProperty(index)) {
                        const attr = data[index];
                        $('[id$=_'+index+']', form).closest('tr').replaceWith(attr);
                    }
                }
            }
        });
    }
    {/if}

    $(document).on('change', '#{$id}', function () {
        const form = $(this).closest('form')
        const dummy_input = form.find("input[name='{$name}']")
        if (($(this).val() || []).length === 0) {
            if (dummy_input.length === 0) {
                form.append("<input type='hidden' name='{$name}' value=''>")
            }
        } else {
            dummy_input.remove()
        }
        {if $field->depends}
            reloadForm(form, {$field->getForm()|get_class|json_encode}, '{$field->depends|implode:','}');
        {/if}
    });

</script>