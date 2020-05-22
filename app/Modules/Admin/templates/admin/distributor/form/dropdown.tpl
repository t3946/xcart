{if $extends}{$extends}{/if}
<span class='select-holder'>
        <select name="{$name}" id="{$id}" {raw $html}>
            {if $field->empty}
                <option value="{$field->empty}">{$field->empty}</option>
            {/if}
            {foreach $field->getChoices() as $key => $name}
                <option value="{$key}" {if $key in list $field->getSelected()}selected="selected"{/if} {if $key in list $field->disabled}disabled{/if}>{$name}</option>
            {/foreach}
        </select>
</span>
{if $extended}
    {$field->getForm()->getField($extended)->renderInput()}
{/if}