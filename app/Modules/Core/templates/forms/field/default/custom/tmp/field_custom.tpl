<div class="row form-row">
    <div class="column hide-for-large small-12 large-2 large-order-2">
        {raw $errors}
    </div>
    <div class="column small-12 large-order-1">
        <div class="row">
            <div class="small-12 large-6 columns large-text-right text-block">
                <div class="multiline">
                    {raw $label}
                    {raw $hint}
                </div>
            </div>
            <div class="small-12 large-6 columns input-fields">
                {raw $input}

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