<div class="row form-row compound-field">
    <div class="column hide-for-large small-12 large-2 large-order-2">
        {raw $errors}
    </div>
    <div class="column small-12 large-order-1">
        <div class="row">
            <div class="small-12 large-6 columns large-text-right text-block">
                <div class="medium-multiline">
                    {raw $label}
                    {raw $hint}
                </div>
            </div>
            <div class="small-12 large-6 columns phone--container input-fields">
                {raw $input}

                <span class="phone_ext--container">
                    <label class="display-inline hide-for-medium">X</label>
                    <label class="display-inline show-for-medium">{t 'ext' dict='order'}</label>
                    {raw $ext->renderInput()}
                </span>
                <span class="show-for-large">
                    <span class="show-success"></span>
                    <span class="show-error"></span>
                    {raw $errors}
                    {*<span class="errors-content">{raw $errors}</span>*}
                </span>
            </div>
        </div>
    </div>
</div>
{*<div class="row form-row compound-field">*}
    {*<div class="column hide-for-large small-12 large-2 large-order-2">*}
        {*{$form->getField('phone')->renderErrors()}*}
    {*</div>*}
    {*<div class="column small-12 large-order-1">*}
        {*<div class="row">*}
            {*<div class="small-12 large-6 columns large-text-right text-block">*}
                {*{if $form->getField('phone')->hint}*}
                    {*<div class="multiline">*}
                        {*{$form->getField('phone')->renderLabel()}*}
                        {*<span class="hint">*}
                                                {*{$form->getField('phone')->renderHint()}*}
                                            {*</span>*}
                    {*</div>*}
                {*{else}*}
                    {*{$form->getField('phone')->renderLabel()}*}
                {*{/if}*}
            {*</div>*}
            {*<div class="small-12 large-6 columns phone--container">*}
                {*{$form->getField('phone')->renderInput()}*}
                {*<span class="phone_ext--container">*}
                                          {*<label class="display-inline hide-for-medium">X</label>*}
                                          {*<label class="display-inline show-for-medium">{t 'ext' dict='order'}</label>*}
                    {*{$form->getField('phone_ext')->renderInput()}*}
                                    {*</span>*}
                {*<span class="show-for-large">*}
                                          {*{$form->getField('phone')->renderErrors()}*}
                                    {*</span>*}
            {*</div>*}
        {*</div>*}
    {*</div>*}
{*</div>*}