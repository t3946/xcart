<div class="row">
    <div class="small-6 columns medium-text-right text-block">
        <div class="multiline">
            {$form->getField($field)->renderLabel()}
            <span class="hint">
                {$form->getField($field)->renderHint()}
            </span>
        </div>
    </div>
    <div class="small-12 medium-6 columns">
        {$form->getField($field)->renderInput()}
    </div>
</div>