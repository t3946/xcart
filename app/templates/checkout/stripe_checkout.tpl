{extends "checkout/base.tpl"}

{block 'content'}
    <script src="https://js.stripe.com/v3/"></script>
    <section class="page pages receipt-confirmation bg-dark-blue" style="padding-top: 5em; padding-bottom: 5em;">
        <div class="vertical-middle">
            <div class="row w1280 align-middle">
                <div class="hide-for-small-only columns medium-2 large-3"></div>
                <div class="columns small-12 medium-8 large-6">
                    <div style="text-align: center">
                        <form id="payment-form" style="width:100%; background-color:#efefef;">
                            <div id="payment-request-button"></div>
                            <h1 style="text-align: center; margin-bottom: 10px; padding-top: 0;">Secure credit card payment</h1>
                            <div style="font-size:21px; text-align: center; margin-bottom: 2rem;">Total: <span style="font-size:21px">{$site_currency}{$site_currency->getCurrencyFormat($order->total)}</span></div>
                            <div id="card-element">
                            </div>
                            <div id="card-errors" role="alert" style="text-align: center"></div>

                            <div style="margin-top: 2rem;" class="row align-center">
                                <div class="column small-12">
                                    <div class="buttons text-center">
                                        {set $shipping_info = $order->getAddressInfo()[0]}
                                        {set $billing_info = $order->getAddressInfo()[1]}
                                        <button style="min-width:230px;" data-secret="{$client_secret}"
                                                data-name="{$billing_info.firstname}"
                                                data-address1="{$billing_info.address[0]}"
                                                data-address2="{$billing_info.address[1]}"
                                                data-zipcode="{$billing_info.zipcode}"
                                                data-country="{$billing_info.country->code}"
                                                data-state="{$billing_info.state->state}"
                                                data-city="{$billing_info.city}"
                                                data-s_name="{$shipping_info.firstname}"
                                                data-s_address1="{$shipping_info.address[0]}"
                                                data-s_address2="{$shipping_info.address[1]}"
                                                data-s_zipcode="{$shipping_info.zipcode}"
                                                data-s_country="{$shipping_info.country->code}"
                                                data-s_state="{$shipping_info.state->state}"
                                                data-s_city="{$shipping_info.city}"
                                                data-email="{$order->email}"
                                                data-phone="{$order->phone}"
                                                data-return="{$returnUrl}"
                                                id="submit"
                                                class="button submit yellow waves waves-orange waves-effect">Pay now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="hide-for-small-only columns medium-2 large-3"></div>
            </div>
        </div>
    </section>
    <script>
        var stripe = Stripe('{$public_key}');
        var elements = stripe.elements();

        var style = {
            base: {
                color: "#32325d",
                
            }
        };
        var paymentRequest = stripe.paymentRequest({
            country: '{$billing_info.country->code}',
            currency: '{$site_currency->currency_code|strtolower}',
            total: {
                label: 'Total',
                amount: {$order->total * 100}
            }
        });
        {ignore}
        var prButton = elements.create('paymentRequestButton', {
            paymentRequest: paymentRequest
        });
        paymentRequest.canMakePayment().then(function(result) {
            if (result) {
                prButton.mount('#payment-request-button');
            } else {
                document.getElementById('payment-request-button').style.display = 'none';
            }
        });
        var card = elements.create("card", {style: style});
        card.mount("#card-element");
        card.on("change", function (event) {
            document.querySelector("button").disabled = event.empty || event.error;
            document.querySelector("#card-errors").textContent = event.error ? event.error.message : "";
        });
        var form = document.getElementById('payment-form');
        var button = document.querySelector("button");
        var clientSecret = button.dataset.secret;

        form.addEventListener('submit', function (ev) {
            ev.preventDefault();
            document.querySelector("button").disabled = true;
            stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        address: {
                            city: button.dataset.city,
                            country: button.dataset.country,
                            line1: button.dataset.address1,
                            line2: button.dataset.address2,
                            postal_code: button.dataset.zipcode,
                            state: button.dataset.state,
                        },
                        name: button.dataset.name,
                        email: button.dataset.email,
                        phone: button.dataset.phone,
                    },
                },
                return_url: button.dataset.return,
                shipping: {
                    address: {
                        line1: button.dataset.s_address1,
                        line2: button.dataset.s_address2,
                        city: button.dataset.s_city,
                        country: button.dataset.s_country,
                        postal_code: button.dataset.s_zipcode,
                        state: button.dataset.s_state,
                    },
                    name: button.dataset.s_name,
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
        {/ignore}
    </script>
    {ignore}
        <style>
            form {

                align-self: center;

                box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
                0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);

                border-radius: 7px;

                padding: 40px;

            }

            input {

                border-radius: 6px;

                margin-bottom: 6px;

                padding: 12px;

                border: 1px solid rgba(50, 50, 93, 0.1);

                height: 44px;

                font-size: 16px;

                width: 100%;

                background: white;

            }

            .result-message {

                line-height: 22px;

                font-size: 16px;

            }

            .result-message a {

                color: rgb(89, 111, 214);

                font-weight: 600;

                text-decoration: none;

            }

            .hidden {

                display: none;

            }

            #card-errors {

                color: red;

                text-align: left;

                /*font-size: 13px;*/

                line-height: 17px;

                margin-top: 12px;

            }

            #card-element {

                border-radius: 4px 4px 0 0;

                padding: 12px;

                border: 1px solid rgba(50, 50, 93, 0.1);

                height: 46px;

                width: 100%;

                background: white;

            }

            #payment-request-button {

                margin-bottom: 32px;

            }

            /* Buttons and links */

            /*button {

                background: #5469d4;

                color: #ffffff;

                font-family: Arial, sans-serif;

                border-radius: 0 0 4px 4px;

                border: 0;

                padding: 12px 16px;

                font-size: 16px;

                font-weight: 600;

                cursor: pointer;

                display: block;

                transition: all 0.2s ease;

                box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);

                width: 100%;

            }*/

            button:hover {

                filter: contrast(115%);

            }

            button:disabled {

                opacity: 0.5;

                cursor: default;

            }

            /* spinner/processing state, errors */

            .spinner,
            .spinner:before,
            .spinner:after {

                border-radius: 50%;

            }

            .spinner {

                color: #ffffff;

                font-size: 22px;

                text-indent: -99999px;

                margin: 0px auto;

                position: relative;

                width: 20px;

                height: 20px;

                box-shadow: inset 0 0 0 2px;

                -webkit-transform: translateZ(0);

                -ms-transform: translateZ(0);

                transform: translateZ(0);

            }

            .spinner:before,
            .spinner:after {

                position: absolute;

                content: "";

            }

            .spinner:before {

                width: 10.4px;

                height: 20.4px;

                background: #5469d4;

                border-radius: 20.4px 0 0 20.4px;

                top: -0.2px;

                left: -0.2px;

                -webkit-transform-origin: 10.4px 10.2px;

                transform-origin: 10.4px 10.2px;

                -webkit-animation: loading 2s infinite ease 1.5s;

                animation: loading 2s infinite ease 1.5s;

            }

            .spinner:after {

                width: 10.4px;

                height: 10.2px;

                background: #5469d4;

                border-radius: 0 10.2px 10.2px 0;

                top: -0.1px;

                left: 10.2px;

                -webkit-transform-origin: 0px 10.2px;

                transform-origin: 0px 10.2px;

                -webkit-animation: loading 2s infinite ease;

                animation: loading 2s infinite ease;

            }

            @-webkit-keyframes loading {

                0% {

                    -webkit-transform: rotate(0deg);

                    transform: rotate(0deg);

                }

                100% {

                    -webkit-transform: rotate(360deg);

                    transform: rotate(360deg);

                }

            }

            @keyframes loading {

                0% {

                    -webkit-transform: rotate(0deg);

                    transform: rotate(0deg);

                }

                100% {

                    -webkit-transform: rotate(360deg);

                    transform: rotate(360deg);

                }

            }

            @media only screen and (max-width: 600px) {

                form {

                    width: 80vw;

                }

            }
        </style>
    {/ignore}
{/block}