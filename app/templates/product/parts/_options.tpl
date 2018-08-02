{*{set $options = $model->options->filter(['active' => true])->order(['position'])}*}
{*{if $options}*}
    {*{foreach $options as $option}*}
        {*<div class="option">*}
            {*{$option->option}*}
            {*<select class="product-options" data-id="{$option->option}">*}
                {*{foreach $option->variants as $variant}*}
                    {*<option value="{$variant->variant}">{$variant->variant}</option>*}
                {*{/foreach}*}
            {*</select>*}
            {*</div>*}
    {*{/foreach}*}
{*{/if}*}
{*{dd($form->getFields())}*}
{*'action' => $.app->router->url('checkout:options'),*}
{if $form}
<div class="product-form">
    {raw $form->renderBegin([
    'action' => '',
    'method' => 'POST',
    'class' => 'product-page-form'
    ])}
    {raw $form->render()}
    {raw $form->renderEnd()}
</div>
{/if}
