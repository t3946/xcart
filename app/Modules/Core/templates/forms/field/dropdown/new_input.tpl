<span class='select-holder'>
    {if !$field->empty}
        <input type="hidden" name="{$name}"/>
    {/if}
    {set $chioces = $field->getChoices()}
        <select data-form='{$field->getForm()|get_class}' name="{$name}" id="{$id}_{$field->getForm()->getInstance()->pk}" {raw $html}>
            {if $field->empty}
                <option {if !$field->getSelected()}selected="selected"{/if} value="{$field->empty}">{$field->empty}</option>
            {/if}
            {foreach $chioces as $key => $field_name}
                <option value="{$key}"
                        {if $key in list $field->getSelected()}selected="selected"{/if} {if $key in list $field->disabled}disabled{/if}>{$field_name}</option>
            {/foreach}
        </select>
</span>
{if $extended}
    {$field->getForm()->getField($extended)->renderInput()}
{/if}
<script>
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
        reloadForm(form, {$field->getForm()|get_class|json_encode}, '{implode(',', $field->depends)}');
        {/if}
    });

</script>