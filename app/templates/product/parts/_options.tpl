{if $form}
<div class="product-form">
    {raw $form->renderBegin([
    'action' => '',
    'method' => 'POST',
    'class' => 'product-page-form'
    ])}
    {raw $form->render()}

    {*<div class="radio-container">*}
        {*<input id="test1" value="sdfsdf"*}
               {*name="test12" required="" class="required" type="radio">*}
        {*<label for="test1">*}
            {*<span>121212</span>*}
        {*</label>*}
    {*</div>*}
    {*<div class="radio-container">*}
        {*<input id="test2" value="sdfsdf"*}
               {*name="test12" required="" class="required" type="radio">*}
        {*<label for="test2">*}
            {*<span>121212</span>*}
        {*</label>*}
    {*</div>*}

    {raw $form->renderEnd()}
</div>

{/if}
