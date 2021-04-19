'use strict';

import _ from 'lodash';
import Checkout from '@/js/Components/checkout/Checkout';
import Pace from "pace-js";

(()=>{
    let page_cart = document.querySelector('.cart-page, .checkout-page');

    if (page_cart) {
        let n_request = 0;
        let recalc = () => {
            page_cart = document.querySelector('.cart-page');

            if (!page_cart) {
                return;
            }

            let products = page_cart.querySelectorAll('[data-product]');
            if (products) {
                let subtotals = Object.create(null), fullquantity = 0;
                subtotals.wh = Object.create(null);
                subtotals.cart = 0;

                for (let i = 0; products.length > i; ++i) {
                    let product = products[i];
                    let quantity = parseInt(product.dataset.quantity);
                    let subtotal = product.dataset.price * quantity;

                    fullquantity += quantity;

                    subtotals.cart += subtotal;
                    subtotals.wh[product.dataset.wh] = subtotal + (subtotals.wh[product.dataset.wh] || 0);
                }

                let whs = page_cart.querySelectorAll('.warehouse_subtotal');

                for (let i = 0; whs.length > i; ++i) {
                    let wh = whs[i];
                    wh.querySelector('.subtotal').innerHTML = toLocaleCurrency(subtotals.wh[wh.dataset.wh]);
                }

                page_cart.querySelector('.cart_subtotal').innerHTML = toLocaleCurrency(subtotals.cart);
                page_cart.dataset.total = subtotals.cart;
                page_cart.dataset.quantity = fullquantity;
            }
        };

        let sync = _.throttle(product => {
            let key = product.dataset.key;
            let quantity = parseInt(product.dataset.quantity) || 1;
            let number_request = ++n_request;
            const data = {
                uid: key,
                quantity: quantity,
            };

            Checkout.update( data, function ( res ) {
                const page_cart = document.querySelector('.checkout-page');
                let p_data = page_cart.dataset;
                let cartQuantity = 0;

                for ( const dxCartId in res[ 'distributor_carts' ] ) {
                    cartQuantity += res[ 'distributor_carts' ][ dxCartId ].quantity;
                }

                if ( number_request === n_request && ( p_data.quantity != cartQuantity || p_data.total != res.total ) )
                {
                    Pace.ignore(function () {
                        $.get('/cart/?_=' + (new Date).getTime(), {}).done(data => {
                            if (number_request === n_request) {
                                //do not update checkout one page
                                if ($('.checkout-page').length ) {
                                    return;
                                }

                                $(page_cart).html(data.content || data);
                                window.LazyLoad.update();
                            }
                        });
                    });
                }
            });
        }, 200);

        let updateCart = _.throttle(product => {
            recalc();
            sync(product);

        }, 200);

        $(document).on('component.quantity.change', (e, data) => updateCart(data.product));
    }
})();