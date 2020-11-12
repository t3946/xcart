{extends 'base.tpl'}

{block "content"}
    <div class="rma-page default-content-page">

        <div class="row">
            <div class="column small-12">
                <h1 class="text-center">{t 'Product return/replacement request #'} AR-202239_R-1</h1>
            </div>
            <div class="column small-12 text-center">
                {t 'Please let us know which products and how many of them you would like to return for a refund or replacement'}
            </div>
        </div>

        <div class="rma-form default-form">
            <div class="row">
                <div class="column small-12">
                    {raw $form->renderBegin()}
                    {raw $form->render()}
                    <div class="row">
                        <div class="column button-row">
                            <button class="button submit-button" type="submit" value="Submit">{t 'Submit'}</button>
                        </div>
                    </div>
                    {raw $form->renderEnd()}
                </div>
            </div>
        </div>
    </div>
{/block}