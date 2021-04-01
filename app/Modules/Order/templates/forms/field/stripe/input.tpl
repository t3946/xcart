{add $form = $field->getForm()}
{set $pi = $form->stripe_payment_intent}
{set $public_key = $form->public_key}
{set $order = $form->order}
<div id="payment-request-button"></div>
<div id="{$id}"></div>
<div id="card-errors" role="alert"></div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{$public_key}', {
        locale: 'en',
    });
    const elements = stripe.elements();
    const form = document.querySelector('form');
    const button = document.querySelector('button');
    const clientSecret = '{$pi}';
    const style = {
        base: {
            color: '#272727',
            fontSize: '20px',
            '::placeholder': {
                color: '#272727'
            }
        }
    };

    const paymentRequest = stripe.paymentRequest({
        country: '{$order->b_country ?: 'US'}',
        currency: '{$order->currency|strtolower}',
        total: {
            'label': 'Total',
            amount: {$order->total * 100}
        },
        requestPayerName: true,
        requestPayerEmail: true,
    });

    paymentRequest.on('paymentmethod', function(ev) {
        stripe.confirmCardPayment(
                clientSecret,
                { payment_method: ev.paymentMethod.id },
                { handleActions: false }
        ).then(function(confirmResult) {
            if (confirmResult.error) {
                ev.complete('fail');
            } else {
                ev.complete('success');
                stripe.confirmCardPayment(clientSecret).then(function(result) {
                    if (result.error) {
                        document.querySelector("#card-errors").textContent = result.error ? result.error.message : "";
                    } else {
                        window.location = button.dataset.return;
                    }
                });
            }
        });
    });

    const prButton = elements.create('paymentRequestButton', {
        paymentRequest: paymentRequest,
        classes: {
            base: 'checkout_stripe-element-button',
        },
    });

    paymentRequest.canMakePayment().then(function(result) {
        if (result) {
            prButton.mount('#payment-request-button');
        } else {
            document.getElementById('payment-request-button').style.display = 'none';
        }
    });

    const card = elements.create("card", {
        style: style,
        classes: {
            base: 'stripe-element common-input',
            complete: 'common-input__correct',
            invalid: 'common-input__wrong',
        },
    });

    card.mount("#{$id}");

    card.on("change", function (event) {
        document.querySelector("button").disabled = event.empty || event.error;
        document.querySelector("#card-errors").textContent = event.error ? event.error.message : "";
    });

    form.addEventListener('submit', function (ev) {
        ev.preventDefault();
        document.querySelector("button").disabled = true;
        stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: card,
                billing_details: {
                    address: {
                        city: '{$order->b_city ?: $order->s_city}',
                        country: '{$order->b_country ?: $order->s_country}',
                        line1: '{$order->getAddressInfo()[1]['address'][0] ?: $order->getAddressInfo()[0]['address'][0]}',
                        line2: '{$order->getAddressInfo()[1]['address'][1] ?: $order->getAddressInfo()[0]['address'][1]}',
                        postal_code: '{$order->b_zipcode ?: $order->s_zipcode}',
                        state: '{$order->b_state ?: $order->s_state}',
                    },
                    name: '{$order->b_firstname}',
                    email: '{$order->email}',
                    phone: '{$order->phone}',
                },
            },
            shipping: {
                address: {
                    line1: '{$order->getAddressInfo()[0]['address'][0]}',
                    line2: '{$order->getAddressInfo()[0]['address'][1]}',
                    city: '{$order->s_city}',
                    country: '{$order->s_country}',
                    postal_code: '{$order->s_zipcode}',
                    state: '{$order->s_state}',
                },
                name: '{$order->s_firstname}',
            }
        }).then(function (result) {
            if (result.error) {
                document.querySelector("button").disabled = false;
                document.querySelector("#card-errors").textContent = result.error ? result.error.message : "";
            } else {
                if (result.paymentIntent.status === 'requires_capture') {
                    window.location = button.dataset.return;
                }
            }
        });
    });
</script>