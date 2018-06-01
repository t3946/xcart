{extends  $.request->getIsAjax() ? "ajax.tpl" : "cart/base.tpl"}
{block 'content'}
    <div class="cart_shipping-page default-content-page default-form" >
    <div class="ajax-calculate-shipping-form">
        <form action="/cart/calculate_shipping" method="post">
            <div class="row">
                <div class="column small-12">
                    {include 'checkout/_form_row.tpl' field=$form->getField('country')}
                    {include 'checkout/_form_row.tpl' field=$form->getField('zipcode')}
                    {include 'checkout/_form_row.tpl' field=$form->getField('state')}
                    {include 'checkout/_form_row.tpl' field=$form->getField('city')}
                </div>
            </div>
            <div class="row align-center">
                <div class="column small-12">
                    <div class="buttons text-center">
                        <button type="submit" class="button submit yellow waves waves-orange waves-effect">
                            {t 'Submit' dict='order'}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
{/block}