<style type="text/css">
    a.admin_link {
        color: blue;
    }
    a.admin_link:hover {
        text-decoration: none !important;
        color: red;
    }
    .x_form input, .x_form textarea {
        width: 100%;
    }
    .admin .required {
        color: inherit;
    }
</style>
<table class="x_form" cellpadding="3" cellspacing="1" width="100%">
    {var $fieldsets = $form->getFieldsets()}
    {if $fieldsets}
        {foreach $fieldsets as $name => $fieldsNames last=$last}
            {if !is_integer($name)}
                <tr>
                    <td colspan="3"><br>
                        <table class="SubHeader" cellspacing="0">
                            <tbody>
                            <tr>
                                <td class="Green2">
                                    {$name}
                                </td>
                            </tr>
                            <tr>
                                <td class="SubHeaderLine">
                                    <img src="/skin1_kolin/images/spacer.gif" class="Spc"><br/></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                {if $fieldsNames['hint']}
                    <tr>
                        <td colspan="3">
                            {$fieldsNames['hint'][0]}
                        </td>
                    </tr>
                {/if}
            {/if}
            {foreach $fieldsNames as $fieldName}
                {if !is_array($fieldName)}
                    {var $field = $form->getField($fieldName)}
                    {raw $field->render()}
                {/if}
            {/foreach}
            {if is_integer($name) && !$last}
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
            {/if}
        {/foreach}
    {else}
        {var $fields = $form->getFieldsInit()}
        {foreach $fields as $field}
            {raw $field->render()}
        {/foreach}
    {/if}
</table>

