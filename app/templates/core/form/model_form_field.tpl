<div class="form-model-field-block">
    <div class="row">
        <div class="columns large-4">
            <label for="m_{$field}">{$model->getField($field)->getVerboseName()}:</label>
        </div>

        <div class="columns large-6">
            {if $type == 'textarea'}
                <textarea name="{$model->classNameShort()}[{$field}]" id="m_{$field}" {if $model->getField($field)->isRequired()}required{/if}>{$model.$field}</textarea>
            {elseif $type == 'select'}
                <select name="{$model->classNameShort()}[{$field}]" id="m_{$field}" {if $multiple}multiple{/if} class="{$class}">
                    <option value=""></option>
                    {foreach $choises as $key => $value}
                        {if is_array($selected)}
                            <option value="{$value.id}" {if $value.id|in:$selected }selected{/if}>{$value.name}</option>
                        {else}
                            <option value="{$value.id}" {if $selected == $value.id }selected{/if}>{$value.name}</option>
                        {/if}
                    {/foreach}
                </select>
            {elseif $type == 'checkbox'}
                <input type="hidden" value="0" name="{$model->classNameShort()}[{$field}]">
                <input type="{if $type}{$type}{else}text{/if}"
                       name="{$model->classNameShort()}[{$field}]"
                       id="m_{$field}"
                       value="1"
                       class="{$class}"
                       {if $model->getField($field)->isRequired()}required{/if}
                       {if $model.$field || ($model->getIsNewRecord() && $model->getField($field)->getValue())}checked{/if}
                >
            {else}
                <input type="{if $type}{$type}{else}text{/if}"
                       name="{$model->classNameShort()}[{$field}]"
                       id="m_{$field}" value="{$model.$field}"
                       class="{$class}"
                       {if $model->getField($field)->isRequired()}required{/if}
                >
            {/if}
        </div>

    </div>
</div>