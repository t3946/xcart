<div class="send-question">
    <form action="">
        <div class="row">
            <div class="column small-12">
                {include 'checkout/_form_row.tpl' field=$form->getField('name')}
                {include 'checkout/_form_row.tpl' field=$form->getField('email')}
                {include 'checkout/_form_row.tpl' field=$form->getField('phone')}
                {include 'checkout/_form_row.tpl' field=$form->getField('question')}
            </div>
        </div>
        <div class="row align-center">
            <div class="column small-12">
                <div class="buttons text-center">
                    <button type="submit" class="button submit yellow waves waves-orange waves-effect">
                        {t 'Submit question' dict='order'}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

