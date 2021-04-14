{add $form = $field->getForm()}
{set $pi = $form->stripe_payment_intent}
{set $public_key = $form->public_key}

<div class="stripe-target"></div>

<script>
    window.app.options.payByCardForm = {
        stripeField: {
            id: '{$id}',
            pi: '{$pi}',
            public_key: '{$public_key}',
        },
    };
</script>
