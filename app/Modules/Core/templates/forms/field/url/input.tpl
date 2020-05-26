<div style="white-space: nowrap">
<input type="{$type}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>
    {if $value}
    <a target="_blank" href="{$value}">{$extended ?: 'link'}</a>
    {/if}
</div>