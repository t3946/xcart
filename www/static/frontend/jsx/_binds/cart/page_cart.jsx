import storeCart from 'stores/StoreCart';
import createAutoComplete from '_binds/cart/shipping_autocomplete';

(() => {
    let cart_container = document.querySelector('.cart-page');

    if (cart_container) {

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
                cart_container.appendChild(windowWrapper);

                console.log('1222222');

                $.ajax('/cart/calculate_shipping').done(function(html) {

                    //console.log(html);

                    $(window).html(html).mmodal({
                        'width': 750,
                        'onSubmit': function () {
                            $.ajax({
                                url: '/cart/calculate_shipping',
                                method: 'POST',
                                data: $('.mmodal-content form').serialize()
                            }).done(function(json) {
                                console.log(json);
                                let html = '';
                                for (let key in json) {
                                    html += '<div class="row align-center"><div class="name columns small-4">' + key
                                        + '</div><div class="value columns small-2">' + json[key] + '</div></div>';
                                }
                                $('.mmodal-content .ajax-calculate-shipping-form').html(html);
                            });
                        }
                    });
                    createAutoComplete('.mmodal-content');
                });
            }
        });

    }
})();