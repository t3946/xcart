<ul id="{$id}_errors" class="errors {$field->errorClass} {if $errors}common-field-error_visible{/if}"  {raw $html}>
    <li class="{$field->errorTextClass}">{$errors[0]}</li>
</ul>