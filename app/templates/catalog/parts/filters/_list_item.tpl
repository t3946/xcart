<input type="checkbox"
       name="filter[{$key}][]"
       id="{$prefix}_lcb_{$val.value}"
       value="{$val.value}"
       data-group="fv-group-{$val.value}"
       data-remove="fv-remove-{$val.value}"
       data-fv-val="{$val.value}"

        {if $val.checked}
            class="fv-group-{$val.value} checked" checked
        {else}
            class="fv-group-{$val.value}"
        {/if}
/>
<label for="{$prefix}_lcb_{$val.value}">
    {$val.name} <span class="count">({$val.count})</span>
</label>