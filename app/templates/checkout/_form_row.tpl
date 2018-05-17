<div class="row">
    <div class="column show-for-large small-12 large-2"></div>
    <div class="column small-12 large-2 large-order-2">
        {$field->renderErrors()}
    </div>
    <div class="column small-12 large-8 large-order-1">

        <div class="row">
            <div class="small-12 medium-6 columns large-text-right text-block">
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
            <div class="small-12 large-6 columns">
                {$field->renderInput()}
            </div>
        </div>

    </div>
</div>