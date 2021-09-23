<table class="dx_form" cellpadding="3" cellspacing="1" width="100%">
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
        {foreach $fields as $field}
            {set $f = $form->getField($field)}
            {$f->render()}
        {/foreach}
    {/if}
</table>

