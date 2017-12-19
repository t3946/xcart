{if $fields}
    <ul class="ul-main">
        {foreach $fields as $name}
            {set $field = $form->getField($name)}
            <li class="form-field {$name}">
                {raw $field->render()}
            </li>
        {/foreach}
    </ul>
{/if}