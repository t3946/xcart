
<div class="send-question default-form">
    {if $message && $message == 'success'}
    <form action="" data-message-text="message" data-message-type="success">
        <div class="message" style="display:none;">
            <div class="in-center">Thank you for submitting your product question! We appreciate your interest in this product.
            We'll do our best to get back to you within 24 hours.
                S3 Stores, Inc. customer care team</div>
        </div>
    {else}
        {raw $form->renderBegin()}
    {/if}
            <input type="hidden" value="{$productId}" name="{$form->classNameShort()}[productid]">
            <div class="row">
                <div class="column no-padding small-12">
                    {raw $form->render()}
                </div>
            </div>
            <div class="row align-center submit-button-container">
                <div class="column no-padding small-12">
                    <div class="buttons text-center">
                        <button type="submit" class="button yellow-white waves waves-orange waves-effect">
                            {t 'Submit question' dict='order'}
                        </button>
                    </div>
                </div>
            </div>
        {raw $form->renderEnd()}
</div>
{if $productQuestion}
    {include 'product/tabs/questions/_questions_list.tpl' productQuestion = $productQuestion}
{/if}
