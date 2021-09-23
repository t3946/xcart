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
