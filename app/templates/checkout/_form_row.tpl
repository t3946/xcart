<div class="row">
    <div class="small-6 columns medium-text-right text-block">
        {if $field->hint}
            <div class="multiline">
                {$field->renderLabel()}
                <span class="hint">
                    {$field->renderHint()}
                </span>
            </div>
        {else}
            {$field->renderLabel()}
        {/if}
    </div>
    <div class="small-12 medium-6 columns">
        {$field->renderInput()}
    </div>
</div>