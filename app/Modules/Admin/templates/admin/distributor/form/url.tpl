{if $extends}{$extends}{/if} <input type="{$type}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>
{if $value}
    <a target="_blank" href="{$value}">link</a>
{/if}
