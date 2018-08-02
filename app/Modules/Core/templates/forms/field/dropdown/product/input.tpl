{set $selectedList = $field->selected ? $field->selected : $field->getSelected()}
<span class='select-holder'>
        <select name="{$name}" id="{$id}" {raw $html}>
            {if $field->empty}
                <option value="{$field->empty}">{$field->empty}</option>
            {/if}
            {foreach $field->getChoices() as $key => $name}
                <option value="{$key}" {if $key in list $selectedList}selected="selected"{/if} {if $key in list $field->disabled}disabled{/if} {if $key == ''}hidden{/if}>{$name}</option>
            {/foreach}
        </select>
</span>