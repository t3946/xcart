{if $extends}{$extends}{/if} <input type="{$type}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>
{if $extended}
    {$field->getForm()->getField($extended)->renderInput()}
{/if}