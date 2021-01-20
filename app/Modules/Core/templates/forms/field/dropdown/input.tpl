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
    $('#{$id}').change(function () {
        let form = $(this).closest('form')
        let dummy_input = form.find("input[name='{$name}']")
        if (($(this).val() || []).length === 0) {
            if (dummy_input.length === 0) {
                form.append("<input type='hidden' name='{$name}' value=''>")
            }
        } else {
            dummy_input.remove()
        }
    });
</script>