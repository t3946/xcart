<div class="send-question default-form">
    {if $message}
    <form action="" data-message-text="{$message['text']}" data-message-type="{$message['type']}">
        {else}
        <form action="">
            {/if}
            <input type="hidden" value="{$productId}" name="{$form->classNameShort()}[productid]">
            <div class="row">
                <div class="column small-12">
                    {include 'checkout/_form_row.tpl' field=$form->getField('name')}
                    {include 'checkout/_form_row.tpl' field=$form->getField('email')}

                    <div class="row form-row compound-field">

                        <div class="column hide-for-large small-12 large-2 large-order-2">
                            {$form->getField('phone')->renderErrors()}
                        </div>

                        <div class="column small-12 large-order-1">
                            <div class="row">
                                <div class="small-12 large-6 columns large-text-right text-block">
                                    {if $form->getField('phone')->hint}
                                        <div class="multiline">
                                            {$form->getField('phone')->renderLabel()}

                                            <span class="hint">
                                                    {$form->getField('phone')->renderHint()}
                                                </span>
                                        </div>
                                    {else}
                                        {$form->getField('phone')->renderLabel()}
                                    {/if}
                                </div>
                                <div class="small-12 large-6 columns phone--container">
                                    {$form->getField('phone')->renderInput()}
                                    <span class="phone_ext--container">
                                            <label class="display-inline hide-for-medium">X</label>
                                            <label class="display-inline show-for-medium">{t 'ext' dict='order'}</label>

                                        {$form->getField('phone_ext')->renderInput()}
                                        </span>

                                    <span class="show-for-large">
                                            {$form->getField('phone')->renderErrors()}
                                        </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    {include 'checkout/_form_row.tpl' field=$form->getField('question')}
                </div>
            </div>
            <div class="row align-center">
                <div class="column small-12">
                    <div class="buttons text-center">
                        <button type="submit" class="button yellow-white waves waves-orange waves-effect">
                            {t 'Submit question' dict='order'}
                        </button>
                    </div>
                </div>
            </div>
        </form>
</div>
{if $productQuestion}
    {include 'product/tabs/questions/_questions_list.tpl' productQuestion = $productQuestion}
{/if}
