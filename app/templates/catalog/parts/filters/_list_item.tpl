<input type="checkbox"
       name="fform[{$key}][]"
       id="{$prefix}_lcb_{$val.value}"
       value="{$val.value}"
       data-group="{$val.value}"
        {if $val.checked}
            class="fv-group-{$val.value} checked" checked
        {else}
            class="fv-group-{$val.value}"
        {/if}
/>
<label for="{$prefix}_lcb_{$val.value}">
    {$val.name} <span class="count">({$val.count})</span>
</label>