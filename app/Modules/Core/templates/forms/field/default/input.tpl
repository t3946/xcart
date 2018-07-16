{if !$extended}
    <input type="{$type}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>
{else}
    <input data-extended="true" type="{$type}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>
{/if}