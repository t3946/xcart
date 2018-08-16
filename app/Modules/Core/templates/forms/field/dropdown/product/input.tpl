{set $selectedList = $field->selected ? $field->selected : $field->getSelected()}
{set $placeholder = ''}

<span class='select-holder display-none'>
        <select name="{$name}" id="{$id}" {raw $html}>
            {if $field->empty}
                <option value="{$field->empty}">{$field->empty}</option>
            {/if}

            {foreach $field->getChoices() as $key => $name}
                {set $isSelected = ($key in list $selectedList)}
                {if $isSelected}
                    {set $placeholder = $name}
                {/if}
                <option value="{$key}"
                        {if $isSelected}selected="selected"{/if} {if $key in list $field->disabled}disabled{/if} {if $key == ''}hidden{/if}>
                            {$name}
                    </option>
            {/foreach}
        </select>
</span>
<a class="select-visible-button button str-down grey-border hover-blue" data-select="{$id}">
        <span>
                {$placeholder}
        </span>
</a>