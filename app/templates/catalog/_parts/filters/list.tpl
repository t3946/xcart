<ul class="no-bullet">
    {if $values|count > 0}
        {foreach $values as $val}
            <li>
                <input type="checkbox" name="fform[{$key}][]" id="lcb_{$val.value}"  value="{$val.value}" class="" />
                <label for="lcb_{$val.value}">
                    {$val.name} <span class="count">({$val.count})</span>
                </label>
            </li>
        {/foreach}
    {/if}
</ul>