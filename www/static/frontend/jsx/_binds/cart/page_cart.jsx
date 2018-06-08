import storeCart from 'stores/StoreCart';
import createAutoComplete from '_binds/cart/shipping_autocomplete';

(() => {
    let cartContainer = document.querySelector('.cart-page');

    if (cartContainer) {

        storeCart.dispatch({type: 'FETCH'});

        let logo = document.querySelector('.s3-logo-big');
        let keyPressed = false;
        let window, windowWrapper;

        let getChar = (event) => {

            if (event.which && (event.charCode || event.keyCode)) {
                if (event.which < 32) {
                    return null;
                }
                return String.fromCharCode(event.which);
            }
            // спец. символ
            return null;
        };

        document.addEventListener('keydown', (event) => {
            let symbol = getChar(event);
            if (symbol == 'Q') {
                keyPressed = true;
            }
        });

        document.addEventListener('keyup', (event) => {
            let symbol = getChar(event);
            if (symbol == 'Q') {
                keyPressed = false;
            }
        });

        logo.addEventListener('click', (event) => {

            if (keyPressed) {
                event.preventDefault();
                event.stopPropagation();

                window = window || document.createElement('div');
                windowWrapper = windowWrapper || document.createElement('div');

                window.setAttribute('id', 'shippingCalc');
                windowWrapper.style.position = 'absolute';
                windowWrapper.style.left = '-9999px';
                windowWrapper.style.right = '-9999px';
                windowWrapper.style.display = 'none';

                windowWrapper.appendChild(window);
                cartContainer.appendChild(windowWrapper);

                $.ajax('/cart/calculate_shipping').done(function(html) {

                    $(window).html(html).mmodal({
                        'width': 750,
                        'onSubmit': function () {
                            $.ajax({

                                url: '/cart/calculate_shipping',
                                method: 'POST',
                                data: $('.mmodal-content form').serialize()

                            }).done(function(result) {

                                if(result.type && result.type == 'json') {
                                    let html = '';
                                    let json = result.result;
                                    for (let key in json) {
                                        html += '<div class="row align-center"><div class="name columns small-4">' + key
                                            + '</div><div class="value columns small-2">' + json[key] + '</div></div>';
                                    }
                                    $('.mmodal-content .ajax-calculate-shipping-form').html(html);
                                } else {
                                    $('.mmodal-content .ajax-calculate-shipping-form').html(result);
                                }

                            });
                        }
                    });
                    createAutoComplete('.mmodal-content');
                });
            }
        });

    }
})();