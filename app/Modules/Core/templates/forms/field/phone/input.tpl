<div style="white-space: nowrap">
<input type="{$type}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>
    {if $value}
    <a target="_blank" style="color: #140BFC" href="tel:{$value}">{$extended ?: 'Call'}</a>
    {/if}
</div>