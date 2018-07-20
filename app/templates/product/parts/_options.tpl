{set $options = $model->options->filter(['active' => true])->order(['position'])}
{if $options}
    {foreach $options as $option}
        <div class="option">
            {$option->option}
            <select class="product-options" data-id="{$option->option}">
                {foreach $option->variants as $variant}
                    <option value="{$variant->variant}">{$variant->variant}</option>
                {/foreach}
            </select>
            </div>
    {/foreach}
{/if}