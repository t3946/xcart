{set $options = $model->options->filter(['avail' => 'Y'])->order(['orderby'])}
{if $options}
    {foreach $options as $option}
        {$option->classtext}
        <select class="product-options" data-id="{$option->classid}">
            {foreach $option->values as $value}
                <option value="{$value->optionid}">{$value->option_name}</option>
            {/foreach}
        </select>
    {/foreach}
{/if}