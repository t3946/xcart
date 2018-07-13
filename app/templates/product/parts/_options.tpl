{set $options = $model->options->filter(['avail' => 'Y'])->order(['orderby'])}
{if $options}
    {foreach $options as $option}
        {$option->classtext}
        <select class="product-options" data-id="{$option->classtext}">
            {foreach $option->values as $value}
                <option value="{$value->option_name}">{$value->option_name}</option>
            {/foreach}
        </select>
    {/foreach}
{/if}