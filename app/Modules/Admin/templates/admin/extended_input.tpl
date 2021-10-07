{if $extends}{$extends}{/if}
{if $field->extendedInputTemplate}
    {$field->renderExtendInput()}
{else}
    <input type="{$type}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>
{/if}
{if $field->extendedBeforeText}
    <span>{$field->extendedBeforeText}</span>
{/if}
{if $extended}
    {$field->getForm()->getField($extended)->renderInput()}
{/if}