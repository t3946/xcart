{add $form = $field->getForm()}
{set $pi = $form->stripe_payment_intent}
{set $public_key = $form->public_key}

<div
    class="stripe-target"
    data-id="{$id}"
    data-pi="{$pi}"
    data-public_key="{$public_key}"
></div>