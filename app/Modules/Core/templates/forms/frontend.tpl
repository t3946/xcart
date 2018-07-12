
{foreach $fields as $name}

    {set $field = $form->getField($name)}
    {if $field->extend}
        {set $fieldExt = $form->getField($field->extend)}
        <div class="form-field {$name}">
            {raw $field->render($fieldExt)}
        </div>
    {elseif !$field->extends}
        <div class="form-field {$name}">
            {raw $field->render()}
        </div>
    {/if}
{/foreach}