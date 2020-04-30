{var $fieldsets = $form->getFormFieldsets()}
{if $fieldsets}
    {foreach $fieldsets as $name => $fieldsNames}
        <fieldset>
            <div class="fieldset-title">
                {$name}
            </div>
            <div class="fields">
                {foreach $fieldsNames as $fieldName}
                    {var $field = $form->getField($fieldName)}
                    <div class="form-field {$fieldName}">
                        {raw $field->render()}
                    </div>
                {/foreach}
            </div>
        </fieldset>
    {/foreach}
{else}
    <table class="dx_form" cellpadding="3" cellspacing="1" width="100%">
        {foreach $fields as $field}
            {set $f = $form->getField($field)}
            {$f->render()}
        {/foreach}
    </table>
{/if}
