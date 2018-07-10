{foreach $fields as $name}

    {set $field = $form->getField($name)}
    {if !$field->extends}
        <div class="form-field {$name}">
            {raw $field->render()}
        </div>
    {/if}
{/foreach}