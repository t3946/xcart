<div class="fields-wrapper">
    {foreach $fields as $name}

        {set $field = $form->getField($name)}
        {raw $field->render()}

    {/foreach}
</div>
