<div class="form-model-field-block">
    <div class="row">
        <div class="columns large-4">
            <label for="m_{$field}">{$model->getField($field)->getVerboseName()}:</label>
        </div>

        <div class="columns large-6">
            {if $type == 'textarea'}
                <textarea name="{$model->classNameShort()}[{$field}]" id="m_{$field}" {if $model->getField($field)->isRequired()}required{/if}>{$model.$field}</textarea>
            {elseif $type == 'checkbox'}
                <input type="hidden" value="0" name="{$model->classNameShort()}[{$field}]">
                <input type="{if $type}{$type}{else}text{/if}" name="{$model->classNameShort()}[{$field}]" id="m_{$field}" value="1" class="{$class}" {if $model->getField($field)->isRequired()}required{/if} {if $model.$field || ($model->getIsNewRecord() && $model->getField($field)->getValue())}checked{/if}>
            {else}
                <input type="{if $type}{$type}{else}text{/if}" name="{$model->classNameShort()}[{$field}]" id="m_{$field}" value="{$model.$field}" class="{$class}" {if $model->getField($field)->isRequired()}required{/if}>
            {/if}
        </div>

    </div>
</div>